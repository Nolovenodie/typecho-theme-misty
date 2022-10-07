<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;


require_once("lib/core.php");

function themeConfig($form) {
    $blogLogo = new Typecho_Widget_Helper_Form_Element_Text('blogLogo', NULL, NULL, _t('博客logo'), _t('填写一个url地址。注意图片将以原尺寸大小展示在首页'));
    $form->addInput($blogLogo);
    $shortcutIcon  = new Typecho_Widget_Helper_Form_Element_Text('shortcutIcon', NULL, NULL, 'favicon地址', '');
    $form->addInput($shortcutIcon);
    $recordNum = new Typecho_Widget_Helper_Form_Element_Text('recordNum', null, null, '备案号', '如有备案号，请填写在这里');
    $form->addInput($recordNum);
    $cIdRecommend = new Typecho_Widget_Helper_Form_Element_Text('cIdRecommend', NULL, NULL, '置顶', '填写置顶文章id，用||分隔开，如：3||4');
    $form->addInput($cIdRecommend);
    $customLink = new Typecho_Widget_Helper_Form_Element_Textarea('customLink', NULL, NULL, '自定义链接', '一行一个。填写示例： 百度:https://www.baidu.com');
    $form->addInput($customLink);

    $themeStyle  = new Typecho_Widget_Helper_Form_Element_Radio('themeStyle',  array('0' => '三栏','1' => '单栏'), '0', _t('首页样式'), '');
    $form->addInput($themeStyle);
    $thumbFilter  = new Typecho_Widget_Helper_Form_Element_Radio('thumbFilter',  array('0' => '灰白','1' => '彩色'), '0', _t('缩略图滤镜'), '在三栏样式下，缩略图是否加灰白滤镜');
    $form->addInput($thumbFilter);
    $cdnType  = new Typecho_Widget_Helper_Form_Element_Radio('cdnType',  array('OSS' => '阿里云oss','UPYUN' => '又拍云', 'KODO' => '七牛云','COS' => '腾讯云'), 'OSS',_t('加速类型') , _t('使用相册模版时可选，用于生成相册缩略图'));
    $form->addItem($cdnType);
    $showCopyright  = new Typecho_Widget_Helper_Form_Element_Radio('showCopyright',  array( true => '是' , false => '否'), 'true', _t('版权声明') , _t('在文章结尾处显示作'));
    $form->addInput($showCopyright);

    $writerIntro  = new Typecho_Widget_Helper_Form_Element_Radio('writerIntro',  array( true => '是' , false => '否'), 'true', _t('作者简介') , _t('在文章结尾处显示作者简介'));
    $form->addInput($writerIntro);
    $selfIntro = new Typecho_Widget_Helper_Form_Element_Text('selfIntro', NULL, NULL, _t('一句话自我介绍'), _t('展示在文章页作者介绍处'));
    $form->addInput($selfIntro);
    $socialLink = new Typecho_Widget_Helper_Form_Element_Textarea('socialLink', NULL, NULL, _t('社交媒体链接'), _t('一行一个，填写格式请到主题作者博客查看。展示在文章页作者介绍处'));
    $form->addInput($socialLink);

    $customHTMLInHeadTop = new Typecho_Widget_Helper_Form_Element_Textarea('customHTMLInHeadTop', NULL, NULL, _t('自定义 HTML 元素拓展 -  head 头部 (meta 元素后)'), _t('在 head 标签头部(meta 元素后)添加你自己的 HTML 元素'));
    $form->addInput($customHTMLInHeadTop);
    $customHTMLInHeadBottom = new Typecho_Widget_Helper_Form_Element_Textarea('customHTMLInHeadBottom', NULL, NULL, _t('自定义 HTML 元素拓展 -  head 尾部 (head 标签结束前)'), _t('在 head 尾部 (head 标签结束前)添加你自己的 HTML 元素'));
    $form->addInput($customHTMLInHeadBottom);
    $beforeBodyClose = new Typecho_Widget_Helper_Form_Element_Textarea('beforeBodyClose', NULL, NULL, _t('自定义 HTML 元素拓展 - 在 body 标签结束前'), _t('在 body 标签结束前添加你自己的 HTML 元素'));
    $form->addInput($beforeBodyClose);

}


function themeFields($layout) {
    $directoryStatus  = new Typecho_Widget_Helper_Form_Element_Select('directoryStatus',  array('off' => '关闭（默认）', 'on' => '开启'), "on", _t('是否开启文章目录树'), _t('介绍：开启后，文章页面和自定义页面将显示目录树（小屏幕上不会显示）'));
    $layout->addItem($directoryStatus);
    $bannerStatus = new Typecho_Widget_Helper_Form_Element_Select('bannerStatus',  array('on' => '开启（默认）', 'off' => '关闭'), "on", _t('是否显示首页缩略图'), _t('开启后，在三栏样式下将显示配置的缩略图或者文章的第一张图片（都没有则不显示）。'));
    $layout->addItem($bannerStatus);
    $bannerUrl = new Typecho_Widget_Helper_Form_Element_Text('bannerUrl', NULL, NULL, _t('首页缩略图'), _t('在这里填入一个图片URL地址，以便在三栏样式下显示'));
    $layout->addItem($bannerUrl);

}

function parseContent($obj){
    $options = Typecho_Widget::widget('Widget_Options');
    if(!empty($options->src_add) && !empty($options->cdn_add)){
        $obj->content = str_ireplace($options->src_add,$options->cdn_add,$obj->content);
    }
    $obj->content = preg_replace("/<a href=\"([^\"]*)\">/i", "<a href=\"\\1\" target=\"_blank\">", $obj->content);
    echo trim($obj->content);
}

function getCommentAt($coid){
    $db   = Typecho_Db::get();
    $prow = $db->fetchRow($db->select('parent')
        ->from('table.comments')
        ->where('coid = ? AND status = ?', $coid, 'approved'));
    $parent = $prow['parent'];
    if ($parent != "0") {
        $arow = $db->fetchRow($db->select('author')
            ->from('table.comments')
            ->where('coid = ? AND status = ?', $parent, 'approved'));
        $author = $arow['author'];
        $href = '<a href="#comment-'.$parent.'">@'.$author.'</a>&nbsp;';
        return $href;
    } else {
        return '';
    }
}

function parseComment($coid, $content){
    $author = getCommentAt($coid);
    $content = substr($content, 3, strlen($content)-7);
    echo '<p>'.$author.$content.'</p>';
}




