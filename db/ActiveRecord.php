<?php

namespace lvl\staticactiverecord\db;

class ActiveRecord extends \yii\db\ActiveRecord
{
    private static $getTableSchemaCache;
    private static $primaryKeyCache;
    private static $attributesCache;
    private static $hasAttributeCache;
    private static $scenariosCache;
    private static $formNameCache;

    public static function getTableSchema()
    {
        $class = get_called_class();

        if (!isset(self::$getTableSchemaCache[$class])) {
            self::$getTableSchemaCache[$class] = parent::getTableSchema();
        }

        return self::$getTableSchemaCache[$class];
    }

    public static function primaryKey()
    {
        $class = get_called_class();

        if (!isset(self::$primaryKeyCache[$class])) {
            self::$primaryKeyCache[$class] = parent::primaryKey();
        }

        return self::$primaryKeyCache[$class];
    }

    public function attributes()
    {
        $class = get_class($this);

        if (!isset(self::$attributesCache[$class])) {
            self::$attributesCache[$class] = parent::attributes();
        }

        return self::$attributesCache[$class];
    }

    public function hasAttribute($name)
    {
        $class = get_class($this);

        if (!isset(self::$hasAttributeCache[$class][$name])) {
            self::$hasAttributeCache[$class][$name] = parent::hasAttribute($name);
        }

        return self::$hasAttributeCache[$class][$name];
    }

    public function scenarios()
    {
        $class = get_class($this);

        if (!isset(self::$scenariosCache[$class])) {
            self::$scenariosCache[$class] = parent::scenarios();
        }

        return self::$scenariosCache[$class];
    }

    public function formName()
    {
        $class = get_class($this);

        if (!isset(self::$formNameCache[$class])) {
            self::$formNameCache[$class] = parent::formName();
        }

        return self::$formNameCache[$class];
    }
}
