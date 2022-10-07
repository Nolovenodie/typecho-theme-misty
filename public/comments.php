<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<?php
function threadedComments($comments, $options)
{
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
            <div class="comment-view" >
                <div>
                    <img class="comment-avatar" src="<?php ParseAvatar($comments->mail); ?>" />
                </div>
                <div style="display: flex; flex-wrap: wrap;">
                    <div style="width: 100%; margin-top: 10px">
                        <span class="comment-author <?php echo $commentClass; ?>">
                            <?php if ($comments->url): ?>
                                <a target="_blank" href="<?php $comments->url(); ?>">
                                <?php echo $comments->author; ?>
                                </a>
                            <?php else: ?>
                                <?php echo $comments->author; ?>
                            <?php endif;?>
                        </span>
                        <?php if ($comments->status === "waiting") : ?>
                            <em>（审核后可见）</em>
                        <?php endif; ?>
                        <time class="comment-time"><?php $comments->date('M j, Y'); ?></time>
                        <span class="comment-reply" onclick="return TypechoComment.reply('<?php $comments->theId(); ?>', <?php $comments->coid(); ?>)"><?php $comments->reply('回复'); ?></span>
                    </div>
                    <div class="comment-content">
                        <?php echo GetReply($comments->parent, $comments->content); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($comments->children) { ?>
            <div class="comment-children">
                <?php $comments->threadedComments($options); ?>
            </div>
        <?php } ?>
    </li>
<?php } ?>

<div class="comment-container" />
<?php if ($this->allow('comment')): ?>
<?php $this->comments()->to($comments); ?>
<div class="response">
    添加评论
    <span style="font-size: 16px">
     <?php if ($this->user->hasLogin()): ?>
         You are <a href="<?php $this->options->profileUrl(); ?>"
                    data-no-instant><?php $this->user->screenName(); ?></a> here, do you want to <a
                 href="<?php $this->options->logoutUrl(); ?>" title="Logout"
                 data-no-instant>logout</a> ?
     <?php endif; ?>
    </span>
</div>
<div id="comments" class="clearfix">
    <div id="<?php $this->respondId(); ?>" class="respond" data-respondId="<?php $this->respondId() ?>">
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
                <textarea name="text" id="textarea" class="form-control" placeholder="请输入评论... "
                          required><?php $this->remember('text', false); ?></textarea>
                <?php $comments->cancelReply(' 取消回复'); ?>
                <button type="submit" class="submit" id="misubmit">提交</button>
                <?php $security = $this->widget('Widget_Security'); ?>
                <input type="hidden" name="_" value="<?php echo $security->getToken($this->request->getReferer()) ?>">
            </form>
        </div>
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