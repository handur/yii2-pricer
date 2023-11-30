<?php


namespace app\modules\pricer\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

use app\modules\pricer\models\Shipper;

class ShippersController extends Controller
{
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
        $shippers = Shipper::find()->all();

        return $this->render('ShippersList',['shippers'=>$shippers]);
    }
    public function actionDelete($id)
    {
        Shipper::findOne($id)->delete();

        Yii::$app->session->setFlash(
                'success',
                'Запись удалена'
        );
        return $this->redirect('/pricer/shippers/list');
    }

    public function processEdit($op=''){
        $request = Yii::$app->request;
        if($op=='edit'){
            $id=$request->get('id');
            $model = Shipper::findOne($id);
        } else {
            $model = new Shipper();
        }
        if($model->load(Yii::$app->request->post())){
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
        return $this->render('ShipperForm', ['op'=>$op,'model' => $model]);
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