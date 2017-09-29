<?php
if (waitig_gopt('waitig_Cache_on')):

    //引入文件
    require_once(ABSPATH . 'wp-admin/includes/file.php');

    //是否开启首页缓存
    $indexOn = waitig_gopt('waitig_Cache_index_on');
    define('INDEXON', $indexOn);

    //是否开启分类页缓存
    $cateOn = waitig_gopt('waitig_Cache_cate_on');
    define('CATEON', $cateOn);

    //请求脚本的网址
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    $scriptUrl = rtrim($http_type . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], "/");
    define('SCRIPTURL', $scriptUrl);

    //网站首页地址
    $indexUrl = get_site_url();
    define('INDEXURL', $indexUrl);

    //网站根路径
    $homePath = get_home_path();
    define('HOMEPATH', $homePath);

    //页脚备注
    $footMeta = '<!--您正在浏览的页面是由WSingle主题' . constant('THEMEVERSION') . '版本缓存系统创建的真实HTML文件，缓存创建日期：' . date("Y-m-d H:i:s") . ' -->';
    define('FOOTMETA', $footMeta);

    define('SAFETAG', '<!--THIS IS A REAL HTML , CREATED BY WSINGLE THEME.-->');

    /**
     * 根据路径和内容创建HTML文件
     * @param $FilePath
     * @param $Content
     * 等英博客出品 https://www.waitig.com
     */
    function CreateHtmlFile($FilePath, $Content)
    {
        waitig_logs('开始');
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
            waitig_logs($path . '--' . $i . '--');
            if ($dir_array[$i] == "") {
                $i++;
                continue;
            }

            if (substr_count($path, '&')) return true;
            if (substr_count($path, '?')) return true;
            if (!substr_count($path, '.htm')) {
                //if is a directory
                if (!file_exists($path)) {
                    waitig_logs('是目录');
                    mkdir($path, 0777);
                    chmod($path, 0777);
                }
            }
            $i++;
        }

        waitig_logs($path);
        waitig_logs(is_dir($path));

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
        waitig_logs('结束');
    }

    function checkBuffer()
    {
        $needBuffer = false;
        if (substr_count($_SERVER['REQUEST_URI'], '?')) {
            return false;
        }
        if (substr_count($_SERVER['REQUEST_URI'], '../')) {
            return false;
        }
        //未登录
        if (strlen($_COOKIE['wordpress_logged_in_' . COOKIEHASH]) < 4) {
            //判断首页
            if (is_home() && INDEXON) {
                $needBuffer = true;
                return true;
            } elseif (is_category() && CATEON) {
                $needBuffer = true;
                return true;
            } elseif (is_single()) {
                $needBuffer = true;
                return true;
            }
        }
        return false;
    }

    //缓存钩子函数
    function createHtml()
    {
        var_dump('heeeeeeeeeeeeeeeeeee');
        $needBuffer = checkBuffer();
        var_dump($needBuffer);
        if ($needBuffer) {
            //将输出缓冲重定向到cos_cache_ob_callback函数中
            ob_start('cos_cache_ob_callback');
            register_shutdown_function('cos_cache_shutdown_callback');
        }
    }

    add_action('get_header', 'createHtml');


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


        elseif ((SCRIPTURL == INDEXURL) && INDEXON) {// creat homepage
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
            waitig_logs('删除首页缓存');
            if ($post_ID == "") return true;
            DelCacheByUrl(INDEXURL);
        }
    }

    if (!function_exists('createCateHTML')) {
        /**
         * 更新小说页缓存
         * @param $post_ID
         * @return bool
         * 等英博客出品 https://www.waitig.com
         */
        function createCateHTML($post_ID)
        {
            waitig_logs($post_ID);
            if ($post_ID == "") return true;
            $categroy = get_the_category($post_ID);
            $cateLink = get_category_link($categroy->term_id);
            DelCacheByUrl($cateLink);
        }
    }


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

    add_action('get_footer', 'CosSafeTag');
    add_action('publish_post', 'htmlCacheDelNb');
    if (INDEXON){
        waitig_logs('新增首页更新钩子');
        add_action('publish_post', 'createIndexHTML');
        add_action('delete_post', 'createIndexHTML');
        add_action('edit_post', 'createIndexHTML');
    }

    if (CATEON){
        waitig_logs('新增分类页更新钩子');
        add_action('publish_post', 'createCateHTML');
        add_action('delete_post', 'createCateHTML');
        add_action('edit_post', 'createCateHTML');
    }
    add_action('delete_post', 'htmlCacheDelNb');
    add_action('edit_post', 'htmlCacheDel');

endif;
