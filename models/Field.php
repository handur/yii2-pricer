<?php

namespace app\modules\pricer\models;

use Yii;

/**
 * This is the model class for table "pricer_fields".
 *
 * @property int $id
 * @property string|null $key
 * @property string|null $name
 * @property resource|null $settings
 */
class Field extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pricer_field';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['settings'], 'string'],
            [['key'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'name' => 'Name',
            'settings' => 'Settings',
        ];
    }
    public function getFieldInstances(){
        return $this->hasMany(FieldInstance::class,['field_id'=>'id']);
    }
    public static function getListByType(int $type){
        $fields=Field::find()->joinWith('fieldInstances')->where(['type_id'=>$type])->select(['name','key'])->indexBy('key')->column();
        return $fields;
    }
}
