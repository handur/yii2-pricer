<?php
namespace app\modules\pricer\job;

use Yii;
use app\modules\pricer\core\Log\Logger;
use app\modules\pricer\models\Price;


class GetPriceJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public $price_id;


    public function execute($queue)
    {
        $price=PRICE::findOne(['id'=>$this->price_id]);
        $price_rows=$price->process(['ignoreExpire'=>TRUE]);
        $log_message="Скачиваем прайс ".$price->id.": ".$price->name;
        Logger::addLog('info','get_price',$log_message,['price_id'=>$this->price_id]);
        Yii::$app->queue->push(new StartPriceJob([
            'price_id' => $this->price_id
        ]));
        $price_rows_chunks=array_chunk($price_rows,100);
        foreach($price_rows_chunks as $pack_no=>$chunk) {
            Yii::$app->queue->push(new ParseRows([
                'price_id' => $this->price_id,
                'rows'=>$chunk,
                'pack_no'=>$pack_no,
                'count_rows'=>array_keys($price_rows)
            ]));
        }

        Yii::$app->queue->push(new FinishPriceJob([
            'price_id' => $this->price_id
        ]));


    }
}