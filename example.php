<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <title>Google URL Shortener API example</title>
</head>
<body>

    <?php

    require_once 'googl.class.php';

    // Example 1.
    if($link = googl::shorten('http://github.com'))
    {
        echo 'Shorten: '.$link.'<br>';
    }
    else
    {
        echo 'Error: '.googl::getError();
    }

    // Example 2.
    if($link = googl::expand('http://goo.gl/KkZ8'))
    {
        echo 'Expanded: '.$link.'<br>';
    }
    else
    {
        echo 'Error: '.googl::getError();
    }

    ?>

</body>
</html>