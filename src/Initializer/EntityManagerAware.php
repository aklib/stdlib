<?php

/**
 * EntityManagerAware.php
 * 
 * @since 04.05.2018
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */
namespace Aklib\Stdlib\Initializer;


interface EntityManagerAware {
	public function getEntityManager();
	public function setEntityManager($em);
}

