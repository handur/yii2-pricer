<?php


namespace app\modules\pricer\core\Parse;


class ParseToken
{
    static public function getToken($fieldMatch, $data){
        $tokens=self::paramScan($fieldMatch);
        if(!empty($tokens)) {
            $fieldText = [];
            foreach ($tokens as $token => $field) {
                $fieldSource = $field[0];
                $fieldName = $field[1];
                $processedRow = $data[$fieldSource];
                if (!isset($processedRow[$fieldName])) {
                    $fieldText[$token] = "";
                } else {
                    $fieldText[$token] = $processedRow[$fieldName];
                }
            }
            return str_replace(array_keys($tokens), $fieldText, $fieldMatch);
        }
        return $fieldMatch;
    }
    static public function paramScan($text){
        preg_match_all('/\\{([^\\s\\{\\}:]*):([^\\{\\}]*)\\}/x', $text, $matches);
        $token = $matches[0];
        $source = $matches[1];
        $field = $matches[2];
        for ($i = 0; $i < count($source); $i++) {
            $results[$token[$i]] = [$source[$i],$field[$i]];
        }
        if(!empty($results)) return $results;

        return FALSE;
    }
}