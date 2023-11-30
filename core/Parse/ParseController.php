<?php

namespace app\modules\pricer\core\Parse;

use app\modules\pricer\core\Log\Logger;

class ParseController
{
    private array $dataSet=[];

    public function __construct(array $dataSet){
        $this->dataSet=$dataSet;
    }

    public function parseRow($sourceRow=[]){
        $parsedRow=[];
        $dataSet=$this->dataSet;
        foreach($dataSet as $dataKey=>$dataRule){
          #  print "Создаём данные для ".$dataKey."\n";
            $parsedRow[$dataKey]=$this->parseItem($dataRule,$sourceRow,$parsedRow);
        }
        if(!empty($parsedRow['row_key'])) $log_message='Парсим строку '.$parsedRow['row_key'];
        else $log_message='Парсим строку '.implode(" | ",array_slice($sourceRow,0,3));
        Logger::addLog('info','parse row',$log_message,$sourceRow);
        return $parsedRow;
    }

    private function parseItem($dataRule,$sourceRow,$parsedRow){
        $fieldMatch=$dataRule['field'];
       # print "- Парсим колонку ".$fieldMatch."\n";
        $fieldResult=ParseToken::getToken($fieldMatch,['source'=>$sourceRow,'parsed'=>$parsedRow]);
        if(!empty($dataRule['rules'])) {
            $rules = $dataRule['rules'];
            foreach ($rules as $ruleKey => $ruleSet) {
               # print "-- Правило обработки: " . $ruleKey . "\n";
                $result = $this->processRule($ruleKey, $ruleSet, $fieldResult, $sourceRow, $parsedRow);
            }
        } else {
         #   print "-- Правило обработки отсутствует\n";
            $result=$fieldResult;
        }
      #  print "-- Результат обработки: " . $result . "\n";
        return $result;
    }

    private function processRule($ruleKey,$ruleSet,$fieldText,$sourceRow,$parsedRow){
        $data=['source'=>$sourceRow,'parsed'=>$parsedRow];
        return ParseRule::process($ruleKey, $ruleSet, $fieldText, $data);
    }
}