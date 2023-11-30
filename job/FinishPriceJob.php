<?php
namespace app\modules\pricer\job;

use app\modules\pricer\core\Log\Logger;


class FinishPriceJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public string $price_id;
    /**
     * @inheritDoc
     */
    public function execute($queue)
    {
        // TODO: Implement execute() method.
        Logger::addLog('info','finish_price_job','Закончили обновление прайса ID '.$this->price_id,['price_id'=>$this->price_id]);
    }
}