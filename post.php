<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('public/header.php'); ?>
<div class="post-container">
    <div class="content-container">
        <h1 class="post-title">
            <?php $this->title(); ?>
        </h1>
        <div class="post-meta">
            <?php $this->date('Y-m-d') ?>
            <?php if($this->is('post')):?>
                <?php  $this->category(',');?>
            <?php endif;?>
            <?php if($this->user->hasLogin()):?>
                <?php if ($this->is('page')):?>
                    <?php echo "<a href=\"", $this->options->adminUrl, "write-page.php?cid={$this->cid}\" target=\"_blank\">"; echo '编辑'; echo "</a>";?>
                <?php else:?>
                    <?php echo "<a href=\"", $this->options->adminUrl, "write-post.php?cid={$this->cid}\" target=\"_blank\">"; echo '编辑'; echo "</a>";?>
                <?php endif;?>
            <?php endif;?>
            <?php if(intval($this->viewsNum) > 0):?>
                • <?php echo '阅读: '.$this->viewsNum?>
            <?php endif;?>
        </div>
        <div id="post-content" class="post-content line-numbers">
            <?php if($this->is('post')):?>
                <?php
                $pattern = '/\<img.*?src\=\"(.*?)\"[^>]*>/i';
                $replacement = '<a href="$1" data-fancybox="gallery" /><img src="$1" alt="'.$this->title.'" title="点击放大图片"></a>';
                $content = preg_replace($pattern, $replacement, $this->content);
                echo $content;
                ?>
            <?php else:?>
                <?php $this->content();?>
            <?php endif; ?>
        </div>
        <?php if ($this->tags): ?>
            <div class="tags-container">
                <?php $this->tags('', true, ''); ?>
            </div>
        <?php endif;?>
        <?php if($this->is('post')):?>
            <div class="recommend-container">
                <?php thePrev($this); ?> <?php theNext($this); ?>
            </div>
            <?php if($this->options->showCopyright):?>
                <div class="post-copyright">
                    <div><div>版权属于: </div><div><a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?> </a>的博客</div></div>
                    <div><div>本文链接: </div><div><?php $this->permalink();?></div></div>
                    <div><div>作品采用: </div><div>本作品采用<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.zh" target="_blank">知识共享署名-非商业性使用-相同方式共享 4.0 国际许可协议</a>进行许可</div></div>
                </div>
            <?php endif;?>
            <?php if($this->options->writerIntro):?>
                <div class="article-writer">
                    <img src="<?php parseAvatar($this->author->mail)?>">
                    <div class="right">
                        <div class="intro">
                            <span class="name"><a href="<?php $this->author->permalink()?>"><?php $this->author() ?> </a></span>
                            <span class="sign">
                            <?php if($this->options->selfIntro):?>
                                <?php $this->options->selfIntro()?>
                            <?php else:?>
                                这个人很懒，什么也没有留下
                            <?php endif;?>
                        </span>
                        </div>
                        <div class="social-link">
                            <li><a href="mailto:<?php $this->author->mail()?>" class="iconfont">&#xe601;</a></li>
                            <?php if($this->options->socialLink):?>
                                <?php
                                $socialLink = trim($this->options->socialLink);
                                $socialLink = mb_split("\n", $socialLink);
                                foreach ($socialLink as $linkItem) {
                                    $item = mb_split(":", $linkItem, 3);
                                    if (count($item) !== 3) continue;
                                    $itemLink = trim($item[2]);
                                    $itemType = trim($item[1]);
                                    $itemName = trim($item[0]);
                                    if ($itemType  == 'qr'){
                                        echo '<li><a href="javascript:;" class="iconfont" onclick="popup(this,\''.$itemLink.'\')">'.getIconByType($itemName).'</a></li>';
                                    }else if ($itemType  == 'url'){
                                        echo '<li><a href="'.$itemLink.'" target="_blank" class="iconfont">'.getIconByType($itemName).'</a></li>';
                                    }
                                }
                                ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif;?>
        <?php endif;?>
        <?php $this->need('public/comments.php'); ?>
    </div>
    <?php if ($this->fields->directoryStatus === 'on') : ?>
        <div class="catalog-container">
            <div class="catalog-directory" id="catalog-directory">
            </div>
        </div>
        <script type="text/javascript">
            !function (callbackfn, thisArg) {
                var postChildren = function children(childNodes, reg) {
                        var result = [],
                            isReg = typeof reg === 'object',
                            isStr = typeof reg === 'string',
                            node, i, len;
                        for (i = 0, len = childNodes.length; i < len; i++) {
                            node = childNodes[i];
                            if ((node.nodeType === 1 || node.nodeType === 9) &&
                                (!reg ||
                                    isReg && reg.test(node.tagName.toLowerCase()) ||
                                    isStr && node.tagName.toLowerCase() === reg)) {
                                result.push(node);
                            }
                        }
                        return result;
                    },
                    createPostDirectory = function (article, directory, isDirNum) {
                        var contentArr = [],
                            titleId = [],
                            levelArr, root, level,
                            currentList, list, li, link, i, len;
                        levelArr = (function (article, contentArr, titleId) {
                            var titleElem = postChildren(article.childNodes, /^h\d$/),
                                levelArr = [],
                                lastNum = 1,
                                lastRevNum = 1,
                                count = 0,
                                guid = 1,
                                id = 'menu-index-',
                                lastRevNum, num, elem;
                            $(window).scroll(function() {
                                var titles = postChildren(document.getElementById('post-content').childNodes, /^h\d$/)
                                var iTop = document.documentElement.scrollTop || document.body.scrollTop;
                                var i=0;
                                titles.forEach(function (ele,idx){
                                    if (iTop+5 > $(ele).offset().top) {
                                        var href = "#"+ele.id;
                                        var aele = $('a[href^="'+href+'"]').get(0);
                                        var display = $(aele).parent().parent().css("display");
                                        if ( display !='none'){
                                            $(".catalog-directory a").removeClass('current');
                                            $(".catalog-directory a").eq(i).addClass('current');
                                        }
                                        i++;
                                    }
                                })
                                if (iTop>180 && iTop<(180+$('#post-content').outerHeight(true))){
                                    $("#catalog-directory").css("opacity", 1);
                                }else {
                                    $("#catalog-directory").css("opacity", 0);
                                }
                            })
                            while (titleElem.length) {
                                elem = titleElem.shift();
                                contentArr.push(elem.innerHTML);
                                num = +elem.tagName.match(/\d/)[0];
                                if (num > lastNum) {
                                    levelArr.push(1);
                                    lastRevNum += 1;
                                } else if (num === lastRevNum ||
                                    num > lastRevNum && num <= lastNum) {
                                    levelArr.push(0);
                                    lastRevNum = lastRevNum;
                                } else if (num < lastRevNum) {
                                    levelArr.push(num - lastRevNum);
                                    lastRevNum = num;
                                }
                                count += levelArr[levelArr.length - 1];
                                lastNum = num;
                                elem.id = elem.id || (id + guid++);
                                titleId.push(elem.id);
                            }
                            if (count !== 0 && levelArr[0] === 1) levelArr[0] = 0;
                            return levelArr;
                        })(article, contentArr, titleId);

                        currentList = root = document.createElement('ul');
                        dirNum = [0];
                        for (i = 0, len = levelArr.length; i < len; i++) {
                            level = levelArr[i];
                            if (level === 1) {
                                list = document.createElement('ul');
                                if (!currentList.lastElementChild) {
                                    currentList.appendChild(document.createElement('li'));
                                }
                                currentList.lastElementChild.appendChild(list);
                                currentList = list;
                                dirNum.push(0);
                            } else if (level < 0) {
                                level *= 2;
                                while (level++) {
                                    if (level % 2) dirNum.pop();
                                    currentList = currentList.parentNode;
                                }
                            }
                            dirNum[dirNum.length - 1]++;
                            li = document.createElement('li');
                            link = document.createElement('a');
                            link.href = '#' + titleId[i];
                            link.innerHTML = contentArr[i];
                            li.appendChild(link);
                            currentList.appendChild(li);
                        }
                        directory.appendChild(root);
                        var objHeight = directory.offsetHeight;
                        $(directory).css("top",(window.screen.availHeight-70-objHeight)/3);
                    };
                createPostDirectory(document.getElementById('post-content'), document.getElementById('catalog-directory'), true);

            }();

        </script>
    <?php endif;?>
</div>
<script>

    function popup(obj,url){
        var children = obj.parentNode.childNodes;
        var n = children.length;
        debugger;
        if (n==1){
            obj.style.color = '#cc493d';
            $(obj).after('<div class="social-pop"><img src="'+url+'"></div>');
        }else {
            obj.style.color = 'black';
            obj.parentNode.removeChild(children[1]);
        }
    }
</script>
<?php $this->need('public/footer.php'); ?>
