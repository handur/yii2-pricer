<?php


namespace app\modules\pricer\core\Loaders;

use app\modules\pricer\core\Log\Logger;
use igogo5yo\uploadfromurl\UploadFromUrl;
use yii\db\Exception;
use yii\helpers\FileHelper;
use app\modules\pricer\models\Upload;


class UrlLoadMethod extends LoadMethod
{
    public function load(){
        $result=$error=[];
        $url = $this->settings['url'];
        try {
            Logger::addLog('info','Load file from url', 'Загружаем файл '.$url, $this->settings);
            $content = file_get_contents($url);
            if($this->settings['isLocalFile']){
                $result['path'] = $url;
            } else {
                $filename=$this->getFilename();
                file_put_contents($filename, $content);
                $result['path'] = $filename;
            }
            $result['file'] = $content;
        } catch (\Exception $e){
            $result['error']=$e->getMessage();
            Logger::addLog('error','Load file from url', 'Ошибка при загрузке файла: '.$result['error'], $this->settings);
        }




        /*
        $fullPath=$this->getFilename($url);
        $file = UploadFromUrl::initWithUrl($url);
        if(!empty($file->error)){
            $error['initialize upload']=$this->phpFileUploadErrors[$file->error];
        } else {
            try{
                $file->saveAs($fullPath);
            } catch(\Exception $e){
                $error['file save']=$e->getMessage();
            }
        }

        if(empty($error)) {
                // Создаём новый Upload-объект и возвращаем его
                $savedata=['Upload'=>['file'=>$fullPath,'size'=>$file->size,'created'=>date("Y-m-d H:i:s")]];
                $upload = new Upload();
                $upload->load($savedata);
                $upload->save();

                $result['upload']=$upload;
        }
        else $result['error']=$error;
        */
        return $result;
    }

}