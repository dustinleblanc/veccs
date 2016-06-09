<?php
$aliases["dev"] = [
  'root' => '/Users/dustinleblanc/code/php/veccs2/web',
  'uri' => 'default',
  'databases' =>
    [
      'default' =>
        [
          'default' =>
            [
              'database' => 'recover_dev',
              'username' => 'root',
              'password' => '',
              'host' => 'localhost',
              'port' => '3306',
              'driver' => 'mysql',
              'prefix' =>
                [
                  'default' => '',
                ],
              'collation' => 'utf8mb4_general_ci',
            ],
        ],
    ],
];

$aliases["test"] = [
  'root' => '/Users/dustinleblanc/code/php/veccs2/web',
  'uri' => 'test',
  'databases' =>
    [
      'default' =>
        [
          'default' =>
            [
              'database' => 'recover_test',
              'username' => 'root',
              'password' => '',
              'host' => 'localhost',
              'port' => '3306',
              'driver' => 'mysql',
              'prefix' =>
                [
                  'default' => '',
                ],
              'collation' => 'utf8mb4_general_ci',
            ],
        ],
    ],
];
