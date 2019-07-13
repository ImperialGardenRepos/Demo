<?php

namespace ig;

spl_autoload_register(function ($className) {
	$strNamespace = 'ig\\';
	
	$intLen = strlen($strNamespace);
	if (strncmp($strNamespace, $className, $intLen) !== 0) {
		return;
	}
	
	$strRelativeClassName = substr($className, $intLen);
	
	$strClassFilename = __DIR__ .'/'. str_replace('\\', '/', $strRelativeClassName) . '.class.php';
	if (file_exists($strClassFilename)) {
		require ($strClassFilename);
	}
});

// custom section
require_once ($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/lib/Minifier.class.php');