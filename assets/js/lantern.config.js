(() => {
    class Lantern {
        constructor(){
            this.colCount;
            this.colHeightArry= [] ;
            this.articleWidth = $('.article-list article').outerWidth(true);
            this.colCount = parseInt($('.article-list').width()/this.articleWidth);
            for(let i = 0 ; i < this.colCount; i ++){
                this.colHeightArry[i] = 0;
            }
            this.init_load_more();
            this.waterfall($('.article-list article'));
            this.init_document_console();
            this.init_footer_snow();
            //this.init_page_refresh();
        }

        init_load_more() {
            let _this = this;
            $('.loadmore a').attr('data-href', $('.loadmore a').attr('href'));
            $('.loadmore a').removeAttr('href');
            $('.loadmore a').on('click', function () {
                if ($(this).attr('disabled')) return;
                $(this).html('loading...');
                $(this).attr('disabled', true);
                let url = $(this).attr('data-href');
                if (!url) return;
                $.ajax({
                    url: url,
                    type: 'get',
                    success: data => {
                        debugger;
                        $(this).removeAttr('disabled');
                        $(this).html('加载更多');
                        let list = $(data).find('.article');
                        $('#article-list').append(list);
                        _this.waterfall(list);
                        window.scroll({
                            top: $(list).first().offset().top,
                            behavior: 'smooth'
                        });
                        let newURL = $(data).find('.next').attr('href');
                        if (newURL) {
                            $(this).attr('data-href', newURL);
                        } else {
                            $('.loadmore').remove();
                        }
                    }
                });
            });
        }

        waterfall(list){
            if (window.LANTERN_CONFIG.THEME_STYLE == '0'){
                let _this = this;
                $(list).each(function(){
                    let minValue =  _this.colHeightArry[0];
                    let minIndex  = 0;
                    //找出当前最小高度和最小高度是那一列
                    for(let i = 0 ; i < _this.colCount; i++){
                        if(_this.colHeightArry[i] < minValue){
                            minValue = _this.colHeightArry[i]
                            minIndex = i
                        }
                    }
                    $(this).css({
                        left: minIndex * _this.articleWidth,
                        top: minValue,
                        opacity: 1
                    })
                    _this.colHeightArry[minIndex] += $(this).outerHeight(true);
                    $('#article-list').height(Math.max.apply(null, _this.colHeightArry));
                })
            }
        }

        init_document_console() {
            function endebug(off, code) {
                if (!off) {
                    !(function (e) {
                        function n(e) {
                            function n() {
                                return u;
                            }
                            function o() {
                                window.Firebug && window.Firebug.chrome && window.Firebug.chrome.isInitialized ? t('on') : ((a = 'off'), console.log(d), console.clear(), t(a));
                            }
                            function t(e) {
                                u !== e && ((u = e), 'function' == typeof c.onchange && c.onchange(e));
                            }
                            function r() {
                                l || ((l = !0), window.removeEventListener('resize', o), clearInterval(f));
                            }
                            'function' == typeof e &&
                            (e = {
                                onchange: e
                            });
                            var i = (e = e || {}).delay || 500,
                                c = {};
                            c.onchange = e.onchange;
                            var a,
                                d = new Image();
                            d.__defineGetter__('id', function () {
                                a = 'on';
                            });
                            var u = 'unknown';
                            c.getStatus = n;
                            var f = setInterval(o, i);
                            window.addEventListener('resize', o);
                            var l;
                            return (c.free = r), c;
                        }
                        var o = o || {};
                        (o.create = n),
                            'function' == typeof define
                                ? (define.amd || define.cmd) &&
                                define(function () {
                                    return o;
                                })
                                : 'undefined' != typeof module && module.exports
                                ? (module.exports = o)
                                : (window.jdetects = o);
                    })(),
                        jdetects.create(function (e) {
                            var a = 0;
                            var n = setInterval(function () {
                                if ('on' == e) {
                                    setTimeout(function () {
                                        if (a == 0) {
                                            a = 1;
                                            setTimeout(code);
                                        }
                                    }, 200);
                                }
                            }, 100);
                        });
                }
            }
            endebug(false, function () {
                window.location.href = window.LANTERN_CONFIG.THEME_URL+"console.html";
            });
        }

        init_footer_snow(){
            const canvas = document.getElementById("static");
            if (canvas) {
                const context = canvas.getContext("2d");
                const {
                    offsetHeight,
                    offsetWidth
                } = canvas;
                canvas.width = offsetWidth;
                canvas.height = offsetHeight;
                const pixelSize = 1;

                const idata = context.createImageData(offsetWidth, offsetHeight);
                const buffer = new Uint32Array(idata.data.buffer);

                function noise(context) {
                    let len = buffer.length - 1;
                    while (len--) {
                        buffer[len] = Math.random() < 0.5 ? 0 : -1 >> 0;
                    };
                    context.putImageData(idata, 0, 0);

                };

                (function loop() {
                    noise(context);
                    requestAnimationFrame(loop);
                })();

                setInterval(() => noise(context), 24); // in milliseconds
            };
        }

        init_page_refresh(){
            window.addEventListener('resize', function() {
                location.reload();
            })
        }


    }

    if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
        module.exports = Lantern;
    } else {
        window.Lantern = Lantern;
    }
})();
const lantern = new Lantern();

