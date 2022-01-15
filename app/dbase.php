<?php

namespace App;

class DBase
{
    protected $host = 'localhost';
    protected $user = 'root';
    protected $pass = '';
    protected $name = 'wood';

    protected $main_table = 'object';

    private static $instances = [];

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function init()
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls]))
            self::$instances[$cls] = new static();

        return self::$instances[$cls];
    }

    public static function reload()
    {
        $cls = static::class;
        self::$instances[$cls] = new static();

        return self::$instances[$cls];
    }
}
