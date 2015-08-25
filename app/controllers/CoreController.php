<?php

class CoreController extends Phalcon\DI\Injectable {

    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function index()
    {
            echo 'REST Api @Phalcon ' . Phalcon\Version::get();
        echo '<br/>';
        echo 'sha1(password) = ' . sha1('password');
        echo '<br/>';
        echo 'sha1(sha1(password) + Y-m-d) = ' .sha1(sha1('password') . date('Y-m-d'));
    }

}
