<?php
/**
 * Created by PhpStorm.
 * User: wangst
 * Date: 2021/3/3
 * Time: 9:41
 */
define("THEME_URL", str_replace('//usr', '/usr', str_replace(Helper::options()->siteUrl, Helper::options()->rootUrl . '/', Helper::options()->themeUrl)));
$str1 = explode('/themes/', (THEME_URL . '/'));
$str2 = explode('/', $str1[1]);
define("THEME_NAME", $str2[0]);

function ParseAvatar($mail, $re = 0, $id = 0){
    $a = "gravatar.helingqi.com/wavatar";
    $b = 'https://' . $a . '/';
    $c = strtolower($mail);
    $d = md5($c);
    $f = str_replace('@qq.com', '', $c);
    if (strstr($c, "qq.com") && is_numeric($f) && strlen($f) < 11 && strlen($f) > 4) {
        $g = '//thirdqq.qlogo.cn/g?b=qq&nk=' . $f . '&s=100';
        if ($id > 0) {
            $g = Helper::options()->rootUrl . '?id=' . $id . '" data-type="qqtx';
        }
    } else {
        $g = $b . $d . '?d=mm';
    }
    if ($re == 1) {
        return $g;
    } else {
        echo $g;
    }
}

function GetReply($parent,$content){
    if ($parent == 0) {
        return $content;
    }
    $db = Typecho_Db::get();
    $commentInfo = $db->fetchRow($db->select('author,status,mail')->from('table.comments')->where('coid = ?', $parent));
    $link = '<span>@' . $commentInfo['author'] .  '  </span>';
    $content = substr($content,3, strlen($content)-7);
    return '<p>'.$link.$content.'</p>';
}

function getTalks($parent,$content){
    $pattern = '/<p><img/i';
    $replacement = '<p class="img-paragraph"><img';
    $content = preg_replace($pattern, $replacement, $content);

    $pattern = '/\<img.*?src\=\"(.*?)\"[^>]*>/i';
    $replacement = '<a href="$1" data-fancybox="gallery" /><img src="$1"  title="点击放大图片"></a>';
    $content = preg_replace($pattern, $replacement, $content);
    if ($parent == 0) {
        return $content;
    }
    $db = Typecho_Db::get();
    $commentInfo = $db->fetchRow($db->select('author,status,mail')->from('table.comments')->where('coid = ?', $parent));
    $link = '<span>@' . $commentInfo['author'] .  '  </span>';
    if (strpos($content, "img-paragraph")==10){
        $content = substr($content,25, strlen($content)-29);
        $content = '<p class="img-paragraph">'.$link.$content.'</p>';
    }else{
        $content = substr($content,3, strlen($content)-7);
        $content = '<p>'.$link.$content.'</p>';
    }
    return $content;
}

function cdnSuffix($cdnType){
    if ($cdnType == "UPYUN") {
        echo "!/fw/640/quality/85";
    } elseif ($cdnType == "OSS") {
        echo "?x-oss-process=image/resize,w_640/quality,q_85";
    } elseif ($cdnType == "KODO") {
        echo "?imageView2/2/w/640/q/85";
    } elseif ($cdnType == "COS") {
        echo "?imageView2/2/w/640/q/85";
    } else
        echo "";
}


function getThumb($archive, $options){
    if ($archive->fields->bannerUrl){
        return $archive->fields->bannerUrl;
    } else {
        return loadThumb($archive->content, $options);
    }
}

function loadThumb($content, $options){
    preg_match_all("/\<img.*?src\=\"(.*?)\"[^>]*>/i", $content, $thumbUrl);
    if (count($thumbUrl) > 0 && count($thumbUrl[0]) > 0) {
        $bg =  $thumbUrl[1][0];
    } else {
        $bg = "";
    }
    return $bg;
}

function getIconByType($type){
    if ($type == "weixin"){
        return "&#xe660;";
    }else if ($type == "weibo"){
        return "&#xe7e4;";
    }else if ($type == "bilibili"){
        return "&#xe646;";
    }else if ($type == "github"){
        return "&#xe64c;";
    }else if ($type == "zhihu"){
        return "&#xea8b;";
    }else if ($type == "qq"){
        return "&#xe666;";
    }else if ($type == "facebook"){
        return "&#xeb8d;";
    }else if ($type == "twitter"){
        return "&#xe630;";
    }else if ($type == "ins"){
        return "&#xe639;";
    }else if ($type == "reward"){
        return "&#xe693;";
    } else {
        return $type;
    }
}

function theNext($widget){
    $db = Typecho_Db::get();
    $sql = $db->select()->from('table.contents')
        ->where('table.contents.created > ?', $widget->created)
        ->where('table.contents.status = ?', 'publish')
        ->where('table.contents.type = ?', $widget->type)
        ->where('table.contents.password IS NULL')
        ->order('table.contents.created', Typecho_Db::SORT_ASC)
        ->limit(1);
    $content = $db->fetchRow($sql);
    if ($content) {
        $content = $widget->filter($content);
        $link = '<a class="iconfont" href="' . $content['permalink'] . '" title="' . $content['title'] . '">下一篇&#xe60d;</a>';
        echo $link;
    } else {
        echo null;
    }
}

function thePrev($widget){
    $db = Typecho_Db::get();
    $sql = $db->select()->from('table.contents')
        ->where('table.contents.created < ?', $widget->created)
        ->where('table.contents.status = ?', 'publish')
        ->where('table.contents.type = ?', $widget->type)
        ->where('table.contents.password IS NULL')
        ->order('table.contents.created', Typecho_Db::SORT_DESC)
        ->limit(1);
    $content = $db->fetchRow($sql);
    if ($content) {
        $content = $widget->filter($content);
        $link = '<a class="iconfont" href="' . $content['permalink'] . '" title="' . $content['title'] . '">&#xe60b;上一篇</a>';
        echo $link;
    } else {
        echo null;
    }
}


function cdnSuffixPhoto($cdnType){
    if ($cdnType == "UPYUN") {
        echo "!/fw/640/quality/85";
    } elseif ($cdnType == "OSS") {
        echo "?x-oss-process=image/resize,w_640/quality,q_85";
    } elseif ($cdnType == "KODO") {
        echo "?imageView2/2/w/640/q/85";
    } elseif ($cdnType == "COS") {
        echo "?imageView2/2/w/640/q/85";
    } else
        echo "";
}

function excerpt($archive) {
        if (false !== strpos($archive->text, '<!--more-->')) {
            echo $archive->excerpt;
            return ;
        }
        $index = strpos($archive->content, '</p>');
        if ($index === false) {
            echo $archive->content;
        } else {
            echo substr($archive->content, 0, $index + 4);
        }
    }


?>

