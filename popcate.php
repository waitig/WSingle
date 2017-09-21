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
            <div class="title">
                <h3>最近更新</h3>
            </div>
            <div class="details">
                <ul class="gengxin">
                    <?php
                    $args = array(
                        'numberposts' => $index_pop_except_num,
                        'offset' => 1,
                        'category' => 0,
                        'orderby' => 'post_date',
                        'order' => 'DESC',
                        'post_status' => 'publish');
                    $postList = get_posts($args);
                    foreach ($postList as $recent) {
                        $postUrl = get_permalink($recent->ID);
                        $postTitle = $recent->post_title;
                        $postDate = $recent->post_date;
                        $cat = get_the_category($recent->ID);
                        $category = get_root_category($cat[0]);
                        $catLink = get_category_link($category->term_id);
                        $catName = $category->name;
                        $catAuth = waitig_gopt("cat_author_" . $category->term_id);
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
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div id="sidebar">
        <div class="inner">
            <div class="title"><h3>最新入库</h3></div>
            <div class="details">
                <ul class="item-list">
                    <?php
                    $args2 = array(
                        'type' => 'post',
                        'child_of' => 0,
                        'parent' => '0',
                        'orderby' => 'ID',
                        'order' => 'DESC',
                        'hide_empty' => 0,
                        'hierarchical' => 0,
                        'exclude' => $index_pop_except_id,
                        'include' => '',
                        'number' => $index_pop_except_num,
                        'taxonomy' => 'category',
                        'pad_counts' => false);
                    $categories = get_categories($args2);
                    for($i = 0; ($i<$index_pop_except_num&&$i<count($categories));$i++){
                    //foreach ($categories as $category) {
                        $category = $categories[$i];
                        $catLink = get_category_link($category->term_id);
                        $catName = $category->name;
                        $catAuth = waitig_gopt("cat_author_" . $category->term_id);
                        ?>
                        <li>
                            <a href="<?= $catLink ?>" target="_blank" title="<?= $catName ?>全文阅读"><?= $catName ?></a>
                            <?= $catAuth ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>