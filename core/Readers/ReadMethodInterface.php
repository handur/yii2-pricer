<?php


namespace app\modules\pricer\core\Readers;
use app\modules\pricer\models\Upload;

interface ReadMethodInterface
{
    public function init(array $settings);
    public function read($file, array $options);
}