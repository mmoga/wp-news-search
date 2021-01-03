<?php
/**
 * Article view.
 *
 * @package wp-news-search
 */

?>

<div class="wp-news-search-articles__container">
    <div class="wp-news-search-articles__wrapper">
        <?php foreach ($result->articles as $article): ?>
        <div class="wp-news-search-articles__article">
            <h3>
                <?php echo $article->title; ?>
            </h3>
        </div>
        <?php endforeach;?>
    </div>
</div>