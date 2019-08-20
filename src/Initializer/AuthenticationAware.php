<?php

/**
 * AuthenticationAware.php
 * 
 * @since 05.05.2018
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */
namespace Aklib\Stdlib\Initializer;


interface AuthenticationAware {
	public function getCurrentUser();
	public function setCurrentUser($user);
}