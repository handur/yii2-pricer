<?php

namespace app\modules\pricer;

/**
 * pricer module definition class
 */
class Pricer extends \yii\base\Module
{
    public $params=[
      'uploaddir'=>'uploads',
      'Loaders'=>  [
          'url'=>'По прямой ссылке','email'=>'По электронной почте','ftp'=>'Через FTP'
      ],
      'Readers'=>[
          'xml'=>'XML-парсинг','csv'=>'CSV-парсинг','XLS'=>'Excel'
      ]
    ];
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\pricer\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }
}
