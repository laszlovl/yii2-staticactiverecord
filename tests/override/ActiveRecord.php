<?php

namespace yiiunit\data\ar;

/**
 * This class, combined with the classloader override in bootstrap.php, makes all instances
 * of yiiunit\data\ar\ActiveRecord extend from this extension's ActiveRecord version instead
 * of the original yii\db\ActiveRecord.
 *
 * This allows us to run the entire Yii ActiveRecord testsuite without overriding each AR class.
 */
class ActiveRecord extends \lvl\staticactiverecord\db\ActiveRecord
{
    public static $db;

    public static function getDb()
    {
        return self::$db;
    }
}
