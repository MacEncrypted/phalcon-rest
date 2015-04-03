<?php

use Phalcon\Mvc\Micro as Micro;
use \Phalcon\Loader as Loader;
use Phalcon\Db\Adapter\Pdo\Mysql as MysqlAdapter;
use Phalcon\Config\Adapter\Ini as ConfigIni;

// Setup loader
$loader = new Loader();
$loader->registerDirs(array(
    __DIR__ . '/models/'
))->register();

// Read the configuration
$config = new ConfigIni(__DIR__ . '/config/config.ini');

// Start Micro
$app = new Micro();

//Setup the database service
$app['db'] = function() use ($config) {
    return new MysqlAdapter(array(
        "host" => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname" => $config->database->dbname
    ));
};

/**
 * Application
 */

$app->get('/', function () {
});

$app->get('/notes' , function() use ($app) { 
    $notes = new Notes();
    echo json_encode($notes->getAll());
});

$app->get('/notes/{id}', function ($id) use ($app) {
    $notes = new Notes();
    $note = $notes->getSingle($id);
    if ($note != false) {
        echo json_encode($note);        
    } else {
        $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    }
});

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
});

$app->handle();