<?php
/**
 * RSS2Yandex Feed Template for displaying RSS2 Posts feed with Yandex.news compatible
 *
 * @author 		Maxim Borzov
 * @copyright	Copyright (c) 2012, Maxim Borzov (max.borzov@gmail.com)
 * @link		http://github.com/borzov/wp-rss2yandex
 * @since		Version 0.1
 */

header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>

<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" version="2.0">
<channel>
	<title><?=bloginfo_rss('name')?></title>
	<link><?=bloginfo_rss('url')?></link>
	<description><?=bloginfo_rss("description")?></description>
	<image>
		<title><?=bloginfo_rss('name')?></title>
		<link><?=bloginfo_rss('url')?></link>
		<url><?=$cfg['logo'] ?></url>
	</image>
	<?php while( have_posts()) : the_post(); ?>
	<item>
		<title><?=the_title_rss()?></title>
		<link><?=the_permalink_rss()?></link>
		<pubDate><?=get_post_time('D, d M Y H:i:s', true)?></pubDate>
		<author><?=the_author()?></author>
		<category><?php $category = get_the_category(); echo $category[0]->cat_name; ?></category>
		<?php
			preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i',$post->post_content, $post_images);
			if(!empty($post_images[1]) && count($post_images[1]) > 0) {
				foreach($post_images[1] as $image) {
					if (@fopen($image, "r")) {
		            	$image_info=getimagesize($image);
		            	?><enclosure url="<?=$image?>" type="<?=$image_info['mime']?>" /><?
	            	}
				}
			}
		?>
		<description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
		<yandex:full-text><?=htmlspecialchars(strip_tags(apply_filters('the_content_rss', $post->post_content), ENT_QUOTES))?></yandex:full-text>
	</item>
	<?php endwhile; ?>
</channel>
</rss>
