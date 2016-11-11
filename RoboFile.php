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
  const SEED_DB = __DIR__ . '/docker-runtime/mariadb-init/seed.sql.gz';

  /**
   * RoboFile constructor.
   */
  public function __construct() {
    if (file_exists(__DIR__ . '/.env')) {
      $dotenv = new Dotenv\Dotenv(__DIR__);
      $dotenv->load();
    }
  }

  /**
   * Build a deployable artifact.
   */
  public function buildArtifact() {
    $buildNum     = getenv('CIRCLE_BUILD_NUM') ?: '';
    $buildUrl     = getenv('CIRCLE_BUILD_URL') ?: '';
    $pullRequests = getenv('CI_PULL_REQUESTS') ?: '';
    $author       = getenv('CIRCLE_USERNAME') ?: '';

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
    $this->taskBowerInstall('bower')
      ->dir(self::TARGET_DIR . '/web/profiles/recover/themes/recover_theme')
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

  public function develop() {
    $cmd = "docker-compose -f docker-compose.yml -f docker-compose.test.yml";
    if ($local_compose = getenv('LOCAL_COMPOSE')) {
      $cmd .= " -f {$local_compose}";
    }
    $this->taskExec("{$cmd} up -d")->run();
  }


  /**
   * Run a command in the PHP container
   *
   * @param string $op Command to run in PHP container.
   */
  public function docker($op = '') {
    $this->taskExec("docker-compose run php vendor/bin/robo {$op}")->run();
  }

  /**
   * Install Drupal with our install profile.
   *
   * @param string $uri
   */
  public function install($uri = 'default') {
    $this->buildDrushTask($uri)
      ->siteInstall('recover')
      ->run();
  }

  public function setup() {
    $this->taskFilesystemStack()
      ->copy(__DIR__ . '/docker/seed.sql',
        'docker-runtime/mariadb-init/seed.sql')
      ->copy(__DIR__ . '/docker/seed.sql',
        'docker-runtime/testdb-init/seed.sql')
      ->run();
  }

  /**
   * Clone Pantheon repository into target directory.
   */
  public function pullTargetRepository() {
    $this->taskGitStack()
      ->cloneRepo(getenv('PANTHEON_REPO'), self::TARGET_DIR)
      ->run();
  }

  /**
   *
   */
  public function pushToTarget() {
    $this->taskGitStack()
      ->dir(self::TARGET_DIR)
      ->push(getenv('PANTHEON_REPO'), 'master')
      ->run();
  }

  /**
   * @param string $env
   * @param string $uri
   */
  public function syncDb($env = 'dev', $uri = 'default') {
    $this->_exec(self::TERMINUS_BIN . ' auth login --machine-token=' . getenv('TERMINUS_TOKEN'));
    $this->_exec(self::TERMINUS_BIN . ' sites aliases');
    $this->buildDrushTask()
      ->clearCache('drush')
      ->run();
    $alias = "@pantheon.veccs.{$env}";
    $this->buildDrushTask($uri)
      ->exec("sql-sync {$alias} @self")
      ->run();
  }

  /**
   * Run Test suite.
   *
   * @param string $env
   */
  public function test($env = 'dev') {
    $this->buildDrushTask()
      ->exec('config-import')
      ->run();
    $this->taskCodecept(self::CEPT_BIN)
      ->env("{$env}")
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
