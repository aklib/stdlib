<?php

/** 
 * StringUtils.php
 * 
 * @since 01.06.2017
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */
namespace Aklib\Stdlib\Util;

abstract class ClassUtils {
    
    public static function getShortName($object) {
        if(is_object($object)){
            return substr(get_class($object), strrpos(get_class($object), '\\') + 1);
        }
        return gettype($object);
    }
    
    public static function getName($object) {        
        if(is_object($object)){
            return get_class($object);
        }
        return gettype($object);
    }
    
    public static function getMethods($object, $prefix = null) {        
        if(!is_object($object)){
            return [];
        }
        $methods = get_class_methods(get_class($object));
        if(empty($prefix)){
            return $methods;
        }
        $selected = [];
        foreach ($methods as $method) {
            if(StringUtils::startsWith($method, $prefix)){
                $selected[] = $method;
            }
        }
        return $selected;
    }
}