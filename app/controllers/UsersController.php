<?php

class UsersController extends Phalcon\DI\Injectable {

    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function index()
    {
        if (($this->app['auth']['id'] != 0)) {
            $usr = new Users();
            $this->app->response->setContentType('application/json', 'utf-8');
            $this->app->response->setJsonContent($usr->getList());
        } else {
            $this->app->response->setStatusCode(401, "Unauthorized");
            $this->app->response->setContent("Access is not authorized");
        }
        return $this->app->response;
    }

}
