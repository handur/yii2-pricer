<?php
namespace app\modules\pricer\core\Parse;

use app\modules\pricer\core\Parse\ParseToken;

class ParseRule
{
    static public function process($ruleKey, $ruleSet, $fieldText, $data){
        $result="";
        switch($ruleKey){
            case 'findStr':
                /* поиск подстроки и её возврат */
                $list=$ruleSet['list'];
                foreach($list as $el){
                    $findPos = strpos($fieldText, $el);
                    if($findPos!==false) return $el;
                }
                break;
            case 'findRegexp':
                /* поиск подстроки по регулярному выражению */
                $pattern=$ruleSet['pattern'];
                $delta=$ruleSet['delta'];
                $source=$fieldText;
                $match=[];
                preg_match_all($pattern,$source,$match);
                if(!empty($match[0][$delta])){
                    return $match[0][$delta];
                }
                break;
            case 'excludeStr':
                /* удаление подстрок из строки */
                $list=$ruleSet['list'];
                $source=$fieldText;
                foreach($list as $el){
                    $replace=ParseToken::getToken($el,$data);
                    if(!empty($replace)) {
                        $result = str_replace($replace, "", $source);
                        $result = trim($result);
                        $source = $result;
                    }
                }
                return $result;
                break;
        }
    }

}