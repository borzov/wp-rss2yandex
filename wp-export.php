<?php

if (empty($wp)) {
	require_once('./wp-config.php');
	wp('feed=rss2yandex');
}

require (ABSPATH . WPINC . '/feed-rss2yandex.php');

?>