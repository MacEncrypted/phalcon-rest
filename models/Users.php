<?php

use Phalcon\Mvc\Model;

class Users extends Model {

    /**
     * @var integer
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $login;
    
    /**
     * @var string
     */
    protected $password;

    /**
     * Get all messages from sender to receiver
     * @param type $id_sender
     * @param type $id_receiver
     * @return type
     */
    public function getList() {
        $return_users = array();    
        foreach ($this->find() as $user) {
            $return_user = array();
            $return_user['id'] = $user->id;
            $return_user['login'] = $user->login;
            $return_users[] = $return_user;
        }
        return $return_users;
    }  
    
    public function getUserId($login, $password, $key) {
        $psha = sha1($key . $password);
        
        $user = $this->findFirst("login = '" . $login . "' AND password = '" . $psha . "'");
        
        if ($user!= false) {
            return $user->id;
        } 
        
        return 0;
    }
}
