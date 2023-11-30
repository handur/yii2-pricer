<?php

namespace app\modules\pricer\models;

use Yii;

/**
 * This is the model class for table "upload".
 *
 * @property int $id
 * @property string|null $file
 * @property string|null $created
 */
class Upload extends \yii\db\ActiveRecord

{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'upload';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'],'integer'],
            [['file'], 'string', 'max' => 255],
            [['size'],'integer'],
            [['created'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file' => 'File',
            'size' => 'Size',
            'created' => 'Created',
        ];
    }
    public function getFile(){
        return $this->file;
    }
}
