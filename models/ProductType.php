<?php

namespace app\modules\pricer\models;

use Yii;

/**
 * This is the model class for table "pricer_types".
 *
 * @property int $id
 * @property string|null $name
 * @property resource|null $settings
 */
class ProductType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pricer_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['settings'], 'string'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'settings' => 'Settings',
        ];
    }
    public static function getList(){
        return ProductType::find()->select(['name','id'])->indexBy('id')->column();
    }
}
