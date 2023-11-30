<?php

namespace app\modules\pricer\models;

use Yii;

/**
 * This is the model class for table "pricer_field_instance".
 *
 * @property int|null $field_id
 * @property int|null $type_id
 * @property resource|null $settings
 */
class FieldInstance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pricer_field_instance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['field_id', 'type_id'], 'integer'],
            [['settings'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'field_id' => 'Field ID',
            'type_id' => 'Type ID',
            'settings' => 'Settings',
        ];
    }
    public function getField()
    {
        return $this->hasOne(Field::class,['id'=>'field_id']);
    }
}
