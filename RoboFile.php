<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    use \Boedah\Robo\Task\Drush\loadTasks;
    const DRUPAL_ROOT = __DIR__ . '/web';
    const DRUSH_BIN = __DIR__ . '/vendor/bin/drush';
    const BEHAT_BIN = __DIR__ . '/vendor/bin/behat';

    public function test()
    {
        $this->taskBehat(self::BEHAT_BIN)->run();
    }

    public function serve()
    {
        $this->buildDrushTask()
            ->exec('rs')
            ->run();
    }

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

    private function buildDrushTask()
    {
        return $this->taskDrushStack(self::DRUSH_BIN)
            ->drupalRootDirectory(self::DRUPAL_ROOT);
    }
}
