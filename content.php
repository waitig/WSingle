<?php
/**
 * File: content.php.
 * User: LiuS
 * Date: 2017/2/21
 * Time: 15:10
 * Index:http://www.waitig.com
 * Theme:WBetter Theme
 */
$cat_id = 1;
$right_cat_id = 1;
if (is_category()) {
    $cat_id = get_cat_ID(single_cat_title('', false));
} elseif (is_home()) {
    $cat_id = waitig_gopt('index_cat_id');
} elseif (is_single()) {
    $categorys = get_the_category();
    $category = $categorys[0];
    $cat_id = $category->term_id;
}
$thiscat = get_category($cat_id);
$catUrl = get_category_link($thiscat->term_id);
$right_cat_id = waitig_gopt('right_cat_id');
$cats_id_arr = get_term_children($cat_id, 'category');
?>
<div class="container">
    <div class="inner">
        <div class="bookinfo">
            <div class="btitle">
                <h1><a href="<?= $catUrl ?>" target="_blank"><?= $thiscat->name ?></a></h1>
                <em>作者：<?= waitig_gopt("cat_author_" . $thiscat->term_id) ?></em>
            </div>
            <p class="stats">
                <span class="fl"><b>最新章节：</b><?php query_posts("posts_per_page=1&cat=" . $cat_id) ?>
                    <?php while (have_posts()) :
                    the_post(); ?>
                    <a href="<?php the_permalink() ?>" target="_blank" title="<?php the_title(); ?>">
                            <?php the_title(); ?>
                    </a></span></p>
            <div class="status"><font color="#999999">状态：</font>连载中&nbsp;&nbsp;&nbsp;<font
                        color="#999999">更新时间：</font><?php echo the_time('Y年m月d日 H:i'); ?></div>
            <?php endwhile;
            wp_reset_query(); ?>
            <div class="clear"></div>
            <div class="intro">
                <b>内容简介：</b>
                <p><?= $thiscat->description ?></p>
            </div>
            <?php if (waitig_gopt('waitig_tui')) { ?>
                <div class="tuijian">
                    重磅推荐：
                    <?= waitig_gopt('waitig_tui') ?>
                </div>
            <?php } ?>
        </div>
        <dl class="chapterlist">
            <?php if (count($cats_id_arr) == 0) {
                echo '<dt class="title">正文</dt>';
                query_posts("posts_per_page=-1&cat=" . $thiscat->term_id . "&order=ASC");
                while (have_posts()) :
                    the_post();
                    $postUrl = get_the_permalink();
                    $postTitle = get_the_title();
                    echo "<dd><a href=\"$postUrl\" title=\"$postTitle\">$postTitle</a></dd>";
                endwhile;
                wp_reset_query();
            } else {
                foreach ($cats_id_arr as $childCatId) {
                    $childCat = get_category($childCatId);
                    echo "<dt class=\"title\">$childCat->name</dt>";
                    query_posts("posts_per_page=-1&cat=" . $childCatId . "&order=ASC");
                    while (have_posts()) :
                        the_post();
                        $postUrl = get_the_permalink();
                        $postTitle = get_the_title();
                        echo "<dd><a href=\"$postUrl\" title=\"$postTitle\">$postTitle</a></dd>";
                    endwhile;
                    wp_reset_query();
                }
            } ?>
        </dl>
    </div>
</div>
