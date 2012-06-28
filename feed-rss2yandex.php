<?php
/**
 * RSS2Yandex Feed Template for displaying RSS2 Posts feed with Yandex.news compatible
 *
 * @author      Maxim Borzov
 * @copyright   Copyright (c) 2012, Maxim Borzov (max.borzov@gmail.com)
 * @link        http://github.com/borzov/wp-rss2yandex
 * @since       Version 0.4
 */

//For more complex and customizable plugins that provide many options
$settings = array(
    // You blog title, max 100 chars
    'blog_name'  => get_bloginfo_rss('name'),
    // HTTP-link to you blog
    'blog_url'   => get_bloginfo_rss('url'),
    // Short description, max 255 chars
    'blog_desc'  => get_bloginfo_rss('description'),
    // HTTP-link to logo
    'blog_logo'  => get_bloginfo('template_directory') . '/images/logo.jpg'
);

/**
 * Extract and format all images from text
 *
 * @param   string  $post   The text string, like post
 * @return  array
 */

function get_post_images_rss($post) {
    // Temp buffer
    $buffer = array();
    // Search for the src="" and <img> for extracting Images from post content
    preg_match_all("/<img[^']*?src=\"([^']*?)\"[^']*?>/i",$post, $post_images);
    // Check to see if we have at least 1 image
    if(!empty($post_images[1]) && count($post_images[1]) > 0) {
        // Generate the <enclosure> elements allows a media-files to be included with an item
        foreach($post_images[1] as $image) {
            // Check remote file availability
            if (@fopen($image, "r")) {
                $image_info=getimagesize($image);
                $buffer[] = sprintf('<enclosure url="%s" type="%s" />', $image, $image_info['mime']);
            }
        }
    }
     // Collected links or nothing
    return (!empty($buffer)) ? $buffer : false;
}
 
/**
 * Extract and format all external links from text
 *
 * @param   string  $post   The text string, like post
 * @return  array
 */

function get_post_links_rss($post, $domain = '') {
    // Basic host
    $domain = ($domain === '') ? preg_quote(str_replace("www.","", $_SERVER['HTTP_HOST']), '/') : $domain;
    // Temp buffer
    $buffer = array();
    // Find all links in text
    if (preg_match_all("/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU", $post, $post_links, PREG_SET_ORDER)) {
        foreach($post_links as $link) {
            // Compare links with basic host
            if (preg_match("/^(ht|f)tps?:\/\/(?!((www\.)?{$domain}))/i", $link[2])) {
                $buffer[] = sprintf('<link url="%s">%s</link>', $link[2], $link[3]);
            }
        }
    }
    // Collected links or nothing
    return (!empty($buffer)) ? $buffer : false;
}

// Send a raw HTTP header to browser with type and charset information
header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);

// RSS is a dialect of XML. All RSS files must conform to the XML 1.0 specification
printf('<?xml version="1.0" encoding="%s"?>', get_option('blog_charset'));
?>

<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" version="2.0">
<channel>
    <title><?=$settings['blog_name']?></title>
    <link><?=$settings['blog_url']?></link>
    <description><?=$settings['blog_desc']?></description>
    <image>
        <title><?=$settings['blog_name']?></title>
        <link><?=$settings['blog_url']?></link>
        <url><?=$settings['blog_logo'] ?></url>
    </image>
    <?php while( have_posts()) : the_post(); ?>
    <item>
        <title><?=the_title_rss()?></title>
        <link><?=the_permalink_rss()?></link>
        <pubDate><?=mysql2date('D, d M Y H:i:s +' . sprintf('%02d00', get_option('gmt_offset')), get_date_from_gmt(get_post_time('Y-m-d H:i:s', true)), false)?></pubDate>
        <author><?=the_author()?></author>
        <category><?php $category = get_the_category(); echo $category[0]->cat_name; ?></category>
        <?php
            // Over-ride the default $more global variable
            $more = 1;
            // Extract all images to generate <enclosure> for media files
            if ($images = get_post_images_rss($post->post_content)) {
                print(implode(null, $images));
            }
            // Extract all links to generate recommended <yandex:related>
            if ($links = get_post_links_rss($post->post_content)) {
                printf('<yandex:related>%s</yandex:related>', implode(null, $links));
            }
        ?>
        <description><?=strip_tags(apply_filters('the_excerpt_rss',get_the_excerpt(true)))?></description>
        <yandex:full-text><?=trim(htmlspecialchars(strip_tags(apply_filters('the_content_rss', $post->post_content), ENT_QUOTES)))?></yandex:full-text>
    </item>
    <?php endwhile; ?>
</channel>
</rss>
