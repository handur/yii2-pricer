<?php


namespace app\modules\pricer\controllers;

use Yii;
use yii\helpers\Url;
use yii\console\Controller;
use app\modules\pricer\core\Log\Logger;

class DaemonController extends Controller
{
    public function actionIndex() {

        echo "Yes, cron service is running.";
    }
    public function actionHour() {


    }

    public function actionFrequent() {



    }


}