<?php

namespace app\modules\pricer\core\Loaders;
use Yii;


abstract class LoadMethod implements LoadMethodInterface
{
    public array $settings = [];
    protected bool $test = FALSE;
    public array $phpFileUploadErrors = array(
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    );
    public function __construct($settings){
        $this->init($settings);
    }
    public function init($settings){
        $this->settings=$settings;
    }
    protected function getFilename(){
        $uploadDir=$this->settings['uploadDir'];
        $uniqueName=$this->settings['uniqueName'];
        $fileName=hash('md5',$uniqueName.'_'.date("Y-m-d_hia")).".raw";
        return $uploadDir."/".$fileName;
    }



}