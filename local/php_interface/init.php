<?php
/**
 * Autoload
 */
require_once($_SERVER["DOCUMENT_ROOT"] . '/local/vendor/autoload.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/autoload.php');

/**
 * Tools
 */
require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/constants.php');

/**
 * Events
 */
require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/events/system.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/events/admin.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/events/iblock.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/events/sale.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/events/catalog.php');

require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/init_vars.php');