<?php
/**
 * To avoid 301-301-200/301-301-404 etc redirect issues all technical
 * duplicate avoiding redirects are here. It is slower, then Nginx, but easier to configure.
 */

$allowedHosts = [
    'imperial-garden.ru',
    'stage.imperial-garden.ru',
    'www.imperial-garden.ru',
    'www.stage.imperial-garden.ru',
    'imperial-garden.loc',
    'www.imperial-garden.loc',
    'imperialgarden.ru',
    'stage.imperialgarden.ru',
    'www.imperialgarden.ru',
    'www.stage.imperialgarden.ru',
    'imperialgarden.loc',
    'www.imperialgarden.loc'
];

if (!in_array($_SERVER['HTTP_HOST'], $allowedHosts, true)) {
    $host = 'imperialgarden.ru';
} else {
    /** @noinspection HostnameSubstitutionInspection */
    $host = $_SERVER['HTTP_HOST'];
}

$scheme = $_SERVER['REQUEST_SCHEME'] === 'https' ? 'https://' : 'http://';
$request = explode('?', $_SERVER['REQUEST_URI']);
$url = $request[0];
$params = isset($request[1]) ? '?' . $request[1] : '';

$rawRequestFull = $scheme . $host . $url . $params;
$possibleFilePah = $_SERVER['DOCUMENT_ROOT'] . $url;

/** Set scheme https */
$scheme = 'https://';
/** Remove www from host */
$host = str_replace('www.', '', $host);
/** Remove index.php, index.htm[l] */
$url = preg_replace('/(.*\/)(index.(php|html?))(.*)/', '$1$4', $url);
/** Add trailing slash if it is not a file*/
if (is_file($possibleFilePah) === false) {
    $url = preg_replace('/(.*[^\/])/', '$1/', $url);
}
/** Remove multiple slashes */
$url = preg_replace('/[\/]{2,}/', '/', $url);

/** Remove /page-1/ */
$url = str_replace('/page-1/', '/', $url);

/** Transform request to lc */
$url = mb_strtolower($url);

$finalRequestFull = $scheme . $host . $url . $params;

if ($finalRequestFull !== $rawRequestFull) {
    $protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';
    header("{$protocol} 301 Moved Permanently");
    header('X-src: init');
    header("Location: {$finalRequestFull}");
    exit();
}