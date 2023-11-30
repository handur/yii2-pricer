<?php


namespace app\modules\pricer\job;

use app\modules\pricer\core\Log\Logger;
use app\modules\pricer\models\Price;

class ParseRows extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public $price_id;
    public $rows;
    public $pack_no;
    public $count_rows;


    public function execute($queue)
    {
        $price=PRICE::findOne(['id'=>$this->price_id]);
        $log_message="Обрабатываем ".$this->pack_no." x ".count($this->rows)." из ".count($this->count_rows)." прайса ".$price->name;
        Source::addRows($this->rows);
        $price->parseRows($this->rows);
        Logger::addLog('info','get_price_rows',$log_message,['price_id'=>$this->price_id]);
    }
}