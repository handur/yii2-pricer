<?php


namespace app\modules\pricer\controllers;


use app\modules\pricer\models\Field;
use app\modules\pricer\models\FieldInstance;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\modules\pricer\models\Shipper;
use app\modules\pricer\models\Price;
use app\modules\pricer\models\ProductType;

class PriceController extends Controller
{
    public array $loadMethodList;
    public array $readMethodList;
    public array $shipperList;
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['list','add','edit','delete'],
                'rules' => [
                    [
                        'actions' => ['list','add','edit','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }
    public function actionList()
    {
        $prices = Price::find()->all();
        return $this->render('PriceList',['prices'=>$prices]);
    }
    public function actionDelete($id)
    {
        Price::findOne($id)->delete();

        Yii::$app->session->setFlash(
            'success',
            'Запись удалена'
        );
        return $this->redirect('/pricer/price/list');
    }
    public function processEdit($op='add'){
        $loadMethodList=Yii::$app->getModule('pricer')->params['Loaders'];
        $readMethodList=Yii::$app->getModule('pricer')->params['Readers'];
        $productTypes=ProductType::find()->select(['name', 'id'])->indexBy('id')->column();
        $shippersList=Shipper::find()->select(['name', 'id'])->indexBy('id')->column();

        $request = Yii::$app->request;
        if($id=$request->get('id')){
            $model = Price::findOne($id);
        #    $model['settings']=unserialize($model['settings']);
            #$model['fields']=unserialize($model['fields']);
        } else {
            $model = new Price();
        }

        if($shipperId=$request->get('shipper_id')){
            $model['shipper_id']=$shipperId;
        }

        if($post = Yii::$app->request->post()) {
            $settings=$post['Price']['settings'];
            /* Подчищаем ненужные настройки для других методов загрузки */
            $currentLoadMethod=$settings['load_method'];
            $currentLoadMethodSettings=$settings['load_method_settings'][$currentLoadMethod.'_method'];
            unset($settings['load_method_settings']);
            $settings['load_method_settings'][$currentLoadMethod.'_method']=$currentLoadMethodSettings;

            $currentReadMethod=$settings['read_method'];
            $currentReadMethodSettings=$settings['read_method_settings'][$currentReadMethod.'_method'];
            unset($settings['read_method_settings']);
            $settings['read_method_settings'][$currentReadMethod.'_method']=$currentReadMethodSettings;
            $post['Price']['settings'] = serialize($settings);

            $post['Price']['fields'] = serialize($post['Price']['fields']);

            if($model->load($post)){
                if($model->save()){
                    Yii::$app->session->setFlash(
                        'success',
                        'Данные формы записаны в базу данных'
                    );
                    return $this->refresh();
                } else {
                    Yii::$app->session->setFlash(
                        'error',
                        'Данные формы не прошли валидацию'
                    );
                }
            }
        }

        $fieldInstances=Field::getListByType($model->product_type);
        if(!empty($model->fields['custom_fields'])) {
            $customFields=$model->fields['custom_fields'];
        } else $customFields=[];
        $formOptions=[
            'op'=>$op,
            'model' => $model,
            'shippersList'=>$shippersList,
            'loadMethodList'=>$loadMethodList,
            'readMethodList'=>$readMethodList,
            'productTypes'=>$productTypes,
            'fieldInstances'=>$fieldInstances,
            'customFields'=>$customFields,
        ];

        return $this->render('PriceForm', $formOptions);
    }
    public function actionGetProductFields($op='refresh'){
        $request=Yii::$app->request;
        if($request->isAjax){
            $model = new Price();
            $model->load($request->post());
            $fieldInstances=Field::getListByType($model->product_type);
            $customFields=[];
            if(!empty($model->fields['custom_fields'])) {
                $customFields=$model->fields['custom_fields'];
                $cf_count = count($customFields);
            } else {
                $cf_count=0;
            }
            if($op=='add'){
                $cf_count++;
            }
            for($n=0;$n<$cf_count;$n++){
                if(empty($customFields['cf_'.$n])) {
                    $customFields['cf_' . $n] = [
                        'name' => '',
                        'field' => '',
                        'rule' => '',
                    ];
                }
            }
            if($op=='delete'){
                $field_key=$request->get('field_key');
                unset($customFields[$field_key]);
            }
            $formOptions=[
                'model'=>$model,
                'fieldInstances'=>$fieldInstances,
                'customFields'=>$customFields,
            ];
            return $this->renderPartial('PriceFormFields',$formOptions);
        }

    }
    public function actionAddProductFields(){
        return $this->actionGetProductFields('add');
    }
    public function actionDeleteProductField(){
        return $this->actionGetProductFields('delete');
    }
    public function actionEditRules()
    {
        $model = new Price();
        return $this->renderAjax('PriceFormFieldRules', [
            'model' => $model,
        ]);
    }
    public function actionAdd()
    {
        return $this->processEdit('add');
    }

    public function actionEdit()
    {
        return $this->processEdit('edit');
    }

}