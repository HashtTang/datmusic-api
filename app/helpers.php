<?php

use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * @return GuzzleHttp\Client Guzzle http client
 */
function httpClient()
{
    return app('httpClient')->getClient();
}

/**
 * @return \App\Util\CoverArtClient
 */
function covers()
{
    return app('coverArtClient');
}

/**
 * @return \App\Util\Logger logger instance
 */
function logger()
{
    return app('logger');
}

/**
 * Function: sanitize (from Laravel)
 * Returns a sanitized string, typically for URLs.
 * Parameters:.
 *
 * @param $string          - The string to sanitize.
 * @param $force_lowercase - Force the string to lowercase?
 * @param $anal            - If set to *true*, will remove all non-alphanumeric characters.
 * @param $trunc           - Number of characters to truncate to (default 100, 0 to disable).
 *
 * @return string sanitized string
 */
function sanitize($string, $force_lowercase = true, $anal = false, $trunc = 100)
{
    $strip = [
        '~', '`', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '=', '+', '[', '{', ']',
        '}', '\\', '|', ';', ':', '"', "'", '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8211;',
        '&#8212;', '—', '–', ',', '<', '>', '/', '?',
    ];
    $clean = trim(str_replace($strip, '', strip_tags($string)));
    // $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal ? preg_replace('/[^a-zA-Z0-9]/', '', $clean) : $clean);
    $clean = ($trunc ? substr($clean, 0, $trunc) : $clean);

    return ($force_lowercase) ? (function_exists('mb_strtolower')) ? mb_strtolower($clean, 'UTF-8') : strtolower($clean) : $clean;
}

/**
 * Build full url. Prepends APP_URL to given string.
 *
 * @param $path
 *
 * @return string
 */
function fullUrl($path)
{
    return sprintf('%s/%s', env('APP_URL'), $path);
}

/**
 * @return string random artist name
 */
function randomArtist()
{
    $randomArray = config('app.artists');
    $randomIndex = array_rand($randomArray);

    return $randomArray[$randomIndex];
}

/**
 * @param ResponseInterface $response
 *
 * @return stdClass
 */
function as_json($response)
{
    return json_decode((string) $response->getBody());
}

/**
 * Get param from the given request for given possible keys.
 *
 * @param Request $request
 * @param mixed   ...$keys
 *
 * @return mixed|null
 */
function getPossibleKeys(Request $request, ...$keys)
{
    foreach ($keys as $key) {
        if ($request->has($key)) {
            return $request->get($key);
        }
    }

    return null;
}

function subPathForHash($hash)
{
    return sprintf('%s/%s', substr($hash, 0, 2), substr($hash, 2, 2));
}
