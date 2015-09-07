<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;

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
     * @var string
     */
    protected $pubkey;

    public function validation()
    {
        $this->validate(new Uniqueness(array(
            'field' => 'login'
        )));
        if ($this->validationHasFailed() == true) {
            return false;
        } 
        return true;
    }

    public function setUser($login, $password, $pubkey)
    {
        $this->login = $login;
        $this->password = $password;
        $this->pubkey = $pubkey;
        if ($this->validation()) {
            $this->save();
        }

        return $this->id;
    }

    public function updateUser($login, $password, $pubkey, $old_password)
    {
        $secret_old_password = $this->password;        
        $this->login = $login;
        $this->password = $password;
        $this->pubkey = $pubkey;
        if ($this->validation() && ($secret_old_password == $old_password)) {
            $this->save();
        } else {
            return null;
        }

        return $this->id;
    }

    public function getUser()
    {
        $return_user = [];
        $return_user['id'] = $this->id;
        $return_user['login'] = $this->login;
        $return_user['pubkey'] = $this->pubkey;
        return $return_user;
    }

    public function getList()
    {
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
    public function getUserId($login, $password)
    {
        $user = $this->findFirst("login = '" . $login . "'");

        if ($user != false) {
            $rhash = sha1($user->password . date('Y-m-d'));
            if ($rhash == $password) {
                return $user->id;
            }
        }

        return null;
    }

}
