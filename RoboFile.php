<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    use \Boedah\Robo\Task\Drush\loadTasks;

    const CEPT_BIN = __DIR__ . '/vendor/bin/codecept';
    const DRUSH_BIN = __DIR__ . '/vendor/bin/drush';
    const DRUPAL_ROOT = __DIR__ . '/web';
    const DB_DUMP = __DIR__ . '/tests/_data/dump.sql';
    const TARGET_DIR = '../pantheon';
    const PANTHEON_REPO = "ssh://codeserver.dev.66a2727f-da39-4586-a2bb-ba803695ca4a@codeserver.dev.66a2727f-da39-4586-a2bb-ba803695ca4a.drush.in:2222/~/repository.git";

    /**
     * Build a deployable artifact.
     */
    public function buildArtifact()
    {
        $this->taskMirrorDir([__DIR__, self::TARGET_DIR]);
        $this->taskComposerInstall()
             ->noDev()
             ->dir(self::TARGET_DIR)
             ->run();

        $this->taskGitStack()
            ->add('-A')
            ->commit('Compile Dependencies')
            ->push(self::PANTHEON_REPO, 'master')
            ->run();
    }

    /**
     * Cleans out target repo to replace with our build files.
     */
    public function cleanTargetRepository()
    {
        $this->taskCleanDir(self::TARGET_DIR)->run();
    }

    /**
     * Dump the database to a file.
     *
     * @param string $uri
     */
    public function dbDump($uri = 'default')
    {
        $this->buildDrushTask($uri)
             ->stopOnFail(true)
             ->exec("sql-dump --result-file=" . __DIR__ . "/{$uri}.dump.sql")
             ->run();
    }

    /**
     *
     * Load a database dump file.
     *
     * @param string $dumpFile
     */
    public function dbLoad($uri = 'default')
    {
        $this->buildDrushTask($uri)
             ->stopOnFail(true)
             ->exec("sql-cli < " . __DIR__ . "/{$uri}.dump.sql")
             ->run();
    }

    /**
     * Pull Pantheon repository and build a deployable artifact
     */
    public function deploy()
    {
        $this->pullTargetRepository();
        $this->cleanTargetRepository();
        $this->buildArtifact();
        $this->pushToTarget();
    }

    /**
     * Install Drupal with our install profile.
     */
    public function install($uri = 'default')
    {
        $this->buildDrushTask($uri)
             ->siteInstall('recover')
             ->run();
    }

    /**
     * Clone Pantheon repository into target directory.
     */
    public function pullTargetRepository()
    {
        $this->taskGitStack()
             ->cloneRepo(self::PANTHEON_REPO, self::TARGET_DIR)
             ->run();
    }

    public function serve($uri = 'default')
    {
        $port = ($uri == 'test') ? 8889 : 8888;
        $this->buildDrushTask($uri)
             ->exec("rs $port")
             ->run();
    }

    /**
     * Run Test suite.
     */
    public function test()
    {
        if ($this->testSiteIsLoading()) {
            $this->dbDump('test');
            if ($this->taskCodecept(self::CEPT_BIN)
                     ->run()
                     ->wasSuccessful()) {
                $this->dbLoad('test');
            }
        } else {
            $this->say('Test site is not loading, make sure you have a server running!');
        }
    }

    /**
     * Stub out Drush tasks with common arguments.
     *
     * @param string $uri
     * @return $this
     */
    protected function buildDrushTask($uri = 'default') {
        return $this->taskDrushStack(self::DRUSH_BIN)
                    ->uri($uri)
                    ->dir(self::DRUPAL_ROOT);
    }

    /**
     * Ensure test server is up before running acceptance tests.
     *
     * @return bool
     */
    protected function testSiteIsLoading()
    {
        // Ping our test url
        $ch = curl_init('http://localhost:8889');
        // Hide curl's output
        curl_setopt(CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpcode >= 200 && $httpcode < 300);
    }
}
