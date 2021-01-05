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
    $placeholder_img = 'https://via.placeholder.com/900x600?text=No+article+image';
    ?>
        <div class="wp-news-search-articles__block">
            <article class="wp-news-search-articles-article">
                <div class="img__wrapper">
                    <img src="<?php echo $article_img ?: $placeholder_img; ?>" alt="" loading="lazy" width="900"
                        height="600">
                </div>
                <div class="article-content">
                    <a href="<?php echo $article->url; ?>" rel="noopener noreferrer" target="_blank">
                        <h2 class="article-title">
                            <?php echo $article->title; ?>
                        </h2>
                        <span class="screen-reader-text">Opens in new tab</span>
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