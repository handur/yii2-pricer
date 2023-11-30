<?php


namespace app\modules\pricer\core\Readers;



abstract class ReadMethod implements ReadMethodInterface
{
    protected array $settings = [];
    protected string $file = '';
    public function __construct($settings){
        $this->init($settings);
    }
    public function init($settings){
        $this->settings=$settings;
    }

}