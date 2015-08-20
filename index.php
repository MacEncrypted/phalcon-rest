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

$app->get('/', function () {
    echo 'REST Api @Phalcon ' . Phalcon\Version::get();
    echo '<br/>';
    echo 'sha1(password) = ' . sha1('password');
    echo '<br/>';
    echo 'sha1(sha1(password) + Y-m-d) = ' .sha1(sha1('password') . date('Y-m-d'));
});

$app->get('/messages/{id_sender}/{id_receiver}', function ($id_sender, $id_receiver) use ($app) {
    $msg = new Messages();
    $app->response->setContentType('application/json', 'utf-8');
    $app->response->setJsonContent($msg->getFlow($id_sender, $id_receiver));
    return $app->response;
});

$app->get('/conversations/{id_one}/{id_two}', function ($id_one, $id_two) use ($app) {
    $msg = new Messages();
    $app->response->setContentType('application/json', 'utf-8');
    $app->response->setJsonContent($msg->getConversation($id_one, $id_two));
    return $app->response;
});

$app->post('/messages', function () use ($app) {
    $jarray = $app->request->getJsonRawBody();
    
    if ($jarray != NULL) {
        if ($jarray->id_sender && $jarray->id_receiver && $jarray->content) {
            $msg = new Messages();
            $message = $msg->setSingle($jarray->id_sender, $jarray->id_receiver, $jarray->content);
            $app->response->setStatusCode(201, "Created");
            $app->response->setJsonContent(array('status' => 'OK', 'data' => $message));             
        } else {
            $app->response->setStatusCode(400, "Bad Request");
            $app->response->setJsonContent(array('status' => 'ERROR', 'data' => 'missing :id_sender: or :id_receiver: or :content:'));
        }
    } else {
        $app->response->setStatusCode(400, "Bad Request");
        $app->response->setJsonContent(array('status' => 'ERROR', 'data' => 'wrong JSON input'));
   }
   
   $app->response->setContentType('application/json', 'utf-8');
   return $app->response;
});

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

$app->get('/users', function () use ($app) {        
    if (($app['auth']['id'] != 0)) {
        $usr = new Users();
        $app->response->setContentType('application/json', 'utf-8');
        $app->response->setJsonContent($usr->getList());
    } else {
        $app->response->setStatusCode(401, "Unauthorized");
        $app->response->setContent("Access is not authorized");
    }
    return $app->response;    
});

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    var_dump($_REQUEST);
});

$app->handle();