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
    const TARGET_DIR = '../pantheon_veccs';
    const PANTHEON_REPO = "ssh://codeserver.dev.66a2727f-da39-4586-a2bb-ba803695ca4a@codeserver.dev.66a2727f-da39-4586-a2bb-ba803695ca4a.drush.in:2222/~/repository.git";

    /**
     * Build a deployable artifact.
     */
    public function buildArtifact()
    {
        $this->taskRsync()
             ->fromPath(__DIR__ . "/")
             ->toPath(self::TARGET_DIR)
             ->excludeVcs()
             ->exclude('vendor/')
             ->recursive()
             ->run();
        $this->taskComposerInstall()
             ->noDev()
             ->dir(self::TARGET_DIR)
             ->run();

        $this->taskGitStack()
             ->dir(self::TARGET_DIR)
             ->add('-A')
             ->add('vendor -f')
             ->add('web/core -f')
             ->add('web/sites/default/settings.php -f')
             ->add('web/sites/default/settings.pantheon.php -f')
             ->add('web/themes/contrib -f')
             ->add('web/modules/contrib -f')
             ->commit('Compile Dependencies')
             ->run();
    }

    public function checkFeatures()
    {
        $this->dbDump();
        $this->syncDb('dev');
        $this->buildDrushTask()
             ->exec('fia')
             ->run();
    }
    /**
     * Cleans out target repo to replace with our build files.
     */
    public function cleanTargetRepository()
    {
        $this->taskGitStack()
             ->dir(self::TARGET_DIR)
             ->exec("rm -rf .")
             ->exec("clean -fxd")
             ->run();

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

    public function pushToTarget()
    {
        $this->taskGitStack()
             ->dir(self::TARGET_DIR)
             ->push(self::PANTHEON_REPO, 'master')
             ->run();
    }

    public function serve($uri = 'default')
    {
        $port = ($uri == 'test') ? 8889 : 8888;
        $this->buildDrushTask($uri)
             ->exec("rs $port")
             ->run();
    }

    public function syncDb($env = 'dev')
    {
        $alias = "@pantheon.veccs.{$env}";
        $this->buildDrushTask()
             ->exec("sql-sync {$alias} @self")
             ->run();
    }
    /**
     * Run Test suite.
     */
    public function test()
    {
        if (!defined('CI')) {
            $this->dbDump('test');
        }
        if ($this->taskCodecept(self::CEPT_BIN)
                 ->run()
                 ->wasSuccessful() && !defined('CI')) {
            $this->dbLoad('test');
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
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpcode >= 200 && $httpcode < 300);
    }
}
