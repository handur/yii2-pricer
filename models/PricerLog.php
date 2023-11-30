<?php

namespace app\modules\pricer\models;

use Yii;

/**
 * This is the model class for table "pricer_log".
 *
 * @property int|null $id
 * @property int|null $session_id
 * @property string|null $type
 * @property string|null $event
 * @property string|null $message
 * @property string|null $data
 */
class PricerLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pricer_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message', 'data'], 'string'],
            [['type', 'event'], 'string', 'max' => 50],
            [['id'], 'unique'],
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
            'event' => 'Event',
            'message' => 'Message',
            'data' => 'Data',
        ];
    }

    static public function addLog(string $type = 'info', string $event='', string $message='', Array $data=[]){
        $data=['type'=>$type, 'event'=>$event, 'message'=>$message, 'data'=>serialize($data)];

        $log=new PricerLog();
        $log->attributes=$data;
        $log->save();
    }

}
