<?php

/**
 * $Id:$
 * $HeadURL:$
 * $Date:$
 * $Author:$
 * $Revision: $
 * 
 * Ini.php
 * 
 * @since 04.03.2017
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */
namespace Aklib\Stdlib\Initializer\Reader;

use \Zend\Config\Reader\Ini as ZendIni;

class Ini extends ZendIni {

	public function fromFile($filename, $section = false) {
		if ($section) {
			return $this->processSections(parent::fromFile($filename));
		}
		parent::fromFile($filename);
	}

	protected function processSections($array) {
		if (!is_array($array)) {
			return false;
		}
		$returnArray = array();
		foreach ($array as $key => $value) {
			$x = explode(':', $key);
			if (!empty($x[1])) {
				$x = array_reverse($x, true);
				foreach ($x as $k => $v) {
					$i = trim($x[0]);
					$v = trim($v);
					if (empty($returnArray[$i])) {
						$returnArray[$i] = array();
					}
					if (isset($array[$v])) {
						$returnArray[$i] = array_merge($returnArray[$i], $array[$v]);
					}
					if ($k === 0) {
						$returnArray[$i] = array_merge($returnArray[$i], $array[$key]);
					}
				}
			} else {
				$returnArray[$key] = $array[$key];
			}
		}

		return $returnArray;
	}

}
