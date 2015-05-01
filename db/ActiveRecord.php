<?php

namespace lvl\staticactiverecord\db;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public static function getTableSchema()
    {
        static $tableSchema = null;

        if ($tableSchema === null) {
            $tableSchema = parent::getTableSchema();
        }

        return $tableSchema;
    }

    public static function primaryKey()
    {
        static $primaryKey = null;

        if ($primaryKey === null) {
            $primaryKey = parent::primaryKey();
        }

        return $primaryKey;
    }

    final public function attributes()
    {
        static $attributes = null;

        if ($attributes === null) {
            $attributes = parent::attributes();
        }

        return $attributes;
    }

    public function hasAttribute($name)
    {
        static $attributes = [];

        if (!isset($attributes[$name])) {
            $attributes[$name] = parent::hasAttribute($name);
        }

        return $attributes[$name];
    }

    public function scenarios()
    {
        static $scenarios = null;

        if ($scenarios === null) {
            $scenarios = parent::scenarios();
        }

        return $scenarios;
    }
}
