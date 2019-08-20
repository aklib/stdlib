<?php

/** 
 * ViewModelAware.php
 * 
 * @since 29.05.2016
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */
namespace Aklib\Stdlib\Initializer;

interface ViewModelAware {
	public function getList($config = array(), $options = array());
	public function getDetails($id);
        public function getOptionList($optionNname);
}