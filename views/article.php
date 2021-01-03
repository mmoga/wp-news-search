<?php
/**
 * Article view.
 *
 * @package wp-news-search
 */

?>

<div class="wp-news-search-articles__container">
    <div class="wp-news-search-articles__wrapper">
        <?php foreach ($articles as $article):
    $article_img = $article->urlToImage;
    $placeholder_img = 'https://via.placeholder.com/350x250';
    ?>
	        <div class="wp-news-search-articles__block">
	            <article class="wp-news-search-articles-article">
	                <div class="img__wrapper">
	                    <img src="<?php echo $article_img ?: $placeholder_img; ?>" alt="" loading="lazy" width="1500"
	                        height="1368">
	                </div>
	                <div class="article-content">
	                    <a href="<?php echo $article->url; ?>">
	                        <h2 class="article-title">
	                            <?php echo $article->title; ?>
	                        </h2>
	                    </a>
	                    <p class="article-description">
	                        <?php echo $article->description; ?>
	                    </p>
	                </div>
	            </article>
	        </div>
	        <?php endforeach;?>
    </div>
</div>