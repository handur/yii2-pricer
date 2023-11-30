<?php

namespace app\modules\pricer\core\Readers;


class ReadMethodFactory
{
    public static function create($read_method,$settings){
        $read_method_class=__NAMESPACE__.'\\'.ucfirst($read_method).'ReadMethod';
        if(class_exists($read_method_class)){
            return new $read_method_class($settings);
        } else {
            throw new \Exception("Unable to load class: $read_method_class");
        }

    }
}