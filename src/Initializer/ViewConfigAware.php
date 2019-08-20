<?php

/**
 * ViewConfigAware.php
 * 
 * @since 29.05.2016
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */
namespace Aklib\Stdlib\Initializer;

interface ViewConfigAware {
	public function getViewConfig();
	public function setViewConfig($c);
}