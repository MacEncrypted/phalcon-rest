<?php

class CoreController extends Phalcon\DI\Injectable {

    private $app;

    public function __construct($app)
    {
        $this->app = $app;   
        $this->app->response->setContentType('application/json', 'utf-8');
    }

    public function index()
    {
        $info = [];
        $info['app'] = 'REST Api @Phalcon ' . Phalcon\Version::get();
        $info['sha1(password)'] = sha1('password');
        $info['sha1(daily password)'] = sha1(sha1('password') . date('Y-m-d'));
        $this->app->response->setJsonContent($info);
        return $this->app->response;
    }

}
