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
}
