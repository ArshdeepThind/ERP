<?php

namespace App\Libraries;


use Firebase;
use Firebase\FirebaseLib;

/**
 * Firebase component
 */
class FirebaseCommands {

    protected $_defaultConfig = [];
    protected $firebase = null;

    public function __construct() {

        $defaultToken = 'nmHRaKVbJHlr777jIhvQ2rZ189YEpSSvFocuCOr6';
        $defaultUrl   = 'https://weil-b213d.firebaseio.com/';
        
        $this->firebase = new FirebaseLib($defaultUrl, $defaultToken);
    }
    
    public function get($path) {
        $data = $this->firebase->get($path);
        if(!empty($data) && $data != 'null' && $data != null) {
            return json_decode($data, true);
        }
        return [];
    }

    public function set($path, $data) {
        $dd = $this->firebase->set($path, $data);
    }

    public function delete($path) {
        return $this->firebase->delete($path);
    }

    public function push($path, $data, $options = array())
    {
        $dd = $this->firebase->push($path, $data, $options = array());
    }
    
}
