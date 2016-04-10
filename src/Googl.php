<?php

namespace dotzero;

/**
 * Class Googl
 *
 * A PHP5 library to generate shortened URL through The Google URL Shortener API.
 *
 * @package dotzero
 * @version 0.2
 * @author dotzero <mail@dotzero.ru>
 * @link   http://www.dotzero.ru/
 * @link https://github.com/dotzero/Googl
 * @link https://developers.google.com/url-shortener/v1/getting_started
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Googl
{
    /**
     * @var string The Google URL Shortener API endpoint.
     */
    const API_URL = 'https://www.googleapis.com/urlshortener/v1/url';

    /**
     * @var null|string The Google URL Shortener API key (optional).
     */
    private $apiKey = null;

    /**
     * Googl constructor.
     *
     * @param null|string The Google URL Shortener API key (optional).
     */
    public function __construct($apiKey = null)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Shorten a long URL using The Google URL Shortener API
     *
     * @link https://developers.google.com/url-shortener/v1/getting_started#shorten
     * @param string $link The URL to be shorten.
     * @return string
     * @throws GooglException
     */
    public function shorten($link)
    {
        if (!$this->checkLink($link)) {
            throw new GooglException('Incorrect URL format');
        }

        $params = array(
            'longUrl' => $link,
        );

        $result = $this->sendPostRequest($params);

        return $result['id'];
    }

    /**
     * Expand a short URL using The Google URL Shortener API
     *
     * @link https://developers.google.com/url-shortener/v1/getting_started#expand
     * @param string $link The URL to be expanded.
     * @return bool
     * @throws GooglException
     */
    public function expand($link)
    {
        if (!$this->checkLink($link)) {
            throw new GooglException('Incorrect URL format');
        }

        $params = array(
            'shortUrl' => $link,
        );

        $result = $this->sendRequest($params);

        return $result['longUrl'];
    }

    /**
     * A good url regular expression?
     *
     * @link http://flanders.co.nz/2009/11/08/a-good-url-regular-expression-repost/
     * @param string $url An URL string.
     * @return bool
     */
    private function checkLink($url)
    {
        $regex = '/(?:(?:ht|f)tp(?:s?)\:\/\/|~\/|\/)?(?:\w+:\w+@)?(?:(?:[-\w]+\.)+(?:com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum|travel|[a-z]{2}))(?::[\d]{1,5})?(?:(?:(?:\/(?:[-\w~!$+|.,=]|%[a-f\d]{2})+)+|\/)+|\?|#)?(?:(?:\?(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)(?:&(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)*)*(?:#(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)?/';

        return preg_match($regex, $url) ? true : false;
    }

    /**
     * Send cURL GET Request and return body.
     *
     * @param array $params GET params.
     * @return mixed
     * @throws GooglException
     */
    private function sendRequest($params)
    {
        if ($this->apiKey !== null) {
            $params['key'] = $this->apiKey;
        }

        $ch = curl_init(self::API_URL . '?' . http_build_query($params));

        curl_setopt_array($ch, array(
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_RETURNTRANSFER => true,
        ));

        if (!$result = curl_exec($ch)) {
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);

            throw new GooglException($error, $errno);
        }

        curl_close($ch);

        $result = json_decode($result, true);

        if (isset($result['error'])) {
            throw new GooglException($result['error']['message'], $result['error']['code']);
        }

        return $result;
    }

    /**
     * Send cURL POST Request and return body.
     *
     * @param array $params POST params.
     * @return mixed
     * @throws GooglException
     */
    private function sendPostRequest($params)
    {
        $endpoint = self::API_URL;

        if ($this->apiKey !== null) {
            $endpoint .= '?key=' . $this->apiKey;
        }

        $ch = curl_init($endpoint);

        curl_setopt_array($ch, array(
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($params),
        ));

        if (!$result = curl_exec($ch)) {
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);

            throw new GooglException($error, $errno);
        }

        curl_close($ch);

        $result = json_decode($result, true);

        if (isset($result['error'])) {
            throw new GooglException($result['error']['message'], $result['error']['code']);
        }

        return $result;
    }
}

/**
 * Class GooglException
 *
 * @package dotzero
 */
class GooglException extends \Exception
{

}
