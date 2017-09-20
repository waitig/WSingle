<?php
/**
 * Created by PhpStorm.
 * User: lius
 * Date: 2017/2/20
 * Time: 19:49
 */
$waitig_foot_code = waitig_gopt('waitig_foot_code');
?>
<div id="footer">
    <div class="hd">
        Copyright &copy; <a href="<?= home_url() ?>"><?= get_bloginfo('name') ?></a> 免费小说在线阅读 Powered By <a
                href="https://www.waitig.com" title="WSingle主题">WSingle主题</a>
    </div>
</div>
<script type="text/javascript" src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
<script>backtotop();</script>
<?= waitig_gopt('waitig_tongji_code') ?>
<?= waitig_gopt('waitig_baidu_tui_code') ?>
<?= $waitig_foot_code ?>
<!--Powered by WSingle theme(https://www.waitig.com),QQ:504508065 -->
</body>
</html>