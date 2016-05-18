<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
  use \Boedah\Robo\Task\Drush\loadTasks;

  const DRUSH_BIN = __DIR__ . '/vendor/bin/drush';
  const DRUPAL_ROOT = __DIR__ . '/web';

  // define public methods as commands
  public function install()
  {
    $this->buildDrushTask()
      ->siteInstall();
  }

  public function serve()
  {
    $this->taskServer(8000)
      ->dir(self::DRUPAL_ROOT)
      ->env(['PRESSFLOW_SETTINGS' => file_get_contents('env.json')])
      ->run();
  }

  protected function buildDrushTask() {
    return $this->taskDrushStack(self::DRUSH_BIN)
      ->dir(self::DRUPAL_ROOT);
  }
}
