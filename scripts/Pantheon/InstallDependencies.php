<?php
/**
 * Created by PhpStorm.
 * User: dustinleblanc
 * Date: 5/25/16
 * Time: 8:54 AM
 */

if (defined('PANTHEON_ENVIRONMENT') && (PANTHEON_ENVIRONMENT == 'dev') {
  shell_exec('composer install --no-dev');
  shell_exec('git commit -am "Installed Dependencies"');
}