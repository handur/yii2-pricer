<?php


namespace app\modules\pricer\controllers;

use Yii;
use yii\console\Controller;
use app\modules\pricer\job\GetPriceJob;
use app\modules\pricer\models\Price;
use app\modules\pricer\core\Log\Logger;


class JobController  extends Controller
{
    public function actionIndex()
    {
        $prices=Price::findAll(['active' => 1]);
        $ids=[];
        Logger::addLog('info','start','Инициализация обновления прайсов');
        foreach($prices as $price) {
            $ids[]=$price->id;
            $log_message="Проверяем актуальность прайса ".$price->id.": ".$price->name;
            if($price->checkExpired()==TRUE){
                $log_message.="\nПрайс нуждается в обновлении";
                Yii::$app->queue->push(new GetPriceJob([
                    'price_id' => $price->id
                ]));
            } else {
                $log_message.="\nПрайс актуален, не обновляем";
            }
            Logger::addLog('info','check_expired',$log_message,['price_id'=>$price->id]);
        }
    }
}