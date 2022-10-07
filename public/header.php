<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php echo $this->options->customHTMLInHeadTop; ?>
    <?php if (strlen($this->options->shortcutIcon) > 5): ?>
        <link rel="shortcut icon" href="<?php $this->options->shortcutIcon()?>">
    <?php else: ?>
        <link rel="shortcut icon" href="<?php echo rtrim($this->options->siteUrl, "/") ?>/favicon.ico">
    <?php endif ?>
    <script src="<?php $this->options->themeUrl('lib/jquery/2.2.4/jquery.min.js'); ?>"></script>
    <link rel="stylesheet" href="https://cdn.staticfile.org/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Serif+SC:wght@300;400;600;900&display=swap">
    <!-- 自定义css -->
    <link rel="stylesheet" type="text/css" media="all" href="<?php $this->options->themeUrl('assets/css/lantern.min.css'); ?>"/>
    <link rel="stylesheet" type="text/css" media="all" href="<?php $this->options->themeUrl('assets/css/font.css'); ?>"/>
    <script>
        window.LANTERN_CONFIG = {
            THEME_URL: '<?php $this->options->themeUrl(); ?>',
            THEME_STYLE: '<?php $this->options->themeStyle(); ?>'
        }
    </script>
    <!-- prism插件 -->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('lib/prism/prism.css'); ?>"/>
    <script type="text/javascript" src="<?php $this->options->themeUrl('lib/prism/prism.js'); ?>"></script>
    <script src="//cdn.bootcss.com/headroom/0.9.1/headroom.min.js"></script>
    <link rel="stylesheet" href="https://cdn.staticfile.org/fancybox/3.5.2/jquery.fancybox.min.css">
    <script src="https://cdn.staticfile.org/fancybox/3.5.2/jquery.fancybox.min.js"></script>
    <!-- Typecho自有函数 -->
    <?php if ($this->fields->keywords || $this->fields->desc) : ?>
        <?php $this->header('keywords=' . $this->fields->keywords . '&description=' . $this->fields->desc); ?>
    <?php else : ?>
        <?php $this->header(); ?>
    <?php endif; ?>
    <title>
        <?php if ($this->_currentPage > 1) echo '第 ' . $this->_currentPage . ' 页 - '; ?>
        <?php $this->archiveTitle(
            array(
                'category' => '分类 %s 下的文章',
                'search' => '包含关键字 %s 的文章', 'tag' => '标签 %s 下的文章', 'author' => '%s 发布的文章'
            ),
            '',
            ' - '
        ); ?>
        <?php $this->options->title(); ?>
    </title>
    <?php echo $this->options->customHTMLInHeadBottom; ?>
</head>
<body id="body" class="">
<header>
	<a href="<?php $this->options->siteUrl(); ?>">
		<img class="avatar" src="<?php echo rtrim($this->options->siteUrl, "/") ?>/logo.jpg">
	</a>
    <a class="logo" href="<?php $this->options->siteUrl(); ?>">
        <?php if ($this->options->blogLogo):?>
            <img src="<?php $this->options->blogLogo(); ?>">
        <?php else:?>
            KANE BLOG
        <?php endif;?>
    </a>
    <div class="menu">
        <div  id="menu-list" class="menu-list">
            <li>
                <a href="javascript:void(0)"  onclick="showMenuList(this)" >目录</a>
                <div class="menu-more hide" id="menu-more">
                    <ul>
                        <?php $this->widget('Widget_Metas_Category_List')->to($categorys); ?>
                        <?php if ($categorys->have()): ?>
                            <?php while ($categorys->next()): ?>
                                <li>
                                    <a href="<?php $categorys->permalink(); ?>">
                                        <?php $categorys->name(); ?>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>
            <?php $pages=[];$this->widget('Widget_Contents_Page_List')->to($pages); ?>
            <?php while ($pages->next()) : ?>
            <li>
                <a href="<?php $pages->permalink(); ?>"><?php $pages->title(); ?></a>
            </li>
            <?php endwhile; ?>
            <?php if($this->options->customLink):?>
                <?php
                $customLink = trim($this->options->customLink);
                $customLink = mb_split("\n", $customLink);
                foreach ($customLink as $linkItem) {
                    $item = mb_split(":", $linkItem, 2);
                    if (count($item) !== 2) continue;
                    $itemLink = trim($item[1]);
                    $itemName = trim($item[0]);
                    echo '<li><a href="'.$itemLink.'" target="_blank">'.$itemName.'</a></li>';

                }
                ?>
            <?php endif; ?>
        </div>
    </div>
</header>
<script type="text/javascript">
    initTheme();
    function initTheme(){
        $("#body").addClass("<?php $this->options->themeColor(); ?>");
        if (localStorage.getItem("themeMode")=="dark-mode"){
            $("#body").addClass(localStorage.getItem("themeMode"));
            $("#light-bulb").removeClass("on");
            $("#light-bulb").addClass("off");
        }else if (localStorage.getItem("themeMode")=="sun-mode"){
            $("#body").addClass(localStorage.getItem("themeMode"));
            $("#light-bulb").removeClass("off");
            $("#light-bulb").addClass("on");
        }else {
            <?php if($this->options->themeMode == '0'):?>
            let n = new Date();
            if ((n.getHours()>18&&n.getHours()<24)||(n.getHours()>0&&n.getHours()<6)){
                if (!$("#body").hasClass("dark-mode")){
                    $("#body").addClass("dark-mode");
                    $("#light-bulb").removeClass("on");
                    $("#light-bulb").addClass("off");
                }
            } else {
                if ($("#body").hasClass("dark-mode")) {
                    $("#body").removeClass("dark-mode");
                    $("#light-bulb").removeClass("off");
                    $("#light-bulb").addClass("on");
                }
            }
            <?php elseif ($this->options->themeMode == '2'):?>
            if (!$("#body").hasClass("dark-mode")){
                $("#body").addClass("dark-mode");
                $("#light-bulb").removeClass("on");
                $("#light-bulb").addClass("off");
            }
            <?php endif;?>
        }
    }

    function switchThemeMode() {
        if ($("#body").hasClass("dark-mode")){
            $("#body").removeClass("dark-mode");
            $("#light-bulb").removeClass("off");
            $("#light-bulb").addClass("on");
            localStorage.setItem("themeMode","sun-mode");
        }else {
            $("#body").addClass("dark-mode");
            $("#light-bulb").removeClass("on");
            $("#light-bulb").addClass("off");
            localStorage.setItem("themeMode","dark-mode");
        }
    }

    function showMenuList(obj){
        if ($("#menu-more").hasClass("hide")){
            obj.style.color = "#cc493d";
            $("#menu-more").removeClass("hide")
        }else {
            obj.style.color = "black";
            $("#menu-more").addClass("hide")
        }
    }
</script>
</body>
</html>
