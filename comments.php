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
                <?php function mytheme_comment($comment, $args, $depth)
                {
                    var_dump($depth);
                    var_dump($comment);
                    var_dump($args);
                    ?>
                    <li <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?>
                            id="comment-<?php comment_ID() ?>">
                        <div class="author_box">
                            <div class="t" style="display:none;" id="comment-<?php comment_ID() ?>"></div>
                            <div class="comment-author">
                                <span class="floor">&nbsp;<?php comment_ID() ?>楼<sup>#</sup></span>
                                <strong><?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()); ?></strong> :
                                <span class="datetime"><?php
                                    /* translators: 1: date, 2: time */
                                    printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()); ?></span>
                            </div>
                            <p><?php comment_text(); ?></p>
                        </div>
                    </li>
                <?php } ?>
                <ol class="commentlist">
                    <?php wp_list_comments('type=comment&callback=mytheme_comment'); ?>
                </ol>
            <ol class="commentlist">
                <li class="comment even thread-even depth-1" id="comment-3">
                    <div id="div-comment-3" class="comment-body">
                        <div class="author_box">
                            <div class="t" style="display:none;" id="comment-3"></div>
                            <div class="comment-author">
                                <span class="floor">&nbsp;沙发<sup>#</sup></span>
                                <strong>一个套路……</strong> :
                                <span class="datetime">
												2017年09月14日				</span>
                                <span class="reply"><a rel='nofollow' class='comment-reply-link'
                                                       href='/book/11.html?replytocom=3#respond'
                                                       onclick='return addComment.moveForm( "div-comment-3", "3", "respond", "11" )'
                                                       aria-label='回复给一个套路……'>回复</a></span>
                            </div>
                            <p>????啊啊啊</p>
                        </div>
                    </div>
                </li>
            </ol>
            <ol>
                <?php
                $args = array(
                    'max_depth' => 3,
                    'style' => 'ol',
                    'reply_text' => '吐槽',
                    'avatar_size' => 36,
                );
                wp_list_comments($args);
                ?>
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
                'comment_notes_after' => '',
                'fields' => apply_filters('comment_form_default_fields', array(
                    'author' => '<p>' . '<label for="author">' . __('昵称', 'domainreference') . '</label> ' . '<span>*</span>' . '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' /></p>',
                )));
            comment_form();
            ?>
        </div>
    </div>
</div>