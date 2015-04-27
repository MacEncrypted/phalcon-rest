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
    echo 'REST Api @Phalcon ' . Phalcon\Version::get();
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

$app->post('/notes', function () use ($app) {
    $jarray = $app->request->getJsonRawBody();
    
    if ($jarray != NULL) {
        if ($jarray->title && $jarray->text) {
            $notes = new Notes();
            $note = $notes->setSingle($jarray->title, $jarray->text);
            $app->response->setStatusCode(201, "Created");
            $app->response->setJsonContent(array('status' => 'OK', 'data' => $note));             
        } else {
            $app->response->setStatusCode(400, "Bad Request");
            $app->response->setJsonContent(array('status' => 'ERROR', 'data' => 'missing :title: or :text:'));
        }
    } else {
        $app->response->setStatusCode(400, "Bad Request");
        $app->response->setJsonContent(array('status' => 'ERROR', 'data' => 'wrong JSON input'));
   }
   
   $app->response->setContentType('application/json', 'utf-8');
   return $app->response;
});

/*
$app->put('/notes/{id}', function($id) use ($app) {
    $jarray = $app->request->getJsonRawBody();
    
    if ($jarray != NULL) {
        if ($jarray->id && $jarray->title && $jarray->text) {
            
        } else {
            $app->response->setStatusCode(400, "Bad Request");
            $app->response->setJsonContent(array('status' => 'ERROR', 'data' => 'missing :id: or :title: or :text:'));
        }
    } else {
        $app->response->setStatusCode(400, "Bad Request");
        $app->response->setJsonContent(array('status' => 'ERROR', 'data' => 'wrong JSON input'));        
    }
    return $app->response;
});
*/

$app->put('/test/{id}' , function($id) use ($app) { 
    $app->response->setJsonContent(array('status' => 'OK', 'data' => $id));
    return $app->response;
});

$app->delete('/test/{id}' , function($id) use ($app) { 
    $response = new Phalcon\Http\Response();
    $response->setJsonContent(array('status' => 'OK', 'data' => $id));
    return $response;
});

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
});

$app->handle();