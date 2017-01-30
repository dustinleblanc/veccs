<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks {

  const CEPT_BIN = __DIR__ . '/vendor/bin/codecept';
  const DRUPAL_ROOT = __DIR__ . '/web';
  const TARGET_DIR = '../pantheon_veccs';
  const TERMINUS_BIN = './vendor/bin/terminus';
  const SEED_DB = __DIR__ . '/docker-runtime/mariadb-init/seed.sql.gz';


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

  public function test() {
    $this->_exec('vendor/bin/drush --root=web cim');
    $this->_exec('vendor/bin/drush --root=web updb');
    $this->taskCodecept(self::CEPT_BIN)
      ->run();
  }
}
