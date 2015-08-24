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
    
    /**
     * Password sent by user must be:
     * sha1(saved_hash + Y-m-d)
     * 
     * @param type $login
     * @param type $password
     * @return int
     */
    public function getUserId($login, $password) {
        $user = $this->findFirst("login = '" . $login . "'");
        
        if ($user!= false) {
            $rhash = sha1($user->password . date('Y-m-d'));
            if ($rhash == $password) {
                return $user->id;
            }
        } 
        
        return 0;
    }
}
