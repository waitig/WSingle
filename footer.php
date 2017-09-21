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
        <p>本站所有小说内容来源于互联网，所有章节均由网友上传，转载至本站只为宣传本小说让更多读者欣赏。</p>
        <p>如若发现小说内容有与法律抵触之处或对作品版权有质疑，请发邮件告知本站，立即予以处理</p>
        <p>Copyright &copy; <a href="<?= home_url() ?>"><?= get_bloginfo('name') ?></a> 免费小说在线阅读 Powered By <a
                    href="https://www.waitig.com" title="WSingle主题">WSingle</a></p>
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