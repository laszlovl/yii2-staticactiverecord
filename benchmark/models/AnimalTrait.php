<?php

namespace app\models;

trait AnimalTrait {
    public static function tableName()
    {
        return 'animal';
    }

    public function rules()
    {
        return [
            ['name', 'string']
        ];
    }

    public function getSelf()
    {
        return $this->hasOne(static::class, ['id' => 'id']);
    }
}