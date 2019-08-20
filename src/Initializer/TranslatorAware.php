<?php

/**
 * TranslatorAware.php
 * 
 * @since 05.05.2018
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */

namespace Aklib\Stdlib\Initializer;

use Zend\I18n\Translator\TranslatorInterface;

interface TranslatorAware {
   public function getTranslator() : TranslatorInterface;
   public function setTranslator(TranslatorInterface $translator = null);
}
