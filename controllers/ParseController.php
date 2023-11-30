<?php


namespace app\modules\pricer\controllers;


use yii\web\Controller;
use app\modules\pricer\core\Parse\ParseRule;
use app\modules\pricer\core\Parse\ParseToken;

class ParseController extends Controller
{
    private array $dataSet=[];

    public function setDataSet($dataSet){
        $this->dataSet=$dataSet;
    }

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

    }

    public function actionTest(){
        $message="TEST";
        $dataRow=[
            'cae'=>'12345',
            'name'=>'225/45 R18 Goodyear Eagle F1 Asymmetric 3 91Y Run Flat'
        ];
     #   $result=$this->parseRow($dataRow);
        return $message;
    }


}