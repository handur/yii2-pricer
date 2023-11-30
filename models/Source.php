<?php

namespace app\modules\pricer\models;

use Yii;

/**
 * This is the model class for table "sources".
 *
 * @property int $id
 * @property int|null $price_id
 * @property string|null $row
 */
class Source extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sources';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price_id'], 'integer'],
            [['row'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price_id' => 'Price ID',
            'row' => 'Row',
        ];
    }
    public function getRow(){
        if(is_array($this->row)) return $this->row;
        else return unserialize($this->row);
    }
}
