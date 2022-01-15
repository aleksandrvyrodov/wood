<?php

namespace App;

require_once __DIR__ . '/cstPDO.php';

use App\DBase\cstPDO;

class RawWood
{

    private static $inst = NULL;

    private $DB;

    private static $VARIANT = 50;

    private $size = 20;
    private $max_dept = 5;

    public static function typeQ($variant = -1, $size = 0, $dept = 0)
    {
        if ($variant > -1)
            self::$VARIANT = $variant;

        $inst = new static($size, $dept);
        $inst->fill(1);
        return $inst;
    }

    public static function typeW($variant = -1, $size = 0, $dept = 0)
    {
        if ($variant > -1)
            self::$VARIANT = $variant;

        $inst = new static($size, $dept);
        $inst->fill(0);
        return $inst;
    }

    private function __construct($size, $dept)
    {
        (bool)$size && $this->size = $size;
        (bool)$dept && $this->dept = $dept;

        $DB = cstPDO::init();
        $PDO = $DB->getNaturalConnect();

        $PDO->exec('TRUNCATE TABLE `object`');

        $this->DB = $DB;
    }

    private function fill($mode = 1)
    {
        if (self::$VARIANT > 90 && $mode === 0) $mode = 1;

        $PDO = $this->DB->getNaturalConnect();
        $parent_by_level = [0, 1];

        for ($row = 1, $id = 1; $row <= $this->size; $id++) {

            $path = md5((string)time() . $id);

            if (self::variant() && ($id - 1)) {
                $parent = rand(1, $id - 1);
                $level = $this->getLevel($parent) + 1;

                if ($level > $this->max_dept)
                    [
                        $parent,
                        $level
                    ] = $parent_by_level;
                if ($mode === 1) $row++;
            } else {
                $parent = 0;
                $level = 1;
                $row++;
            }

            $PDO->exec(<<<SQL
                INSERT INTO `object`
                    (`id`, `parent`, `level`, `path`)
                VALUE
                    ($id, $parent, $level, '$path')
            SQL);
        }
    }

    public function getRawData($rand = false)
    {
        $rand = $rand ? "ORDER BY RAND()" : '';

        $PDO = $this->DB->getNaturalConnect();
        $PDOSt = $PDO->query("SELECT * FROM `object` $rand");
        return $PDOSt->fetchAll();
    }

    private function getLevel($id)
    {
        $PDO = $this->DB->getNaturalConnect();
        $PDOSt = $PDO->query("SELECT * FROM `object` WHERE `id` = $id");
        $data = $PDOSt->fetch();
        return $data['level'];
    }

    private static function variant()
    {
        $dig = rand(0, 100);

        if ($dig > self::$VARIANT) return false;
        else return true;
    }
}
