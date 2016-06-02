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

  /**
   *
   * Dump the current database and load a dump file.
   *
   * @param string $dumpFile
   */
  public function dbLoad($dumpFile = '', $uri = 'default')
  {
    if (empty($dumpFile)) {
      $dumpFile = __DIR__ . "/dump.sql";
    }
    $this->buildDrushTask($uri)
         ->stopOnFail(true)
         ->exec("sql-dump --result-file=" . __DIR__ . "/{$uri}.dump.sql")
         ->exec("sql-cli < $dumpFile")
         ->run();
  }

  /**
   * Install Development site.
   */
  public function install($uri = 'default')
  {
    $this->buildDrushTask($uri)
         ->siteInstall('recover')
         ->run();
  }

  public function serve($uri = 'default')
  {
    $port = ($uri == 'test') ? 8000 : 8888;
    $this->buildDrushTask($uri)
         ->exec("rs $port")
         ->run();
  }

  /**
   * Run Test suite.
   */
  public function test()
  {
    $this->install('test');
    $this->buildDrushTask('test')
         ->exec('config-import')
         ->run();
    $this->taskCodecept(self::CEPT_BIN)
         ->run();
  }

  /**
   * @return $this
   */
  protected function buildDrushTask($uri = 'default') {
    return $this->taskDrushStack(self::DRUSH_BIN)
                ->uri($uri)
                ->dir(self::DRUPAL_ROOT);
  }


}
