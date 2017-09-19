<?php
/**
 * Created by PhpStorm.
 * User: lius
 * Date: 2017/2/21
 * Time: 22:53
 */
$index_pop_except_id = waitig_gopt('index_pop_except_id');
$index_pop_except_num = waitig_gopt('index_pop_except_num');
?>
<div class="container">
    <div id="content">
        <div class="inner">
            <div class="title"><h3>最近更新</h3></div>
            <div class="details">
                <ul class="gengxin">
                    <?php
                    //query_posts("posts_per_page=".$index_pop_except_num."&cat=-1&order=ASC");
                    $args = array(
                        'order' => DESC,
                        'category__not_in' => array($index_pop_except_id),
                        'orderby' => '',
                        'posts_per_page' => waitig_gopt('$index_pop_except_num'),
                        'paged' => 1,
                        'caller_get_posts' => 1
                    );
                    query_posts($args);
                    while (have_posts()) :
                        the_post();
                        $postUrl = get_the_permalink();
                        $postTitle = get_the_title();
                        $postDate = get_the_time('Y-m-d H:i');
                        $category = get_the_category();
                        $catLink = get_category_link($category[0]->term_id);
                        $catName = $category[0]->name;
                        $catAuth = waitig_gopt("cat_author_" . $category[0]->term_id);
                        ?>
                        <li>
                        <span class="col1">
                            <a href="<?= $catLink ?>" target="_blank"><?= $catName ?></a>
                        </span>
                            <span class="col2">
                            <a href="<?= $postUrl ?>" target="_blank"><?= $postTitle ?></a>
                        </span>
                            <span class="col3"><?= $catAuth ?></span>
                            <span class="col4"><?= $postDate ?></span>
                        </li>
                    <?php endwhile;
                    wp_reset_query();
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div id="sidebar">
        <div class="inner">
            <div class="title"><h3>猜你喜欢</h3></div>
            <div class="details">
                <ul class="item-list">
                    <?php $args = array(
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hierarchical' => 0,
                        'child_of' => 0,
                        'hide_empty' => 1,
                        'taxonomy' => 'category',
                        'number' => $index_pop_except_num,
                        'exclude' => $index_pop_except_id

                    );
                    $categories = get_categories($args);
                    foreach ($categories as $category) {
                        $catLink = get_category_link($category->term_id);
                        $catName = $category->name;
                        $catAuth = waitig_gopt("cat_author_" . $category->term_id);
                        ?>
                        <li>
                            <a href="<?= $catLink ?>" target="_blank" title="$catName全文阅读"><?= $catName ?></a>
                            <?= $catAuth ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>