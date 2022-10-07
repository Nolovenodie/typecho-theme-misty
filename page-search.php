<?php
/**
 * Template Page of Search and Archive
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<?php $this->need('public/header.php'); ?>
    <div class="post-container">
        <div class="content-container">
            <div class="archive-container">
                <form class="search-form" action="<?php $this->options->siteUrl(); ?>" role="search">
                    <input type="text" name="s" required="true" placeholder="输入搜索内容..." class="search-input">
                    <button type="submit" class="iconfont search-btn">
                        &#xe627;
                    </button>
                </form>

                <div class="tags-container">
                    <?php $this->widget('Widget_Metas_Tag_Cloud', 'sort=count&ignoreZeroCount=1&desc=1&limit=50')->to($tags); ?>
                    <div class="terms-tags">
                        <?php parseContent($this); ?>
                        <?php if ($tags->have()): ?>
                            <?php while ($tags->next()): ?>
                                <a href="<?php $tags->permalink(); ?>" class="terms-link"><?php $tags->name(); ?>
                                    <span class="terms-count"><?php $tags->count(); ?></span>
                                </a>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p> Nothing here ! </p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="category-container">
                    <div id="category-list" class="category-list">
                        <div class="category-name active"><span>全部类别</span></div>
                        <?php $this->widget('Widget_Metas_Category_List')->to($categorys); ?>
                        <?php $categorys_temp = $categorys ?>
                        <?php if ($categorys->have()): ?>
                            <?php while ($categorys->next()): ?>
                                <div class="category-name hover-line"><span><?php $categorys->name(); ?></span></div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="archive-list" id="archive-list">
                    <?php
                    $stat = Typecho_Widget::widget('Widget_Stat');
                    $this->widget('Widget_Contents_Post_Recent', 'pageSize=' . $stat->publishedPostsNum)->to($archives);
                    $year = 0;
                    $mon = 0;
                    $i = 0;
                    $j = 0;
                    $output = '<div class="archives active">';
                    while ($archives->next()) {
                        $year_tmp = date('Y', $archives->created);
                        $mon_tmp = date('m', $archives->created);
                        $y = $year;
                        $m = $mon;
                        if ($year > $year_tmp || $mon > $mon_tmp) {
                            $output .= '</div>';
                        }
                        if ($year != $year_tmp || $mon != $mon_tmp) {
                            $year = $year_tmp;
                            $mon = $mon_tmp;
                            $output .= '<div class="archives-month">' . $year_tmp . ' 年 ' . $mon_tmp . ' 月' . '</div><div>';
                        }
                        $day_tmp = date('d', $archives->created);
                        $output .= '<div class="archive-post"><span class="archive-post-time">' . $mon_tmp . '-' . $day_tmp . '</span><span class="archive-post-title"><a class="archive-post-link" href="' . $archives->permalink . '">' . $archives->title . '</a></span></div>';
                    }
                    $output .= '</div></div>';
                    echo $output;
                    ?>

                    <?php if ($categorys_temp->have()): ?>
                        <?php while ($categorys_temp->next()): ?>
                            <?php $catlist = $this->widget('Widget_Archive@categorys_' . $categorys_temp->mid, 'pageSize=10000&type=category', 'mid=' . $categorys_temp->mid); ?>
                            <?php if ($catlist->have()): ?>
                                <?php
                                $year = 0;
                                $mon = 0;
                                $i = 0;
                                $j = 0;
                                $output = '<div class="archives">';
                                while ($catlist->next()) {
                                    $year_tmp = date('Y', $catlist->created);
                                    $mon_tmp = date('m', $catlist->created);
                                    $y = $year;
                                    $m = $mon;
                                    if ($year > $year_tmp || $mon > $mon_tmp) {
                                        $output .= '</div>';
                                    }
                                    if ($year != $year_tmp || $mon != $mon_tmp) {
                                        $year = $year_tmp;
                                        $mon = $mon_tmp;
                                        $output .= '<div class="archives-month">' . $year_tmp . ' 年 ' . $mon_tmp . ' 月' . '</div><div>';
                                    }
                                    $day_tmp = date('d', $catlist->created);
                                    $output .= '<div class="archive-post"><span class="archive-post-time">' . $mon_tmp . '-' . $day_tmp . '</span><span class="archive-post-title"><a class="archive-post-link" href="' . $catlist->permalink . '">' . $catlist->title . '</a></span></div>';
                                }
                                $output .= '</div></div>';
                                echo $output;
                                ?>
                            <?php else:?>
                                <div class="archives">无内容</div>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#category-list .category-name').click(function () {
            $(this).addClass('active').siblings().removeClass('active');
            var a = $(this).index();
            $('#archive-list .archives:eq(' + a + ')').addClass('active').siblings().removeClass('active');
        })
    </script>


<?php $this->need('public/footer.php'); ?>