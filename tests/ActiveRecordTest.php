<?php

namespace lvl\staticactiverecord\unit;

class ActiveRecordTest extends \yiiunit\framework\db\ActiveRecordTest
{
    protected function setUp()
    {
        static::$params = require(__DIR__ . '/data/config.php');
        parent::setUp();
    }
}
