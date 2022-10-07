<?php
/**
 * Template Page of image
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<?php $this->need('public/header.php'); ?>
    <div class="article-container">
        <div id="image-list" class="article-list" style="margin-top: 40px">
            <div class="image-item"></div>
        </div>
    </div>
<?php $this->need('public/footer.php'); ?>
<script type="text/javascript">
    let imageData = initImageData();
    let perPageCount = 9
    let colSumHeight = []
    let nodeWidth = $('.image-item').outerWidth(true)
    let colNum = parseInt($('#image-list').width()/nodeWidth)
    for(let i = 0;i<colNum;i++){
        colSumHeight[i] = 0
    }
    start();
    function start(){
        getData(function(imgList){
            $.each(imgList, function(idx, img){
                var imgUrl = img.attr("data-src")
                var tpl='<a class="image-item" data-fancybox="gallery" href="'+imgUrl+'"></a>';
                var $node = $ (tpl);
                $node.append(img);
                var desc = img.attr('alt');
                if (desc!=null&&desc!=''){
                    desc = '<div class="desc"><p>'+desc+'</p></div>';
                    $node.append($(desc));
                }
                $node.find('img').load(function(){
                    $('#image-list').append($node)
                    waterFallPlace($node)
                })
            })
        })

    }
    function getData(callback) {
        let data = [];
        let len = imageData.length<perPageCount?imageData.length:perPageCount;
        for (let i=0; i<len;i++){
            let img = imageData.shift();
            let $img = $(img);
            let src = $img.attr("src");
            $img.attr('data-src',src);
            $img.attr('src', src+'<?php cdnSuffixPhoto($this->options->cdnType); ?>')
            data.push($img);
        }
        callback(data);
    }
    $(window).scroll(function(){
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if(scrollTop + windowHeight == scrollHeight){
            start();
        }
    })
    function waterFallPlace($node){
        var idx = 0,
            minSumHeight = colSumHeight[0];
        for (var i=0;i<colSumHeight.length;i++){
            if(colSumHeight[i]<minSumHeight){
                idx = i;
                minSumHeight = colSumHeight[i];
            }
        }
        $node.css({
            left: nodeWidth*idx,
            top: minSumHeight,
            opacity:1
        });
        colSumHeight[idx] = $node.outerHeight(true) + colSumHeight[idx];
        $('#image-list').height(Math.max.apply(null,colSumHeight));
    }
    function initImageData(){
        let content = '<?php $this->content()?>'
        let imgReg = /<img.*?(?:>|\/>)/gi;
        if(imgReg.test(content)) {
            return content.match(imgReg);
        }
    }
</script>
