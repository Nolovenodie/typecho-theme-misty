<?php
/**
 * Template Page of talks
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<?php
function threadedComments($comments, $options){
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' comment-by-author';
        } else {
            $commentClass .= ' comment-by-user';
        }
    }

    ?>
    <li id="li-<?php $comments->theId(); ?>" class="comment-body">
        <div id="<?php $comments->theId(); ?>">
            <div class="talks-view" >
                <div class="author-container">
                    <?php if ($comments->parent==0):?>
                    <div class="time"><?php $comments->date('H:s') ?></div>
                    <div class="date"><?php $comments->date('Y.m.d') ?></div>
                    <?php else:?>
                    <img class="author-avatar" src="<?php ParseAvatar($comments->mail); ?>"/>
                    <?php endif;?>
                </div>
                <div class="talks-content-container">
                    <?php if ($comments->parent==0):?>
                        <div class="talks-content">
                            <?php echo getTalks($comments->parent, $comments->content); ?>
                        </div>
                        <div class="talks-meta">
                            <span class="show-comment" onclick="changeCommentVisibility(this)"></span>
                            <span class="reply" onclick="return TypechoComment.reply('<?php $comments->theId(); ?>', <?php $comments->coid(); ?>)"><?php $comments->reply('回复'); ?></span>
                        </div>
                    <?php else:?>
                        <div class="reply-head">
                            <?php if ($comments->url): ?>
                                <a target="_blank" href="<?php $comments->url(); ?>">
                                    <?php echo $comments->author; ?>
                                </a>
                                <span class="reply" onclick="return TypechoComment.reply('<?php $comments->theId(); ?>', <?php $comments->coid(); ?>)"><?php $comments->reply('回复'); ?></span>
                            <?php else: ?>
                                <?php echo $comments->author; ?>
                            <?php endif;?>
                        </div>
                        <div class="date">
                            <?php $comments->date('Y年m月d日 H:s') ?>
                        </div>
                        <div class="talks-content">
                            <?php echo getTalks($comments->parent, $comments->content); ?>
                        </div>
                    <?php endif;?>

                </div>
            </div>
        </div>
        <?php if ($comments->children) { ?>
            <div class="talks-children">
                <?php $comments->threadedComments($options); ?>
            </div>
        <?php } ?>
    </li>
<?php }
?>

<?php $this->need('public/header.php'); ?>
    <div class="post-container">
        <div class="content-container">
            <div class="comment-container" />
            <?php if ($this->allow('comment')): ?>
                <?php $this->comments()->to($comments); ?>
                    <?php if ($this->user->hasLogin()): ?>
                        <div class="response">
                        随便写点什么
                        <span style="font-size: 16px">
                             <?php if ($this->user->hasLogin()): ?>
                                 You are <a href="<?php $this->options->profileUrl(); ?>"
                                            data-no-instant><?php $this->user->screenName(); ?></a> here, do you want to <a
                                         href="<?php $this->options->logoutUrl(); ?>" title="Logout"
                                         data-no-instant>logout</a> ?
                             <?php endif; ?>
                        </span>
                        </div>
                    <?php endif;?>
                    <div id="comments" class="clearfix">
<!--                        --><?php //if ($this->user->hasLogin()): ?>
                        <div id="<?php $this->respondId(); ?>" class="talks-respond <?php if (!$this->user->hasLogin()){ echo 'hide';}?>" data-respondId="<?php $this->respondId() ?>">
                            <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" class="comment-form" role="form"
                                  onsubmit="getElementById('misubmit').disabled=true;return true;">
                                <?php if (!$this->user->hasLogin()): ?>
                                    <div class="comment-user-info-container">
                                        <input type="text" name="author" maxlength="12" id="author"
                                               class="form-control input-control clearfix" placeholder="Name (*)" value="<?php $this->remember('author'); ?>" required>
                                        <input type="email" name="mail" id="mail" class="form-control input-control clearfix"
                                               placeholder="Email (*)"
                                               value="<?php $this->remember('mail'); ?>" <?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?>>
                                        <input type="url" name="url" id="url" class="form-control input-control clearfix"
                                               placeholder="Site (http://)"
                                               value="<?php $this->remember('url'); ?>" <?php if ($this->options->commentsRequireURL): ?> required<?php endif; ?>>
                                    </div>
                                <?php endif; ?>
                                <textarea name="text" id="textarea" class="form-control" placeholder="请输入内容... "
                                          required><?php $this->remember('text', false); ?></textarea>
                                <?php $comments->cancelReply('取消'); ?>
                                <button type="submit" class="submit" id="misubmit">提交</button>
                                <?php $security = $this->widget('Widget_Security'); ?>
                                <input type="hidden" name="_" value="<?php echo $security->getToken($this->request->getReferer()) ?>">
                            </form>
                        </div>
<!--                        --><?php //endif;?>
                        <?php if ($comments->have()): ?>
                            <?php $comments->listComments(); ?>
                            <div style="height: 40px"></div>
                            <?php $comments->pageNav(
                                '上一页',
                                '下一页',
                                1,
                                '...',
                                array(
                                    'wrapTag' => 'ul',
                                    'wrapClass' => 'pagination-container',
                                    'itemTag' => 'li',
                                    'textTag' => 'a',
                                    'currentClass' => 'active',
                                    'prevClass' => 'prev',
                                    'nextClass' => 'next'
                                )
                            );
                            ?>
                        <?php endif; ?>
                    </div>
            <?php else : ?>
                <span class="response">评论已关闭</span>
            <?php endif; ?>
        </div>
        </div>
    </div>


<?php $this->need('public/footer.php'); ?>
<script>
(function() {
    window.TypechoComment = {
        dom : function (id) {
            return document.getElementById(id);
        },

        create : function (tag, attr) {
            var el = document.createElement(tag);

            for (var key in attr) {
                el.setAttribute(key, attr[key]);
            }

            return el;
        },

        reply : function (cid, coid) {
            var comment = this.dom(cid), parent = comment.parentNode,
                response = this.dom('<?php echo $this->respondId(); ?>'), input = this.dom('comment-parent'),
                form = 'form' == response.tagName ? response : response.getElementsByTagName('form')[0],
                textarea = response.getElementsByTagName('textarea')[0];

            if (null == input) {
                input = this.create('input', {
                    'type' : 'hidden',
                    'name' : 'parent',
                    'id'   : 'comment-parent'
                });

                form.appendChild(input);
            }

            input.setAttribute('value', coid);

            if (null == this.dom('comment-form-place-holder')) {
                var holder = this.create('div', {
                    'id' : 'comment-form-place-holder'
                });

                response.parentNode.insertBefore(holder, response);
            }

            comment.appendChild(response);
            $(response).removeClass("hide");
            this.dom('cancel-comment-reply-link').style.display = '';

            if (null != textarea && 'text' == textarea.name) {
                textarea.focus();
            }

            return false;
        },

        cancelReply : function () {
            var response = this.dom('<?php echo $this->respondId(); ?>'),
                holder = this.dom('comment-form-place-holder'), input = this.dom('comment-parent');
            <?php if (!$this->user->hasLogin()): ?>
                $(response).addClass("hide");
            <?php endif;?>
            if (null != input) {
                input.parentNode.removeChild(input);
            }

            if (null == holder) {
                return true;
            }

            this.dom('cancel-comment-reply-link').style.display = 'none';
            holder.parentNode.insertBefore(response, holder);
            return false;
        }
    };
    init_talks_comment();
    function init_talks_comment(){
        $("#comments>.comment-list>.comment-body").each(function (){
            var childDiv =  $(this).children(".talks-children");
            if (childDiv.length > 0){
                childDiv.get(0).classList.add('hide');
                var tc = childDiv.find(".talks-view");
                if (tc.length>0){
                    $(this).find(".talks-meta>.show-comment").get(0).innerText='展开评论('+tc.length+')';
                }
            }
        })

    }
})();
function changeCommentVisibility(obj){
    var text = $(obj).get(0).innerText;
    if (text.indexOf("展开")>-1){
        $(obj).get(0).innerText = text.replace("展开","收起");
    }else {
        $(obj).get(0).innerText = text.replace("收起","展开");
    }
    $(obj).closest(".comment-body").children(".talks-children").toggleClass("hide");
}
</script>
