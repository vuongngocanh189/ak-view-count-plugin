<?php
/**
 * Top view count shortcode template
 */

if (!defined('ABSPATH')) exit;

if($top_view_articles) :
?>
<ul>
	<?php foreach($top_view_articles as $key=>$articles) : ?>
	<li>
		<?php echo $key + 1; ?>.
		<a href="<?php echo $articles['link']; ?>">
            <?php echo $articles['title']; ?>
        </a>
	</li>
	<?php endforeach; ?>
</ul>
<?php endif;
