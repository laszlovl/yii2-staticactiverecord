<?php

namespace lvl\staticactiverecord\unit;

use lvl\staticactiverecord\unit\models\ActiveRecord;
use lvl\staticactiverecord\unit\models\Animal;
use lvl\staticactiverecord\unit\models\Customer;

class InheritanceTest extends \yiiunit\framework\db\DatabaseTestCase
{
    protected function setUp()
    {
        static::$params = require(__DIR__ . '/data/config.php');
        parent::setUp();
        ActiveRecord::$db = $this->getConnection();
    }

    public function testInheritance()
    {
        $animal = new Animal;
        $customer = new Customer;

        $animalAttr = $animal->attributes();
        $customerAttr = $customer->attributes();

        $this->assertNotEquals($animalAttr, $customerAttr, 'Animal and Customer should have a different set of attributes');
    }
}
