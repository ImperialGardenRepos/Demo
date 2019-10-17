<?php

namespace ig;

spl_autoload_register(static function ($classNameFull) {

    $classNameFull = trim($classNameFull, '\\');
    $classNameArray = explode('\\', $classNameFull);
    $className = array_pop($classNameArray);

    if (isset($classNameArray[0]) && strtolower($classNameArray[0]) === 'ig') {
        $classNameArray[0] = 'classes';
    }
    $classPathBase = __DIR__ . DIRECTORY_SEPARATOR . strtolower(implode(DIRECTORY_SEPARATOR, $classNameArray)) . DIRECTORY_SEPARATOR;
    $classPath = $classPathBase . $className . '.php';

    if (is_file($classPath)) {
        /** @noinspection PhpIncludeInspection */
        require_once $classPath;
        return;
    }

    //ToDo:: remove as no more need
    $classPath = $classPathBase . $className . '.class.php';

    if (is_file($classPath)) {
        /** @noinspection PhpIncludeInspection */
        require_once $classPath;
        return;
    }

});

