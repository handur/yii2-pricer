<?php


namespace app\modules\pricer\core\loaders;


interface LoadMethodInterface
{
    public function init(array $settings);
    public function load();

}