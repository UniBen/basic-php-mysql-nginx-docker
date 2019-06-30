<?php

use Framework\Util\Arr;

/**
 * Returns an instance of the app.
 *
 * @return Framework\App
 */
function app() {
    return Framework\App::getInstance();
}

/**
 * @return Framework\Request
 */
function request() {
    return Framework\Request::capture();
};

/**
 * @param     $response
 * @param int $code
 *
 * @return false|string
 *
 * @throws Framework\Exceptions\ViewNotFoundException
 */
function response($response, $code = 200) {
    // Inject flash data in to view
    if ($response instanceof Framework\View) {
        if ($_SESSION['_flash'] && !is_null($_SESSION['_flash'])) {
            $response->inject($_SESSION['_flash']);
            unset($_SESSION['_flash']);
        }

        // Render the view
        return $response->render();
    } else if($response) {
        return jsonResponse($response, $code);
    }
}

/**
 * @param null $data
 * @param int  $code
 *
 * @return false|string
 */
function jsonResponse ($data = null, $code = 200)
{
    header_remove();

    $status = [200 => '200 OK', 400 => '400 Bad Request', 422 => 'Unprocessable Entity', 500 => '500 Internal Server Error'];

    header('Status: '.$status[$code]);
    header("Cache-Control: no-transform,public,max-age=0,s-maxage=0");
    header('Content-Type: application/json; charset=utf-8');

    http_response_code($code);

    return json_encode($data ?? [], isDebug() ? JSON_PRETTY_PRINT : null);
}

/**
 * @param int $code
 */
function abort($code = 404) {
    http_response_code($code);
    echo view('pages.404');
    exit;
}

/**
 * @param int $code
 */
function fatal($code = 500) {
    http_response_code($code);
    echo view('pages.500');
    exit;
}

/**
 * @param Exception $exception
 */
function error(Exception $exception) {
    print "<strong>{$exception->getMessage()} ({$exception->getFile()}:{$exception->getLine()})</strong>";
    display($exception->getTraceAsString());
    exit;
}

/**
 * @param string $to
 * @param int    $responseCode
 * @param array  $data
 */
function redirect(string $to, int $responseCode = 302, $data = []) {
    $_SESSION['_flash'] = $data;
    header("Location: $to", true, $responseCode);
    exit;
}

/**
 * Sets header location to REFERER
 *
 * @param int   $responseCode
 * @param array $data
 */
function back(int $responseCode = 302, $data = []) {
    redirect($_SERVER['HTTP_REFERER'] ?? '/', $responseCode ?? 302, $data);
}

/**
 * @param string $key
 * @param mixed  $default
 *
 * @return mixed
 */
function config(string $key = null, $default = null) {
    $config = require __DIR__ . '/../config.php';

    if ($value = Arr::get($config, $key)) {
        return $value;
    } else if (!is_null($default)) {
        return $default;
    }

    // Return the entire config if nothing is passed to function.
    if (is_null($key) && is_null($default)) return $config;

    return false;
}

/**
 * @param null $key
 *
 * @return bool
 */
function env($key = null) {
    if ($key) {
        if (isset($_ENV[$key])) return $_ENV[$key];
    } else {
        return false;
    }

    return $_ENV;
}

/**
 * @return bool
 */
function isDebug() {
    return env('ENVIRONMENT') === 'development' || $_SESSION['debug'];
}

/**
 * Dumps data.
 *
 * @param      $data
 * @param bool $wrap
 */
function dump($data, $wrap = true) {
    if (php_sapi_name() == 'cli') $wrap = false;

    echo $wrap ? "<pre>" : null;
    var_dump($data);
    echo $wrap ? "</pre>" : null;
}

/**
 * Dumps data then dies.
 *
 * @param mixed $data
 */
function dd(...$data) {
    $data = count($data) <= 1 ? $data[0] : $data;
    dump($data);
    die;
}

/**
 * Displays data passed as param in a styled div.
 *
 * @param string $data
 */
function display(string $data) {
    echo "<pre style='border: 1px solid #ddd; padding: 20px; margin: 10px; font-family: monospace; line-height: 1.5;' '>$data</pre>";
}

/**
 * Tries to strip the namespace from a class and just return the name.
 *
 * @param $class
 *
 * @return string
 */
function getClassName(string $class) {
    try {
        return (new ReflectionClass($class))->getShortName();
    } catch (ReflectionException $e) {
        return $class;
    }
}

/**
 * @param string $string
 *
 * @return string
 */
function slug(string $string) {
    return Framework\Util\Str::slug($string);
}

/**
 * @param string $string
 *
 * @return string
 */
function url(string $string) {
    return Framework\Util\Str::url($string);
}

/**
 * @param string $name
 * @param array  $data
 * @param bool   $cache
 *
 * @return Framework\View
 */
function view(string $name, array $data = [], bool $cache = true) {
    return new Framework\View($name, $data, $cache);
}

/**
 * @param Framework\Queueable $object
 */
function dispatch(Framework\Queueable $object) {
    Framework\Queue::dispatch($object);
}