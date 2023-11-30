<?php


namespace app\modules\pricer\controllers;


use yii\web\Controller;

class ListController  extends Controller
{
    public function actionList()
    {
        return $this->render('index');
    }

    public function actionAdd()
    {
        return $this->render('index');
    }
}