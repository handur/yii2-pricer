<?php


namespace app\modules\pricer\core\Readers;
use app\modules\pricer\core\Log\Logger;
use app\modules\pricer\models\Upload;

class XmlReadMethod extends ReadMethod
{
    public function read($file, array $options){
        $limit=!empty($options['limitRow'])?$options['limitRow']:0;
        Logger::addLog('info','Read xml file from url', 'Читаем XML-файл '.$file['path'], $this->settings);
        try{
            $xml = simplexml_load_file($file['path']);
        } catch (\Exception $e){
            Logger::addLog('error','Error read xml file from url', $e->getMessage(), $this->settings);
            return NULL;
        }

        $xquery_product=$this->settings['xquery_product'];
        $params_product=$this->settings['params_product'];

        $products=$xml->xpath($xquery_product);

        $result=[];
        foreach($products as $product){
            $productArr=[];
            if($params_product=='tags') {
                $productArr = $this->xml2array($product);
            } elseif($params_product=='attr') {
                if(!empty($product->attributes())) {
                    $productArr = current($product->attributes());
                }
            }
            if(!empty($productArr)) $result[]=$productArr;
            if(!empty($limit)&&count($result)>=$limit) break;
        }

        return $result;

        /* Реализация чтения прайс-листа */
    }
    private function xml2array(\SimpleXMLElement $parent)
    {
        $array = array();

        foreach ($parent as $name => $element) {
            ($node = & $array[$name])
            && (1 === count($node) ? $node = array($node) : 1)
            && $node = & $node[];

            $node = $element->count() ? $this->xml2array($element) : trim($element);
        }

        return $array;
    }

}