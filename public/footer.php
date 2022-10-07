<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;?>
<!--<canvas id="static"></canvas>-->
<div class="footer-container">
    <p>
        Copyright &copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?> </a>
    </p>
</div>
<div id="outerdiv">
    <img id="bigimg" src="" />
</div>
<?php $this->footer();?>
<script src="<?php $this->options->themeUrl('assets/js/lantern.config.js');?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $( ".fancybox").fancybox();
    });
</script>
<?php echo $this->options->beforeBodyClose; ?>