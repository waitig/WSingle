<?php
if (waitig_gopt('waitig_Cache_on')&&(!is_admin())):

    //是否开启首页缓存
    $indexOn = waitig_gopt('waitig_Cache_index_on');
    define('INDEXON',$indexOn);

    //是否开启分类页缓存
    $cateOn = waitig_gopt('waitig_Cache_cate_on');
    define('CATEON',$cateOn);

    //请求脚本的网址
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    $scriptUrl = rtrim($http_type . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], "/");
    define('SCRIPTURL',$scriptUrl);

    //网站首页地址
    $indexUrl = get_site_url();
    define('INDEXURL',$indexUrl);

    //网站根路径
    $homePath = get_home_path();
    define('HOMEPATH',$homePath);

    //页脚备注
    $footMeta = '<!--您正在浏览的是页面是由WSingle主题' . constant('THEMEVERSION') . '版本缓存系统创建的真实HTML文件，缓存创建日期：' . date("Y-m-d H:i:s") . ' -->';
    define('FOOTMETA',$footMeta);

    define('SAFETAG','<!--THIS IS A REAL HTML , CREATED BY WSINGLE THEME.-->');

    //require_once(ABSPATH . 'wp-admin/includes/file.php');
//    /* end of config */
//
//    $cossithome = get_option('home');
//    $script_uri =
//    $home_path = get_home_path();
//
//    define('SCRIPT_URI', $script_uri);
//    define('CosSiteHome', $cossithome);
//    define('CosBlogPath', $home_path);
//    define("COSMETA", "<!--this is a real static html file created at " . date("Y-m-d H:i:s") . " by cos-html-cache " . COSVERSION . " -->");
    /**
     * 根据路径和内容创建HTML文件
     * @param $FilePath
     * @param $Content
     * 等英博客出品 https://www.waitig.com
     */
    function CreateHtmlFile($FilePath, $Content)
    {
        $FilePath = preg_replace('/[ <>\'\"\r\n\t\(\)]/', '', $FilePath);

        // if there is http:// $FilePath will return its bas path
        $dir_array = explode("/", $FilePath);

        //split the FilePath
        $max_index = count($dir_array);
        $i = 0;
        $path = $_SERVER['DOCUMENT_ROOT'] . "/";

        while ($i < $max_index) {
            $path .= "/" . $dir_array[$i];
            $path = str_replace("//", "/", $path);

            if ($dir_array[$i] == "") {
                $i++;
                continue;
            }

            if (substr_count($path, '&')) return true;
            if (substr_count($path, '?')) return true;
            if (!substr_count($path, '.htm')) {
                //if is a directory
                if (is_dir($path) && !file_exists($path)) {
                    @mkdir($path, 0777);
                    @chmod($path, 0777);
                }
            }
            $i++;
        }

        if (is_dir($path)) {
            $path = $path . "/index.html";
        }
        if (!strstr(strtolower($Content), '</html>')) return;

        //if sql error ignore...
        $fp = @fopen($path, "w+");
        if ($fp) {
            @chmod($path, 0666);
            @flock($fp, LOCK_EX);

            // write the file。
            fwrite($fp, $Content);
            @flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    function checkBuffer(){
        $needBuffer = false;
        if (substr_count($_SERVER['REQUEST_URI'], '?')){
            return false;
        }
        if (substr_count($_SERVER['REQUEST_URI'], '../')){
            return false;
        }
        //未登录
        if (strlen($_COOKIE['wordpress_logged_in_' . COOKIEHASH]) < 4) {
            //判断首页
            if(is_home()&&INDEXON){
                $needBuffer = true;
                return true;
            }
            elseif(is_category()&&CATEON){
                $needBuffer = true;
                return true;
            }
            elseif(is_single()){
                $needBuffer = true;
                return true;
            }
        }
        return false;
    }

    //判断此页面是否需要缓存
    $needBuffer = checkBuffer();
    if ($needBuffer) {
        //将输出缓冲重定向到cos_cache_ob_callback函数中
        ob_start('cos_cache_ob_callback');
        register_shutdown_function('cos_cache_shutdown_callback');
    }

    /**
     * 处理输出缓存
     * @param $buffer
     * @return mixed
     * 等英博客出品 https://www.waitig.com
     */
    function cos_cache_ob_callback($buffer)
    {
        $buffer = preg_replace('/(<\s*input[^>]+?(name=["\']author[\'"])[^>]+?value=(["\']))([^"\']+?)\3/i', '\1\3', $buffer);

        $buffer = preg_replace('/(<\s*input[^>]+?value=)([\'"])[^\'"]+\2([^>]+?name=[\'"]author[\'"])/i', '\1""\3', $buffer);

        $buffer = preg_replace('/(<\s*input[^>]+?(name=["\']url[\'"])[^>]+?value=(["\']))([^"\']+?)\3/i', '\1\3', $buffer);

        $buffer = preg_replace('/(<\s*input[^>]+?value=)([\'"])[^\'"]+\2([^>]+?name=[\'"]url[\'"])/i', '\1""\3', $buffer);

        $buffer = preg_replace('/(<\s*input[^>]+?(name=["\']email[\'"])[^>]+?value=(["\']))([^"\']+?)\3/i', '\1\3', $buffer);

        $buffer = preg_replace('/(<\s*input[^>]+?value=)([\'"])[^\'"]+\2([^>]+?name=[\'"]email[\'"])/i', '\1""\3', $buffer);

        if (!substr_count($buffer, SAFETAG)) return $buffer;
        if (substr_count($buffer, 'post_password') > 0) return $buffer;//to check if post password protected
        $wppasscookie = "wp-postpass_" . COOKIEHASH;
        if (strlen($_COOKIE[$wppasscookie]) > 0) return $buffer;//to check if post password protected



        elseif ((SCRIPTURL == INDEXURL)&&INDEXON) {// creat homepage
            $fp = @fopen(HOMEPATH . "index.html", "w+");
            if ($fp) {
                @flock($fp, LOCK_EX);
                fwrite($fp, $buffer . FOOTMETA);
                @flock($fp, LOCK_UN);
                fclose($fp);
            }
        } else
            CreateHtmlFile($_SERVER['REQUEST_URI'], $buffer . FOOTMETA);
        return $buffer;
    }

    /**
     * 获取缓存结束
     * 等英博客出品 https://www.waitig.com
     */
    function cos_cache_shutdown_callback()
    {
        ob_end_flush();
        flush();
    }

    /**
     * 根据URL删除缓存
     */
    if (!function_exists('DelCacheByUrl')) {
        function DelCacheByUrl($url)
        {
            $url = HOMEPATH . str_replace(INDEXURL, "", $url);
            $url = str_replace("//", "/", $url);
            if (file_exists($url)) {
                if (is_dir($url)) {
                    @unlink($url . "/index.html");
                    @rmdir($url);
                } else @unlink($url);
            }
        }
    }

    if (!function_exists('htmlCacheDel')) {
        /**
         * 根据文章ID删除文章缓存
         * @param $post_ID
         * @return bool
         * 等英博客出品 https://www.waitig.com
         */
        function htmlCacheDel($post_ID)
        {
            if ($post_ID == "") return true;
            $uri = get_permalink($post_ID);
            DelCacheByUrl($uri);
        }
    }

    if (!function_exists('htmlCacheDelNb')) {
        /**
         * 删除相邻的文章
         * @param $post_ID
         * @return bool
         * 等英博客出品 https://www.waitig.com
         */
        function htmlCacheDelNb($post_ID)
        {
            if ($post_ID == "") return true;

            $uri = get_permalink($post_ID);
            DelCacheByUrl($uri);
            global $wpdb;
            $postRes = $wpdb->get_results("SELECT `ID`  FROM `" . $wpdb->posts . "` WHERE post_status = 'publish'   AND   post_type='post'   AND  ID < " . $post_ID . " ORDER BY ID DESC LIMIT 0,1;");
            $uri1 = get_permalink($postRes[0]->ID);
            DelCacheByUrl($uri1);
            $postRes = $wpdb->get_results("SELECT `ID`  FROM `" . $wpdb->posts . "` WHERE post_status = 'publish'  AND   post_type='post'    AND ID > " . $post_ID . "  ORDER BY ID ASC  LIMIT 0,1;");
            if ($postRes[0]->ID != '') {
                $uri2 = get_permalink($postRes[0]->ID);
                DelCacheByUrl($uri2);
            }
        }
    }

    if (!function_exists('createIndexHTML')) {
        /**
         * 更新主页缓存
         * @param $post_ID
         * @return bool
         * 等英博客出品 https://www.waitig.com
         */
        function createIndexHTML($post_ID)
        {
            if ($post_ID == "") return true;
            //[menghao]@rename(ABSPATH."index.html",ABSPATH."index.bak");
            @rename(HOMEPATH . "index.html", HOMEPATH . "index.bak");//[menghao]
        }
    }

//    if (!function_exists("htmlCacheDel_reg_admin")) {
//        /**
//         * Add the options page in the admin menu
//         */
//        function htmlCacheDel_reg_admin()
//        {
//            if (function_exists('add_options_page')) {
//                add_options_page('html-cache-creator', 'CosHtmlCache', 'manage_options', basename(__FILE__), 'cosHtmlOption');
//                //add_options_page($page_title, $menu_title, $access_level, $file).
//            }
//        }
//    }
//
//
//    if (!function_exists("cosHtmlOption")) {
//        function cosHtmlOption()
//        {
//            do_cos_html_cache_action();
//            ?>
<!--            <div class="wrap" style="padding:10px 0 0 10px;text-align:left">-->
<!--                <form method="post" action="--><?php //echo $_SERVER["REQUEST_URI"]; ?><!--">-->
<!--                    <p>-->
<!--                        --><?php //_e("Click the button bellow to delete all the html cache files", "cosbeta"); ?><!--</p>-->
<!--                    <p>--><?php //_e("Note:this will Not  delete data from your databases", "cosbeta"); ?><!--</p>-->
<!--                    <p>--><?php //_e("If you want to rebuild all cache files, you should delete them first,and then the cache files will be built when post or page first visited", "cosbeta"); ?><!--</p>-->
<!---->
<!--                    <p><b>--><?php //_e("specify a post ID or Title to to delete the related cache file", "cosbeta"); ?><!--</b>-->
<!--                        <input type="text" id="cache_id" name="cache_id"-->
<!--                               value=""/> --><?php //_e("Leave blank if you want to delete all caches", "cosbeta"); ?><!--</p>-->
<!--                    <p><input type="submit" value="--><?php //_e("Delete Html Cache files", "cosbeta"); ?><!--"-->
<!--                              id="htmlCacheDelbt"-->
<!--                              name="htmlCacheDelbt" onClick="return checkcacheinput(); "/>-->
<!--                </form>-->
<!--            </div>-->
<!---->
<!--            <SCRIPT LANGUAGE="JavaScript">-->
<!--                <!---->
<!--                function checkcacheinput() {-->
<!--                    document.getElementById('htmlCacheDelbt').value = 'Please Wait...';-->
<!--                    return true;-->
<!--                }-->
<!---->
<!--                //-->-->
<!--            </SCRIPT>-->
<!--            --><?php
//        }
//    }
    /*
    end of get url
    */
// deal with rebuild or delete
    function do_cos_html_cache_action()
    {
        if (!empty($_POST['htmlCacheDelbt'])) {
            @rename(CosBlogPath . "index.html", CosBlogPath . "index.bak");
            @chmod(CosBlogPath . "index.bak", 0666);
            global $wpdb;
            if ($_POST['cache_id'] * 1 > 0) {
                //delete cache by id
                DelCacheByUrl(get_permalink($_POST['cache_id']));
                $msg = __('the post cache was deleted successfully: ID=', 'cosbeta') . $_POST['cache_id'];
            } else if (strlen($_POST['cache_id']) > 2) {
                $postRes = $wpdb->get_results("SELECT `ID`  FROM `" . $wpdb->posts . "` WHERE post_title LIKE '%" . $_POST['cache_id'] . "%' LIMIT 0,1 ");
                DelCacheByUrl(get_permalink($postRes[0]->ID));
                $msg = __('the post cache was deleted successfully: Title=', 'cosbeta') . $_POST['cache_id'];
            } else {
                $postRes = $wpdb->get_results("SELECT `ID`  FROM `" . $wpdb->posts . "` WHERE post_status = 'publish' AND ( post_type='post' OR  post_type='page' )  ORDER BY post_modified DESC ");
                foreach ($postRes as $post) {
                    DelCacheByUrl(get_permalink($post->ID));
                }
                $msg = __('HTML Caches were deleted successfully', 'cosbeta');
            }
        }
        if ($msg)
            echo '<div class="updated"><strong><p>' . $msg . '</p></strong></div>';
    }

    $is_add_comment_is = true;
    /*
     * with ajax comments
     */
    if (!function_exists("cos_comments_js")) {
        function cos_comments_js($postID)
        {
            global $is_add_comment_is;
            if ($is_add_comment_is) {
                $is_add_comment_is = false;
                ?>
                <script language="JavaScript" type="text/javascript"
                        src="<?php echo CosSiteHome; ?>/wp-content/plugins/cos-html-cache/common.js.php?hash=<?php echo COOKIEHASH; ?>"></script>
                <script language="JavaScript" type="text/javascript">
                    //<![CDATA[
                    var hash = "<?php echo COOKIEHASH;?>";
                    var author_cookie = "comment_author_" + hash;
                    var email_cookie = "comment_author_email_" + hash;
                    var url_cookie = "comment_author_url_" + hash;
                    var adminmail = "<?php  echo str_replace('@', '{_}', get_option('admin_email'));?>";
                    var adminurl = "<?php  echo get_option('siteurl');?>";
                    setCommForm();
                    //]]>
                </script>
                <?php
            }
        }
    }

    /**
     * 输出安全标记，防止被二次缓存
     * 等英博客出品 http://www.waitig.com
     */
    function CosSafeTag()
    {
        if (checkBuffer()) {
            echo SAFETAG;
        }
    }

    function clearCommentHistory()
    {
        global $comment_author_url, $comment_author_email, $comment_author;
        $comment_author_url = '';
        $comment_author_email = '';
        $comment_author = '';
    }

//add_action('comments_array','clearCommentHistory');
    add_action('get_footer', 'CosSafeTag');
//add_action('comment_form', 'cos_comments_js');

    /* end of ajaxcomments*/
    if (INDEXON) add_action('publish_post', 'createIndexHTML');
    add_action('publish_post', 'htmlCacheDelNb');

    if (INDEXON) add_action('delete_post', 'createIndexHTML');
    add_action('delete_post', 'htmlCacheDelNb');

//if comments add
    add_action('edit_post', 'htmlCacheDel');
    if (INDEXON) add_action('edit_post', 'createIndexHTML');

endif;
