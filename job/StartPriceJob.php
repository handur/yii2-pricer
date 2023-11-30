<?php


namespace app\modules\pricer\job;

use app\modules\pricer\core\Log\Logger;
use app\modules\pricer\models\Source;


class StartPriceJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public $price_id;

    /**
     * @inheritDoc
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        Source::deleteAll('price_id = :price_id', [':price_id' => $this->price_id]);
        Logger::addLog('info','start_price_job','Начали обновление прайса ID '.$this->price_id,['price_id'=>$this->price_id]);
    }
}