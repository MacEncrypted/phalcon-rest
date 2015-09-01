<?php

use Phalcon\Mvc\Micro as Micro;
use \Phalcon\Loader as Loader;
use Phalcon\Db\Adapter\Pdo\Mysql as MysqlAdapter;
use Phalcon\Config\Adapter\Ini as ConfigIni;
use Phalcon\Mvc\Micro\Collection as MicroCollection;

// Setup loader
$loader = new Loader();
$loader->registerDirs(array(
    __DIR__ . '/app/models/',    
    __DIR__ . '/app/controllers/'
))->register();

// Read the configuration
$config = new ConfigIni(__DIR__ . '/config/config.ini');

// Start Micro
$app = new Micro();

// Setup the database service
$app['db'] = function() use ($config) {
    return new MysqlAdapter(array(
        "host" => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname" => $config->database->dbname
    ));
};

// Include controllers
$app['controllers'] = function() {
    return [
        'core' => true,
        'users' => true,
        'messages' => true
    ];
};

// Authentication
$app['auth'] = function() use ($app, $config) {
    $auth = array();
    $authorization = $app->request->getHeader("AUTHORIZATION");
    if ($authorization) {
        $cut = str_replace('Basic ', '', $authorization);
        $creds = explode(':', base64_decode($cut));
        $auth['login'] = $creds[0];
        $auth['password'] = $creds[1];
    } else {
        $auth['login'] = null;
        $auth['password'] = null;
    }
    
    $usr = new Users();    
    $auth['id'] = $usr->getUserId($auth['login'], $auth['password']);
    
    return $auth;
};

// CoreController
if ($app['controllers']['core']) {
    $core = new MicroCollection();

    // Set the handler & prefix
    $core->setHandler(new CoreController($app));
    $core->setPrefix('/');

    // Set routers
    $core->get('/', 'index');

    $app->mount($core);
}

//  MessagesController
if ($app['controllers']['messages']) {
    $messages = new MicroCollection();

    // Set the handler & prefix
    $messages->setHandler(new MessagesController($app));
    $messages->setPrefix('/messages');

    // Set routers
    $messages->post('/', 'create');
    $messages->get('/{id_sender}/{id_receiver}', 'stream');
    $messages->get('/inbox', 'inbox');

    $app->mount($messages);
}

// UsersController
if ($app['controllers']['users']) {
    $users = new MicroCollection();

    // Set the handler & prefix
    $users->setHandler(new UsersController($app));
    $users->setPrefix('/users');

    // Set routers
    $users->post('/', 'create');
    $users->put('/{id}', 'update');
    $users->delete('/{id}', 'delete');
    $users->get('/', 'preview');
    $users->get('/{id}', 'info');

    $app->mount($users);
}

// Not Found
$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
});

$app->handle();