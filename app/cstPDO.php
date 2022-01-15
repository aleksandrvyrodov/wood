<?php

namespace App\DBase;

use App\DBase;

require_once __DIR__ . '/dbase.php';


final class cstPDO extends DBase
{
    private $PDO;

    private $opt = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_CASE => \PDO::CASE_NATURAL,
        \PDO::ATTR_ORACLE_NULLS => \PDO::NULL_EMPTY_STRING,
        \PDO::ATTR_STRINGIFY_FETCHES => false,
        \PDO::ATTR_EMULATE_PREPARES => false,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
    ];

    protected function __construct()
    {
        try {
            $this->PDO = new \PDO(
                "mysql:host={$this->host};dbname={$this->name};charset=UTF8",
                $this->user,
                $this->pass,
                $this->opt
            );
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            exit();
        }
    }

    public function getNaturalConnect()
    {
        return $this->PDO;
    }
}
