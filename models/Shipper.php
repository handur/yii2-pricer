<?php

namespace app\modules\pricer\models;

use Yii;
use yii\validators\Validator;

/**
 * This is the model class for table "pricer_shipper".
 *
 * @property int $id
 * @property string|null $machine_key
 * @property string|null $name
 * @property int|null $active
 * @property int $weight
 */
class Shipper extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pricer_shipper';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active','weight'], 'integer'],
            [['machine_key'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['machine_key','name'],'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'machine_key' => 'Machine Key',
            'name' => 'Name',
            'active' => 'Active',
            'weight' => 'Weight',
        ];
    }
    public function getPrices(){
        return $this->hasMany(Price::class,['shipper_id'=>'id']);
    }
}
