<?php
$aliases["dev"] = array (
  'root' => '/Users/dustinleblanc/code/php/veccs2/web',
  'uri' => 'http://recover.dev:8888',
  'databases' =>
    array (
      'default' =>
        array (
          'default' =>
            array (
              'database' => 'recover_dev',
              'username' => 'root',
              'password' => '',
              'host' => 'localhost',
              'port' => '3306',
              'driver' => 'mysql',
              'prefix' =>
                array (
                  'default' => '',
                ),
              'collation' => 'utf8mb4_general_ci',
            ),
        ),
    ),
);

$aliases["test"] = array (
  'root' => '/Users/dustinleblanc/code/php/veccs2/web',
  'uri' => 'http://localhost:8000',
  'databases' =>
    array (
      'default' =>
        array (
          'default' =>
            array (
              'database' => 'recover_test',
              'username' => 'root',
              'password' => '',
              'host' => 'localhost',
              'port' => '3306',
              'driver' => 'mysql',
              'prefix' =>
                array (
                  'default' => '',
                ),
              'collation' => 'utf8mb4_general_ci',
            ),
        ),
    ),
);
