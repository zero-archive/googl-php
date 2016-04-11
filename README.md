# PHP Googl

[![Build Status](https://travis-ci.org/dotzero/googl-php.svg?branch=master)](https://travis-ci.org/dotzero/googl-php)

A PHP5 library to generate shortened URL through The Google URL Shortener API.

## Usage

Using an Google API key is highly recommended. To acquire an API key follow [the instructions](https://developers.google.com/url-shortener/v1/getting_started#APIKey).

### Shorten URL

Shorten a long URL using The Google URL Shortener API

```php
try {
    $googl = new \dotzero\Googl('YOUR_GOOGLE_API');
    echo $googl->shorten('http://github.com');
} catch (\dotzero\GooglException $e) {
    printf('Error (%d): %s', $e->getCode(), $e->getMessage());
}
```

### Expand URL

Expand a short URL using The Google URL Shortener API

```php
try {
    $googl = new \dotzero\Googl('YOUR_GOOGLE_API');
    echo $googl->expand('http://goo.gl/KkZ8');
} catch (\dotzero\GooglException $e) {
    printf('Error (%d): %s', $e->getCode(), $e->getMessage());
}
```

## Install

### Via composer:

```sh
$ composer require dotzero/googl
```

### Without composer

Clone the project using `git clone https://github.com/dotzero/googl-php/`
and include the source file with `require_once("googl-php/src/Googl.php");`

## Test

First install the dependencies, and after you can run:

```bash
GOOGLE_API=YOUR_GOOGLE_API vendor/bin/phpunit
```

## License

Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
