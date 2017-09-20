<?php
/**
 * Created by PhpStorm.
 * User: lius
 * Date: 2017/2/20
 * Time: 19:50
 */
get_header();
session_start();
//$current_category = get_the_category();//获取当前文章所属分类
//$category = $current_category[0];
$cat_id = $_SESSION['cat_id'];
$this_cat = $_SESSION['thiscat'];
$prev_post = get_previous_post($this_cat, '');//与当前文章同分类的上一篇文章
$next_post = get_next_post($this_cat, '');//与当前文章同分类的下一篇文章
$prev_link = get_permalink($prev_post->ID);
$next_link = get_permalink($next_post->ID);
$catName = $this_cat->name;
$blogUrl = get_bloginfo('url');
$blogName = get_bloginfo('name');
$catLink = get_category_link($cat_id);
$postName = get_the_title();
$waitig_post_bottom_tui = waitig_gopt('waitig_post_bottom_tui');
?>
<div class="crumbs">
    <div class="fl"><span>当前位置：</span>
        <a href="<?= $blogUrl ?>" title="<?= $blogName ?>"><?= $blogName ?></a> &gt;
        <a href="<?= $catLink ?>" title="<?= $catName ?>"><?= $catName ?></a> &gt;
        <?= $postName ?>
    </div>
</div>
<div class="container">
    <div class="bookset">
        <script>if (system.win || system.mac || system.xll) {
                bookset();
            }
        </script>
    </div>
    <div class="article" id="main">
        <div class="inner" id="BookCon">
            <h1><?= $postName ?></h1>
            <div class="link xb">
                <a href="<?= $prev_link ?>" rel="prev">上一章</a>←
                <a href="<?= $catLink ?>">返回列表</a>→
                <a href="<?= $next_link ?>" rel="next">下一章</a>
            </div>
            <div class="ads">
                <div class="adleft">
                    <?= waitig_gopt('waitig_ad_post_left') ?>
                </div>
                <div class="adright">
                    <?= waitig_gopt('waitig_ad_post_right') ?>
                </div>
            </div>
            <div>
                <?= waitig_gopt('waitig_ad_post_top') ?>
            </div>
            <div id="BookText" style="">
                <?php while (have_posts()) :
                    the_post(); ?>
                    <p>一秒记住本站域名【<a href="<?= $blogUrl ?>" target="_blank" title="<?= $blogName ?>">
                            <?= $blogUrl ?>
                        </a>】，
                        为您提供 <a href="<?= $catLink ?>" target="_blank" title="<?= $catName ?>">
                            <?= $catName ?>
                        </a>小说最新章节阅读！
                    </p>
                    <?php the_content();
                endwhile; ?>
                <h4>推荐阅读：<?= $waitig_post_bottom_tui ?></h4>
            </div>
            <div>
                <?= waitig_gopt('waitig_ad_post_bottom') ?>
            </div>
            <div class="link">
                <a href="<?= $prev_link ?>" rel="prev">上一章</a>←
                <a href="<?= $catLink ?>">返回列表</a>→
                <a href="<?= $next_link ?>" rel="next">下一章</a>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var next_page = "<?=$next_link?>";
    var back_page = "<?=$prev_link?>";
    document.onkeydown = function (evt) {
        var e = window.event || evt;
        if (e.keyCode == 37) location.href = back_page;
        if (e.keyCode == 39) location.href = next_page;
    };
    if (document.all) {
        window.attachEvent('onload', LoadReadSet);
    } else {
        window.addEventListener('load', LoadReadSet, false);
    }
</script>
<?php if (comments_open() || get_comments_number()) :
    comments_template();
endif;
get_footer(); ?>
