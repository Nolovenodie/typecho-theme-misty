<div class="article-container">
    <?php
    $recommend = $this->options->cIdRecommend;
    $recommendCounts = explode("||", $recommend);
    $number = count($recommendCounts);
    ?>
	<div id="article-list" class="article-list">
		<?php if ($this->have()) : ?>
			<?php while ($this->next()) : ?>
				<?php if (!in_array($this->cid,$recommendCounts)):?>
					<article class="article-single article" id="article-item-<?php $this->cid(); ?>">
						<a href="<?php $this->permalink() ?>">
							<h2 class="title" >
								<?php $this->title() ?>
							</h2>
						</a>
						<div class="meta">
							<?php $this->date('Y-m-d') ?>
						</div>
					</article>
				<?php endif;?>
			<?php endwhile; ?>
		<?php endif; ?>
	</div>
</div>