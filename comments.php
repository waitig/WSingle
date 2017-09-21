<?php
/**
 * File: comment.php.
 * User: admin
 * Date: 2017/9/19
 * Time: 16:25
 * Index:https://www.waitig.com
 */
session_start();
$cat_id = $_SESSION['cat_id'];
$thiscat = $_SESSION['thiscat'];
$post = get_post();
?>
<div class="container">
    <div class="pinglun">
        <p class="jcpl">看网友对 <i><?= get_the_title() ?></i> 的精彩评论</p>
        <div class="comments-template">
            <!-- You can start editing here. -->
            <h3 id="comments">
                <span><?= $post->comment_count ?> 条评论</span>
            </h3>
                <?php
                function mytheme_comment($comment, $args, $depth)
                {
                    global $commentNum;
                    ?>
                    <li <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?>
                            id="comment-<?=$commentNum ?>">
                        <div class="author_box">
                            <div class="t" style="display:none;" id="comment-<?=$commentNum ?>"></div>
                            <div class="comment-author">
                                <span class="floor">&nbsp;<?=$commentNum ?>楼<sup>#</sup></span>
                                <strong><?php printf(__('<cite class="fn">%s</cite> <span class="says">发表:</span>'), get_comment_author_link()); ?></strong> :
                                <span class="datetime"><?php
                                    /* translators: 1: date, 2: time */
                                    printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()); ?></span>
                            </div>
                            <p><?php comment_text(); ?></p>
                        </div>
                    </li>
                <?php
                    $commentNum ++;
                }
                ?>
                <ol class="commentlist">
                    <?php
                    global $commentNum;
                    $commentNum = 1;
                    wp_list_comments('type=comment&post_id='.$post->ID.'&callback=mytheme_comment'); ?>
                </ol>
            <?php
            $args = array(
                'id_form' => 'commentform',
                'id_submit' => '提交评论',
                'title_reply' => __(''),
                'title_reply_to' => __('对 %s 的回复'),
                'cancel_reply_link' => __('撤销回复'),
                'label_submit' => __('文章评论'),
                'comment_field' => '<p><label for="comment">' . _x('Comment', 'noun') . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
                'must_log_in' => '<p>' . sprintf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url(apply_filters('the_permalink', get_permalink()))) . '</p>',
                'logged_in_as' => '<p>' . sprintf(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink()))) . '</p>',
                'comment_notes_before' => '<p class="comment-notes">' . __( '您的评论经过审核后才会发表，请不要重复提交.').'</p>',
                'comment_notes_after' => '',
                'fields' => apply_filters('comment_form_default_fields', array(
                    'author' => '<p>' . '<label for="author">' . __('昵称', 'domainreference') . '</label> ' . '<span>*</span>' . '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>',
                )));
            comment_form($args);
            ?>
        </div>
    </div>
</div>