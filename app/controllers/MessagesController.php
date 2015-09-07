<?php

class MessagesController extends Phalcon\DI\Injectable {

    private $app;

    public function __construct($app)
    {
        $this->app = $app;
        $this->app->response->setContentType('application/json', 'utf-8');
    }

    /**
     * Create new message
     * 
     * @return Response
     */
    public function create()
    {
        if (!empty($this->app['auth']['id'])) {
            $jarray = $this->app->request->getJsonRawBody();

            if (($jarray != NULL) && ($jarray->id_receiver && $jarray->content && $jarray->type)) {
                $msg = new Messages();
                $message = $msg->setSingle($this->app['auth']['id'], $jarray->id_receiver, $jarray->content, $jarray->type);
                $this->app->response
                        ->setStatusCode(201, "Created")
                        ->setJsonContent(array('status' => 'OK', 'data' => $message));
            } else {
                $this->app->response
                        ->setStatusCode(400, "Bad Request")
                        ->setJsonContent(array('status' => 'ERROR', 'data' => 'wrong JSON input'));
            }
        } else {
            $this->app->response
                    ->setStatusCode(401, "Unauthorized")
                    ->setJsonContent(array('status' => 'ERROR', 'data' => 'Access is not authorized'));
        }

        return $this->app->response;
    }

    /**
     * List messages sent from one user to another
     * 
     * @param integer $id_sender
     * @param integer $id_receiver
     * @return Response
     */
    public function stream($id_sender, $id_receiver)
    {
        if (!empty($this->app['auth']['id']) && ($this->app['auth']['id'] == $id_sender || $this->app['auth']['id'] == $id_receiver)) {
            $msg = new Messages();
            $offset = $this->app->request->getQuery("offset");
            $limit = $this->app->request->getQuery("limit");
            $this->app->response->setJsonContent($msg->getFlow($id_sender, $id_receiver, $offset, $limit));
            return $this->app->response;
        } else {
            $this->app->response
                    ->setStatusCode(401, "Unauthorized")
                    ->setJsonContent(array('status' => 'ERROR', 'data' => 'Access is not authorized'));
        }
        return $this->app->response;
    }

    /**
     * List latest messages
     * 
     * @return Response
     */
    public function inbox()
    {
        if (!empty($this->app['auth']['id'])) {
            $msg = new Messages();
            $offset = $this->app->request->getQuery("offset");
            $limit = $this->app->request->getQuery("limit");
            $this->app->response->setJsonContent($msg->getInbox($this->app['auth']['id'], $offset, $limit));
            return $this->app->response;
        } else {
            $this->app->response
                    ->setStatusCode(401, "Unauthorized")
                    ->setJsonContent(array('status' => 'ERROR', 'data' => 'Access is not authorized'));
        }
        return $this->app->response;
    }
}
