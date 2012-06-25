# wp-rss2yandex

RSS2Yandex Feed Template for displaying RSS2 Posts feed with [Yandex.News](http://news.yandex.ru/) compatible.

Yandex.News is the first news service in Russia that automatically processes and systemizes news stories, providing the day's headlines and a search of literally hundreds of news sources.

Specialists in various fields use the service: economists, entrepreneurs, journalists, political analysts, IT specialists, employees of the State Information Service and private companies.

## How to use

A simple to use feed template that makes feed for Yandex.News service. The new feed availeble after installation by simple URL, like that:

    http://site.com/?feed=rss2yandex
    
or that:

    http://site.com/export.php

## Install

Choose one of the two ways to installation feed template:

### Way #1 (recommended)

1. Upload `feed-rss2yandex.php` to the `/wp-includes` directory
2. Open `functions.php` into you theme folder (path like `/wp-content/themes/[theme]/functions.php`)
3. Insert this code into `functions.php`:
<pre>
    /**
     * Add new custom feed (rss2yandex)
     */
    add_action('do_feed_rss2yandex', 'acme_product_feed_rss2yandex', 10, 1 );
    function acme_product_feed_rss2yandex() {
        load_template( ABSPATH . WPINC . '/feed-rss2yandex.php' );
    }
</pre>
    
4. That's all, now you can test feed:

    http://site.com/?feed=rss2yandex

### Way #2

1. Upload `feed-rss2yandex.php` to the `/wp-includes` directory
2. Upload `wp-export.php` to the WP root directory
3. That's all, now you can test feed:

    http://site.com/export.php

## Changelog

### 0.2

* Created array with basic settings, now you can change meta-values
* Fixed prepare "yandex:full-timext" and grabbing under-more text
* Added basic comments and other small changes.
* Tabs changed to spaces
* Other fixes

### 0.1

* First version, lets test it :-)

Credits
-------
This feed template is built and maintained by [Maxim Borzov](http://maxborzov.com/)