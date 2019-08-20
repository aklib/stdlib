<?php

/**
 * $Id:$
 * $HeadURL:$
 * $Date:$
 * $Author:$
 * $Revision: $
 * 
 * StringUtils.php
 * 
 * @since 01.06.2017
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */
namespace Aklib\Stdlib\Util;

abstract class RuntimeUtils {
    
    public static function cli() {
        return (bool)preg_match('/^cli/', php_sapi_name());
    }
}