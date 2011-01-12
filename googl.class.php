<?php
/**
 * Google URL Shortener Class
 *
 * Простой класс для сокращения ссылок с использованием нового API goo.gl
 * сервиса Google URL Shortener
 *
 * @package googl
 * @author  dZ <mail@dotzero.ru>
 * @version 0.1 (12-jan-2010)
 * @link    http://dotzero.ru
 * @link    https://github.com/dotzero/Google-URL-Shortener-Class/
 * @link    http://code.google.com/intl/ru/apis/urlshortener/index.html
 */
class googl
{
    /**
     * Google URL Shortener API
     */
    const API_URL = 'https://www.googleapis.com/urlshortener/v1/url';

    /**
     * Сообщения об ошибках
     */
    const INTERNAL_ERROR = 'Ошибка в процессе создания ссылки';
    const INCORRECT_URL = 'Не корректная ссылка';

    /**
     * Содержит сообщение о последней ошибке
     */
    private static $errorMessage = null;

    /**
     * Метод для сокращения ссылки
     *
     * @param string $link
     * @return mixed
     */
    public static function shorten($link)
    {
        self::$errorMessage = null;

        if(!self::checkLink($link))
        {
            self::$errorMessage = self::INCORRECT_URL;
            return false;
        }

        $ch = curl_init(self::API_URL);

        $options = array(
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => '{"longUrl": "' . $link . '"}'
        );

        curl_setopt_array($ch, $options);

        $result = json_decode(curl_exec($ch), true);

        if(empty($result['id']))
        {
            self::$errorMessage = self::INTERNAL_ERROR;
            return false;
        }

        return $result['id'];
    }

    /**
     * Метод для распаковки ссылок вида http://goo.gl/xxxxx
     *
     * @param string $link
     * @return mixed
     */
    public static function expand($link)
    {
        self::$errorMessage = null;

        if(!preg_match('#http://goo.gl/(.*)#i', $link))
        {
            self::$errorMessage = self::INCORRECT_URL;
            return false;
        }

        $ch = curl_init(self::API_URL.'?shortUrl='.$link);

        $options = array(
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_RETURNTRANSFER => true,
        );

        curl_setopt_array($ch, $options);

        $result = json_decode(curl_exec($ch), true);

        if(empty($result['longUrl']))
        {
            self::$errorMessage = self::INTERNAL_ERROR;
            return false;
        }

        return $result['longUrl'];
    }

    /**
     * Возвращает сообщение о последней ошибке
     *
     * @return mixed
     */
    public static function getError()
    {
        return self::$errorMessage;
    }

    /**
     * A good url regular expression?
     *
     * @link http://flanders.co.nz/2009/11/08/a-good-url-regular-expression-repost/
     * @param string $url
     * @return bool
     */
    public static function checkLink($url)
    {
        $regex = '/(?:(?:ht|f)tp(?:s?)\:\/\/|~\/|\/)?(?:\w+:\w+@)?(?:(?:[-\w]+\.)+(?:com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum|travel|[a-z]{2}))(?::[\d]{1,5})?(?:(?:(?:\/(?:[-\w~!$+|.,=]|%[a-f\d]{2})+)+|\/)+|\?|#)?(?:(?:\?(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)(?:&(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)*)*(?:#(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)?/';

        return preg_match($regex, $url) ? true : false;
    }
}