<?php

/**
 * 
 * BcryptUtils.php
 * 
 * @since 19.07.2017
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */

namespace Aklib\Stdlib\Util;

use Zend\Crypt\Password\Bcrypt;

final class BcryptUtils {

    /**
     * Accept 1 - 31
     */
    const COST = 14;

    public static function createPassword($password) {
        $bcrypt = new Bcrypt;
        $bcrypt->setCost(static::COST);
        return $bcrypt->create($password);
    }

    public static function verifyUserPassword($userEntity, $rawPassword) {
        if (!is_object($userEntity)) {
            return false;
        }
        if (!method_exists($userEntity, 'getPassword')) {
            return false;
        }
        $password = $userEntity->getPassword();
        return static::verifyPassword($rawPassword, $password);
    }

    public static function verifyPassword($rawPassword, $password) {
        $bcrypt = new Bcrypt;
        $bcrypt->setCost(static::COST);
        return $bcrypt->verify($rawPassword, $password);
    }

    public static function isBcrypted($password) {
        if (empty($password)) {
            return false;
        }
        $length = (int) StringUtils::length($password);
        if ($length > 60 || $length < 59) {
            return false;
        }
        if (StringUtils::startsWith($password, '$2y$') && $length === 60) {
            return true;
        }
        if (StringUtils::startsWith($password, '$2a$') && $length === 60) {
            return true;
        }

        if (StringUtils::startsWith($password, '$2$') && $length === 59) {
            return true;
        }
        return false;
    }

}
