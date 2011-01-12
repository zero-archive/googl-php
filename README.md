Google URL Shortener Class
==========================

Простой класс для сокращения ссылок с использованием нового API goo.gl сервиса Google URL Shortener

### Методы класса

Метод для сокращения ссылок

     googl::shorten('http://github.com')

Метод для распаковки ссылок вида `http://goo.gl/xxxxx`

    googl::expand('http://goo.gl/KkZ8')

В случае возникновения ошибки метод `getError()` будет хранить текст ошибки.

    googl::getError()
