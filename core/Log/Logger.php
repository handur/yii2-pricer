<?php
namespace app\modules\pricer\core\Log;


class Logger
{
    static array $log;
    static public function addLog(string $type = 'info', string $event='', string $message='', Array $data=[]){
        $data=['type'=>$type, 'event'=>$event, 'message'=>$message, 'data'=>serialize($data)];
        self::$log[]=$data;
        /*$log=new PricerLog();
        $log->attributes=$data;
        $log->save();
        */
    }
    static public function getLog($render=FALSE){
        if($render) {
            $output="";
            foreach(self::$log as $row){
                $output.=$row['type'].' | '.$row['message']."<br/>";
            }
            return $output;

        } else return self::$log;
    }

}