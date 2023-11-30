<?php

namespace app\modules\pricer\core\Loaders;


class LoadMethodFactory
{
    public static function create($load_method,$settings){
        $load_method_class=__NAMESPACE__.'\\'.ucfirst($load_method).'LoadMethod';
        if(class_exists($load_method_class)){
            return new $load_method_class($settings);
        } else {
            throw new \Exception("Unable to load class: $load_method_class");
        }

    }
}