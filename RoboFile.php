<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks {
  use \Boedah\Robo\Task\Drush\loadTasks;

  const CEPT_BIN = __DIR__ . '/vendor/bin/codecept';
  const DRUSH_BIN = __DIR__ . '/vendor/bin/drush';
  const DRUPAL_ROOT = __DIR__ . '/web';
  const TARGET_DIR = '../pantheon_veccs';
  const TERMINUS_BIN = './vendor/bin/terminus';

  /**
   * RoboFile constructor.
   */
  public function __construct() {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
  }

  /**
   * Build a deployable artifact.
   */
  public function buildArtifact() {
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

  public function checkFeatures() {
    $this->dbDump();
    $this->syncDb('dev');
    $this->buildDrushTask()
         ->exec('updb --entity-updates')
         ->exec('features-import recover core ')
         ->run();
  }

  /**
   * Cleans out target repo to replace with our build files.
   */
  public function cleanTargetRepository() {
    $this->taskGitStack()
         ->dir(self::TARGET_DIR)
         ->exec("rm -rf .")
         ->exec("clean -fxd")
         ->run();

  }

  /**
   * Pull Pantheon repository and build a deployable artifact
   */
  public function deploy() {
    $this->pullTargetRepository();
    $this->cleanTargetRepository();
    $this->buildArtifact();
    $this->pushToTarget();
  }

  /**
   * Install Drupal with our install profile.
   */
  public function install($uri = 'default') {
    $this->buildDrushTask($uri)
         ->siteInstall('recover')
         ->run();
  }

  /**
   * Clone Pantheon repository into target directory.
   */
  public function pullTargetRepository() {
    $this->taskGitStack()
         ->cloneRepo(self::PANTHEON_REPO, self::TARGET_DIR)
         ->run();
  }

  public function pushToTarget() {
    $this->taskGitStack()
         ->dir(self::TARGET_DIR)
         ->push(self::PANTHEON_REPO, 'master')
         ->run();
  }

  public function syncDb($env = 'dev', $uri = 'default') {
    $this->_exec('./vendor/bin/terminus sites aliases');
    $alias = "@pantheon.veccs.{$env}";
    $this->buildDrushTask($uri)
         ->exec("sql-sync {$alias} @self")
         ->run();
  }

  public function ciTest() {
    $this->syncDb('dev');
    $this->taskOpenBrowser('http://localhost:8000')->run();
    return $this->taskCodecept(self::CEPT_BIN)
                ->env('ci')
                ->run();
  }

  /**
   * Run Test suite.
   */
  public function test() {
    $this->syncDb('dev');
    $this->taskCodecept(self::CEPT_BIN)
         ->env('dev')
         ->run();
  }

  /**
   * Stub out Drush tasks with common arguments.
   *
   * @param string $uri
   *
   * @return \Boedah\Robo\Task\Drush\DrushStack
   */
  protected function buildDrushTask($uri = 'default') {
    return $this->taskDrushStack(self::DRUSH_BIN)
                ->uri($uri)
                ->dir(self::DRUPAL_ROOT);
  }
}
