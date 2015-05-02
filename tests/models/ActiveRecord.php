<?php

namespace lvl\staticactiverecord\unit\models;

class ActiveRecord extends \lvl\staticactiverecord\db\ActiveRecord
{
    public static $db;

    public static function getDb()
    {
        return self::$db;
    }
    
    public function attributes()
    {
        $attributes = parent::attributes();

        return $attributes;
    }
}
