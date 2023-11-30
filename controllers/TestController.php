<?php


namespace app\modules\pricer\controllers;


use app\modules\pricer\core\Loaders\LoadMethodFactory;
use app\modules\pricer\core\Log\Logger;
use app\modules\pricer\core\Parse\ParseController;
use app\modules\pricer\core\Readers\ReadMethodFactory;
use Yii;
use yii\console\widgets\Table;
use yii\web\Controller;
use yii\web\HttpException;
use app\modules\pricer\models\Price;


class TestController extends Controller
{
    public function processTest($mode){
        if(!empty($mode)){
            $this->layout = false;
            $post=Yii::$app->request->post();
            $price = new Price();
            $price->load($post);
            if(!empty($post['Price'])){
                if(!empty($post['Price']['id'])){
                    $price=Price::findOne($post['Price']['id']);
                } else {
                    $price = new Price();
                    $price->load($post);
                }
                $price->settings=$post['Price']['settings'];

                if($mode=='load') {
                    $testMessage='LoadTestMessage';
                    $options=['ignoreExpire'=>TRUE];
                    $result = $price->uploadFile($options);
                } elseif($mode=='read'){
                    $testMessage='ReadTestMessage';
                    $options=['limitRow'=>20];
                    $result=$price->readFile($options);
                }
                if (!empty($result['error'])) {
                    #return $this->render($testMessage, ['result' => $result['error']]);
                    throw new HttpException(400, implode("\n", $result['error']));
                } else {
                    return $this->render($testMessage, ['result' => $result]);
                }
            }
        }
    }
    public function actionLoad(){
        return $this->processTest('load');
    }
    public function actionRead(){
        return $this->processTest('read');
    }
    public function actionIndex(){
        $output="<h1>TEST</h1>";

        $options=[
            'load'=>[
                    #'url'=>'https://b2b.4tochki.ru/export_data/M26016.xml',
                    'url'=>"C:\\ospanel\\domains\\yii2\\app\\uploads\\1d285005d26323e2c84bb348e8e40e82.raw",
                    'isLocalFile'=>TRUE,
                    'uploadDir'=>'uploads',
                    'uniqueName'=>'4tochki',

                ],
            'read'=>[
              'xquery_product'=>'/data/tires',
              'params_product'=>'tags',
              'limitRow'=>10,

            ],
        ];
        $load_method_object=LoadMethodFactory::create('url',$options['load']);
        $load_result = $load_method_object->load();

        $read_method_object=ReadMethodFactory::create('xml',$options['read']);
        $read_result = $read_method_object->read($load_result,$options['read']);
/*
        $dataSet=[
            'cae'=>['field'=>'{source:cae}'],
            'brand'=>['field'=>'{source:name}','rules'=>['findStr'=>['list'=>['Goodyear','Hankook','Matador']]]],
            'full_razmer'=>['field'=>'{source:name}','rules'=>['findRegexp'=>['pattern'=>'/[0-9^\/]+[\/[0-9^\s]+]*R[0-9]+/','delta'=>0]]],
            'index'=>['field'=>'{source:name}','rules'=>['findRegexp'=>['pattern'=>'/[0-9]+[A-Z]{1}/','delta'=>0]]],
            'runflat'=>['field'=>'{source:name}','rules'=>['findStr'=>['list'=>['Run Flat']]]],
            'model'=>['field'=>'{source:name}','rules'=>['excludeStr'=>['list'=>['{parsed:brand}','{parsed:full_razmer}','{parsed:index}','{parsed:runflat}']]]],
            'articul'=>['field'=>'{parsed:brand} {parsed:cae}'],
        ];
*/
        $dataSet=[
            'test'=>['field'=>'Hello World!'],
            'cae'=>['field'=>'{source:cae}'],
            'brand'=>['field'=>'{source:brand}'],
            'model'=>['field'=>'{source:model}'],
            'width'=>['field'=>'{source:width}'],
            'diameter'=>['field'=>'{source:diameter}','rules'=>['excludeStr'=>['list'=>['R']]]],
            'height'=>['field'=>'{source:height}'],
            'full_razmer'=>['field'=>'{parsed:width}/{parsed:height} R{parsed:diameter}'],
            'load_index'=>['field'=>'{source:load_index}'],
            'speed_index'=>['field'=>'{source:speed_index}'],
            'season'=>['field'=>'{source:season}'],
            'image'=>['field'=>'{source:img_big_my}'],
            'name'=>['field'=>'{parsed:brand} {parsed:model} {parsed:full_razmer}'],
            'row_key'=>['field'=>'{parsed:name}'],
        ];
        $parseController=new ParseController($dataSet);
        $parse_result=[];
        foreach($read_result as $row){
            $parse_row=$parseController->parseRow($row);
            $parse_result[]=$parse_row;
        }
        $output=Logger::getLog(true);
        return $output;
    }
}