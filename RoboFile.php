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


  /**
   * Install Development site.
   */
  public function install($uri = 'default')
  {
    $this->buildDrushTask()
         ->uri($uri)
         ->siteInstall('recover')
         ->run();
  }

  public function serve($uri = 'default')
  {
    $this->buildDrushTask()
         ->exec('rs')
         ->uri($uri)
         ->run();
  }

  /**
   * Run Test suite.
   */
  public function test()
  {
    $this->taskCodecept(self::CEPT_BIN)
         ->run();
  }

  /**
   * @return $this
   */
  protected function buildDrushTask() {
    return $this->taskDrushStack(self::DRUSH_BIN)
                ->dir(self::DRUPAL_ROOT);
  }

}
