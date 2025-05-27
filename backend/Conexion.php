<?php
    require '../vendor/autoload.php';
    use Cycle\Database\Config\DatabaseConfig;
    use Cycle\Database\Config\MySQLDriverConfig;
    use Cycle\Database\DatabaseManager;
    use Cycle\Database\Driver\MySQL\MySQLDriver;


    $dbal = new DatabaseManager(
        new DatabaseConfig([
            'default' => 'default',
            'databases' => [
                'default' => [
                    'connection' => 'mysql',
                ],
            ],
            'connections' => [
                'mysql' => new MySQLDriverConfig(
                    connection: new \Cycle\Database\Config\MySQL\DsnConnectionConfig(
                        dsn: 'mysql:host=localhost;dbname=citas_medicas',
                        user: 'root',
                        password: '',
                        options: [
                            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                        ],
                    ),
                ),
            ],
        ])
    );


    $database = $dbal->database('default');

    //$result = $database->table('patients')->select()->fetchAll();
    //print_r($result);
