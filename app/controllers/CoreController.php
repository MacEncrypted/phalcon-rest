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
        $keys = new Keys();
        $keys->rebuildKeyStack(240);
        $info = [];
        $info['app'] = 'REST Api @Phalcon ' . Phalcon\Version::get();        
        $info['sha1(password)'] = sha1('password');
        $info['keys'] = $this->getKeys(); 
        $info['passwords'] = $this->getPasswords($keys);
        
        
        $this->app->response->setJsonContent($info);
        return $this->app->response;
    }
    
    private function getPasswords($keys)
    {
        $passwords = [];
        foreach ($this->getKeys() as $key) {
            $password = [];
            $password['sha1(sha1(password) + key)'] = sha1(sha1('password') . $key['key']);
            $password['lifetime'] = $key['lifetime']; 
            $passwords[] = $password;
        }
        return $passwords;
    }
    
    private function getKeys()
    {    
        $keys = new Keys(); 
        return $keys->getValidList();
    }

}
