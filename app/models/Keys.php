<?php

use Phalcon\Mvc\Model;

class Keys extends Model {

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $time;

    /**
     * @var integer
     */
    protected $valid;
    
    /**
     * @var string
     */
    protected $key;
    
    public function rebuildKeyStack($lifetime)
    {
        $key = Keys::findFirst([
            "valid = '1'",
            "order" => "time ASC"
        ]);
                
        if (!empty($key) && ($key->time < time())) {
            $key->valid = 0;
            $key->save();
        } 
        
        if(count($this->getValidList()) < 2) {
            $this->generateNewKey($lifetime);
        }
    }
    
    protected function generateNewKey($lifetime)
    {
        $k = new Keys();
            $k->time = time() + $lifetime;
            $k->valid = 1;
            $k->key = sha1(rand() . time());
            $k->save();
    }
    
    public function getValidList()
    {
        $keys = Keys::find([
            "valid = '1'",
            "order" => "time DESC"
        ]);
        
        $vKeys = [];
        
        foreach ($keys as $key) {
            $vKey = [];
            $vKey['key'] = $key->key;
            $vKey['lifetime'] = $key->time - time();
            $vKeys[] = $vKey;
        }
        
        return $vKeys;
    }

}
