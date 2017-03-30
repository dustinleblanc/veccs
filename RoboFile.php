<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    use \Boedah\Robo\Task\Drush\loadTasks;
    const THEME_DIR = self::DRUPAL_ROOT . 'profiles/recover/themes/recover_theme';
    const DRUPAL_ROOT = __DIR__ . '/web';
    const DRUSH_BIN = __DIR__ . '/vendor/bin/drush';
    const BEHAT_BIN = __DIR__ . '/vendor/bin/behat';
    const TARGET_DIR = '../pantheon_veccs';
    const TERMINUS_BIN = './vendor/bin/terminus';

    /**
     * Build a deployable artifact.
     */
    public function buildArtifact()
    {
        $buildNum = getenv('CIRCLE_BUILD_NUM') ?: '';
        $buildUrl = getenv('CIRCLE_BUILD_URL') ?: '';
        $pullRequests = getenv('CI_PULL_REQUESTS') ?: '';
        $author = getenv('CIRCLE_USERNAME') ?: '';
        $commitMsg = <<<EOF
CircleCI Build number: $buildNum
CircleCI Build URL: $buildUrl
Included pull requests: $pullRequests
Authored by: $author
EOF;
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
            ->exec("config user.email " . getenv('GIT_EMAIL'))
            ->exec("config user.name " . getenv('GIT_USERNAME'))
            ->dir(self::TARGET_DIR)
            ->add('-A')
            ->add('config -f')
            ->add('vendor -f')
            ->add('web/core -f')
            ->add('web/sites/default/settings.php -f')
            ->add('web/sites/default/settings.pantheon.php -f')
            ->add('web/themes -f')
            ->add('web/modules -f')
            ->add('web/profiles/recover/themes/recover_theme/bower_components -f')
            ->commit($commitMsg)
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
     * Clone Pantheon repository into target directory.
     */
    public function pullTargetRepository()
    {
        $this->taskGitStack()
            ->cloneRepo(getenv('PANTHEON_REPO'), self::TARGET_DIR)
            ->run();
    }

    /**
     *
     */
    public function pushToTarget()
    {
        $this->taskGitStack()
            ->dir(self::TARGET_DIR)
            ->push(getenv('PANTHEON_REPO'), 'master')
            ->run();
    }

    /**
     * Run Test suite.
     */
    public function test()
    {
        $this->taskBehat(self::BEHAT_BIN)->run();
    }

    /**
     * Run the Drush webserver.
     */
    public function serve()
    {
        $this->buildDrushTask()
            ->exec('rs')
            ->run();
    }

    /**
     * Install Drupal with simple config.
     */
    public function siteInstall()
    {
        $this->buildDrushTask()
            ->siteName('Recover Drupal')
            ->siteMail('site-mail@example.com')
            ->locale('en')
            ->accountMail('mail@example.com')
            ->accountName('admin')
            ->accountPass('pw')
            ->disableUpdateStatusModule()
            ->siteInstall('recover')
            ->run();
    }

    /**
     * Provision the database seed for Docker.
     */
    public function dbSeed()
    {
        $this->_exec('gunzip dump.sql.gz');
        $this->taskFilesystemStack()
            ->mkdir('mariadb-init')
            ->remove('mariadb-init/dump.sql')
            ->rename('dump.sql', 'mariadb-init/dump.sql')
            ->run();
    }

    public function build()
    {
        $this->taskNpmInstall()
            ->dir(self::THEME_DIR)
            ->run();
        $this->taskBowerInstall()
            ->dir(self::THEME_DIR)
            ->run();
        $this->say('Theme assets built!');
    }

    private function buildDrushTask()
    {
        return $this->taskDrushStack(self::DRUSH_BIN)
            ->drupalRootDirectory(self::DRUPAL_ROOT);
    }
}
