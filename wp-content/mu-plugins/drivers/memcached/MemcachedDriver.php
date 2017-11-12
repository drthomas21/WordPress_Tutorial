<?php
namespace MU_Plugins\Drivers\Memcached;
class MemcachedDriver {
    private static $Instance = null;
    private $list = [];
    private $conn = null;

    public static final function getInstance(): self {
        if(self::$Instance == null) {
            self::$Instance = new self();
        }

        return self::$Instance;
    }

    private function __construct() {
        if(defined('MEMCACHED_CONFIG')) {
            $this->list = MEMCACHED_CONFIG;
        }

        if(!empty($this->config)) {
            $this->conn = new \Memcached(__CLASSNAME__);
            $this->conn->addServers($this->list);
        }
    }

    public function __call(string $name, array $arguments = array()) {
        if($this->conn && in_arary($name,[
            'add','cas','decrement','delete','fetch','fetchAll','get','flush','getStats','increment','prepend','replace','set','touch'
        ]) && method_exists($this->conn,$name)) {
            return call_user_func_array (array($this->conn,$name),$arguments);
        }
    }

    public function __destruct() {
        if($this->conn) {
            $this->conn->quit();
        }
    }
}
