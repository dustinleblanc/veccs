<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    const THEME_DIR = self::DRUPAL_ROOT . 'profiles/recover/themes/recover_theme';
    use \Boedah\Robo\Task\Drush\loadTasks;
    const DRUPAL_ROOT = __DIR__ . '/web';
    const DRUSH_BIN = __DIR__ . '/vendor/bin/drush';
    const BEHAT_BIN = __DIR__ . '/vendor/bin/behat';

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
