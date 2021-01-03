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
        <div class="wp-news-search-articles__article">
            <img src="<?php echo $article_img ?: $placeholder_img; ?>" alt="">
            <a href="<?php echo $article->url; ?>">
                <h3>
                    <?php echo $article->title; ?>
                </h3>
            </a>
        </div>
        <?php endforeach;?>
    </div>
</div>