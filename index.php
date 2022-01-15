<?php

require_once __DIR__ . '/app/raw_wood.php';

use App\RawWood;

$RawWood = RawWood::typeQ(100);
xdebug_var_dump($RawWood->getRawData());
