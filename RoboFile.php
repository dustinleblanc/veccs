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
  public function dbLoad($uri = 'default')
  {
    $this->buildDrushTask($uri)
         ->stopOnFail(true)
         ->exec("sql-cli < " . __DIR__ . "/{$uri}.dump.sql")
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
   * @return $this
   */
  protected function buildDrushTask($uri = 'default') {
    return $this->taskDrushStack(self::DRUSH_BIN)
                ->uri($uri)
                ->dir(self::DRUPAL_ROOT);
  }

  protected function testSiteIsLoading()
  {
    $ch = curl_init('http://localhost:8889');
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if($httpcode >= 200 && $httpcode < 300){
      return true;
    } else {
      return false;
    }
  }
}
