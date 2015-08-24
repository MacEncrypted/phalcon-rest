<?php

class MessagesController extends Phalcon\DI\Injectable {

    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * POST message to server
     * 
     * @return type
     */
    public function index()
    {
        $jarray = $this->app->request->getJsonRawBody();

        if (($jarray != NULL) && ($jarray->id_sender && $jarray->id_receiver && $jarray->content)) {
            $msg = new Messages();
            $message = $msg->setSingle($jarray->id_sender, $jarray->id_receiver, $jarray->content);
            $this->app->response->setStatusCode(201, "Created");
            $this->app->response->setJsonContent(array('status' => 'OK', 'data' => $message));
        } else {
            $this->app->response->setStatusCode(400, "Bad Request");
            $this->app->response->setJsonContent(array('status' => 'ERROR', 'data' => 'wrong JSON input'));
        }

        $this->app->response->setContentType('application/json', 'utf-8');
        return $this->app->response;
    }

    public function stream($id_sender, $id_receiver)
    {
        $msg = new Messages();
        $this->app->response->setContentType('application/json', 'utf-8');
        $this->app->response->setJsonContent($msg->getFlow($id_sender, $id_receiver));
        return $this->app->response;
    }

}
