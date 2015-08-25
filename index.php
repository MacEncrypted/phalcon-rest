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

/**
 * Application
 */

$core = new MicroCollection();

// Set the main handler & prefix
$core->setHandler(new CoreController($app));
$core->setPrefix('/');

// core methods
$core->get('/', 'index');

$app->mount($core);

$messages = new MicroCollection();

// Set the main handler & prefix
$messages->setHandler(new MessagesController($app));
$messages->setPrefix('/messages');

// Messages methods
$messages->post('/', 'index');
$messages->get('/{id_sender}/{id_receiver}', 'stream');

$app->mount($messages);

$app->put('/messages/{id}', function($id) use ($app) {
    $msg = Messages::findFirst($id);
    if ($msg && ($app['auth']['id'] == $msg->getIdSender())) {
        $jarray = $app->request->getJsonRawBody();
    
        if ($jarray != NULL && ($jarray->id_sender && $jarray->id_receiver && $jarray->content)) {
            echo 'jest tablica i dobry user - aktualizuj';
                
                //message = $msg->updateSingle($jarray->id_sender, $jarray->id_receiver, $jarray->content);
                //$app->response->setStatusCode(201, "Created");
                //$app->response->setJsonContent(array('status' => 'OK', 'data' => $message));             
        } else {
            $app->response->setStatusCode(400, "Bad Request");
            $app->response->setJsonContent(array('status' => 'ERROR', 'data' => 'wrong JSON input'));
       }
    } else {
        $app->response->setStatusCode(401, 'Unauthorized');
        $app->response->setJsonContent(array('status' => 'ERROR', 'data' => 'Access is not authorized'));
    }   
  $app->response->setContentType('application/json', 'utf-8');
   return $app->response;
});

$users = new MicroCollection();

// Set the main handler. ie. a controller instance
$users->setHandler(new UsersController($app));

// Set a common prefix for all routes
$users->setPrefix('/users');

// Use the method 'index' in PostsController
$users->post('/', 'create');
$users->put('/{id}', 'update');
$users->delete('/{id}', 'delete');
$users->get('/', 'preview');
$users->get('/{id}', 'info');

// Use the method 'show' in PostsController
//$posts->get('/show/{slug}', 'show');

$app->mount($users);



$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    var_dump($_REQUEST);
});

$app->handle();