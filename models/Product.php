<?php

namespace app\modules\pricer\models;

use Yii;

/**
 * This is the model class for table "pricer_products".
 *
 * @property int $id
 * @property int|null $type
 * @property string|null $name
 * @property string|null $created
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pricer_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'integer'],
            [['created'], 'safe'],
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
            'type' => 'Type',
            'name' => 'Name',
            'created' => 'Created',
        ];
    }
}
