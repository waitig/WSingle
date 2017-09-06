<?php
/**
 * Created by PhpStorm.
 * User: lius
 * Date: 2017/2/21
 * Time: 22:53
 */
if (waitig_gopt('waitig_flink')) {
    ?>
    <div class="clear"></div>
    <div class="container">
        <div class="inner links">
            <div class="title">友情链接</div>
            <ul class="link">
                <?= waitig_gopt('waitig_flink') ?>
            </ul>
        </div>
    </div>
<?php }