<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="<?php echo $description; ?>" />
        <meta name="keywords" content="<?php echo $keywords; ?>" />
        <meta property="og:site_name" content="<?php echo $site_name; ?>" />
        <meta property="og:type" content="Website" />
        <meta property="og:title" content="<?php echo $title_seo; ?>" />
        <meta property="og:url" content="<?php echo current_url(); ?>" />
        <meta property="og:description" content="<?php echo $description; ?>" />
    		<meta property="og:image" content="<?php echo base_url(get_module_path('logo') . $site_logo); ?>" />
    		<meta property="fb:app_id" content="<?php echo $this->config->item('app_id', 'facebook'); ?>" />
        <title><?php echo $title_seo; ?></title>
        <link rel="icon" href="<?php echo base_url(get_module_path('logo') . $favicon); ?>" type="image/x-icon">
        <link href="<?php echo get_asset('css_path'); ?>bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo get_asset('css_path'); ?>webslidemenu.css" rel="stylesheet">
        <link href="<?php echo get_asset('css_path'); ?>owl.carousel.css" rel="stylesheet">
        <link href="<?php echo get_asset('css_path'); ?>owl.theme.css" rel="stylesheet">
        <link href="<?php echo get_asset('css_path'); ?>owl.transitions.css" rel="stylesheet">
        <link href="<?php echo get_asset('css_path'); ?>slick.css" rel="stylesheet">
        <link href="<?php echo get_asset('css_path'); ?>slick-theme.css" rel="stylesheet">
        <link href="<?php echo get_asset('css_path'); ?>photoswipe.css" rel="stylesheet">
      	<link href="<?php echo get_asset('css_path'); ?>default-skin.css" rel="stylesheet">
      	<link href="<?php echo get_asset('css_path'); ?>star-rating-svg.css" rel="stylesheet">
      	<link href="<?php echo get_asset('css_path'); ?>tree-menu.css" rel="stylesheet">
        <link href="<?php echo get_asset('css_path'); ?>style.css" rel="stylesheet">
        <!-- Custom Fonts -->
        <link href="<?php echo get_asset('css_path'); ?>fontawesome-all.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo get_asset('css_path'); ?>material-icons.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
        <?php foreach ($plugins_css as $plugin_css): ?>
            <link rel="stylesheet" href="<?php echo base_url('assets/plugins/' . $plugin_css['folder'] . '/' . $plugin_css['name'] . '.css'); ?>" />
        <?php endforeach;?>
        <?php foreach ($modules_css as $module_css): ?>
            <link rel="stylesheet" href="<?php echo base_url('assets/modules/' . $module_css['folder'] . '/css/' . $module_css['name'] . '.css'); ?>" />
        <?php endforeach;?>
        <?php echo add_css($add_css); ?>
        <?php echo $other_seo; ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript">
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', '<?php echo $analytics_UA_code; ?>', 'auto');
            ga('send', 'pageview');
        </script>
    </head>
    <body>
  		<script>
  			window.fbAsyncInit = function() {
  				FB.init({
  				  appId      : '<?php echo $this->config->item('app_id', 'facebook'); ?>',
  				  xfbml      : true,
  				  version    : 'v2.11'
  				});
  			};
  		</script>

  		<div id="fb-root"></div>
  		<script>(function(d, s, id) {
  		  var js, fjs = d.getElementsByTagName(s)[0];
  		  if (d.getElementById(id)) return;
  		  js = d.createElement(s); js.id = id;
  		  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.11";
  		  fjs.parentNode.insertBefore(js, fjs);
  		}(document, 'script', 'facebook-jssdk'));</script>
        <?php $this->load->view('header'); ?>
        <?php $this->load->view($main_content); ?>
        <?php $this->load->view('footer'); ?>

        <div id="gallery" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="pswp__bg"></div>
          <div class="pswp__scroll-wrap">
            <div class="pswp__container">
              <div class="pswp__item"></div>
              <div class="pswp__item"></div>
              <div class="pswp__item"></div>
            </div>
            <div class="pswp__ui pswp__ui--hidden">
              <div class="pswp__top-bar">
                <div class="pswp__counter"></div>
                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                <button class="pswp__button pswp__button--share" title="Share"></button>
                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                <div class="pswp__preloader">
                  <div class="pswp__preloader__icn">
                    <div class="pswp__preloader__cut">
                      <div class="pswp__preloader__donut"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip">
                </div>
              </div>
              <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
              <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
              <div class="pswp__caption">
                <div class="pswp__caption__center">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="pop-up-quickview">
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <div class="modal-body iframe-load"></div>
                    </div>
                </div>
            </div>
            <?php
            $popup_status = get_config_value('popup_status');
            $is_popup = ($popup_status == 1 && is_home());
            ?>
            <?php if ($is_popup): ?>
            <div id="popupModal" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 style="font-size: 20px; color: #f00; margin: 0; margin-top: 3px;"><?php echo get_config_value('popup_title'); ?></h3>
                            <button type="button" class="close text-right" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <?php echo get_config_value('popup_content'); ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

      

        <div class="icons-hotline-fixed">
          <div class="box-hotline-fixed-bottom">
            <div class="close-hotline"><img src="<?php echo get_asset('img_path'); ?>close_error.png" alt="" class="img-fluid"></div>
            <a href="<?php echo isset($info_hotline_scroll_none['link']) ? $info_hotline_scroll_none['link'] : ''; ?>">
              <p>Hotline tư vấn</p>
              <h3><?php echo isset($info_hotline_scroll_none['content']) ? $info_hotline_scroll_none['content'] : ''; ?></h3>
            </a>
          </div>
          <div class="open-hotline"><img src="<?php echo get_asset('img_path'); ?>call.png" alt="" class="img-fluid"></div>
        </div>


        
        <script type="text/javascript">
            base_url = '<?php echo base_url(); ?>';
            logo = '<?php echo base_url(get_module_path('logo') . $site_logo); ?>';
        </script>
        <!--Jquery-->
        <script src="<?php echo get_asset('js_path'); ?>jquery-3.3.1.slim.min.js"></script>
        <script src="<?php echo get_asset('js_path'); ?>popper.min.js"></script>
        <script src="<?php echo get_asset('js_path'); ?>bootstrap.min.js"></script>
        <script src="<?php echo get_asset('js_path'); ?>bootstrap-number-input.js"></script>
        <script src="<?php echo get_asset('js_path'); ?>webslidemenu.js"></script>
        <script src="<?php echo get_asset('js_path'); ?>owl.carousel.min.js"></script>
        <script src="<?php echo get_asset('js_path'); ?>slick.min.js" type="text/javascript"></script>
        <script src="<?php echo get_asset('js_path'); ?>photoswipe.min.js" type="text/javascript"></script>
      	<script src="<?php echo get_asset('js_path'); ?>photoswipe-ui-default.min.js" type="text/javascript"></script>
      	<script src="<?php echo get_asset('js_path'); ?>myscripts.js" type="text/javascript"></script>
      	<script src="<?php echo get_asset('js_path'); ?>jquery.star-rating-svg.js" type="text/javascript"></script>
      	<script src="<?php echo get_asset('js_path'); ?>jquery.sudoSlider.min.js" type="text/javascript"></script>
        <script src="<?php echo get_asset('js_path'); ?>tree-menu-jquery.js" type="text/javascript"></script>
        <script src="<?php echo get_asset('js_path'); ?>site.js" type="text/javascript"></script>
        <!-- <script src="<?php echo get_asset('js_path'); ?>account.js" type="text/javascript"></script> -->
      	<script src="<?php echo get_asset('js_path'); ?>filter.js" type="text/javascript"></script>
        <?php foreach ($plugins_script as $plugin_script) : ?>
            <script src="<?php echo base_url('assets/plugins/' . $plugin_script['folder'] . '/' . $plugin_script['name'] . '.js'); ?>" type="text/javascript"></script>
        <?php endforeach; ?>
        <?php foreach ($modules_script as $module_script) : ?>
            <script src="<?php echo base_url('assets/modules/' . $module_script['folder'] . '/js/' . $module_script['name'] . '.js'); ?>" type="text/javascript"></script>
        <?php endforeach; ?>
        <?php echo add_js($add_js); ?>
         <!--
        <script src="https://stats.viennam.com/site/log"></script>
        <script type="text/javascript">
          $("<li class=\"logo\"><a href=\"" + base_url + "\"><img src=\"" + logo + "\" class=\"img-fluid\"></a></li>").insertAfter(".box-header .header-bottom--menu-main .wsmenu-list>li:eq(2)");
        </script>
        <script type="text/javascript">
          $(function () {
            $("#online").html(onlineLog.Online);
            $("#today").html(onlineLog.Today);
            $("#thismonth").html(onlineLog.ThisMonth);
            $("#total").html(onlineLog.Total);
          });
        </script> -->
        <script>
            <?php if ($is_popup): ?>
            $(window).on('load',function(){
                $('#popupModal').modal('show');
            });
            <?php endif; ?>
          (function ($) {
            $(document).on("click", "a.open-popup-product", function(e) {
              e.preventDefault();
              var id = $(this).attr('data-id');
              //console.log('id: ' + id);
              var noidungpopup = $('#product' + id).html();
              //console.log('noidungpopup: ' + noidungpopup);
              $("#myModal .iframe-load").html(noidungpopup);
              $("#myModal").modal("show");
              return false;

              //console.log(arrs[id]);
            });

            $(document).ready(function(){
              $('#myModal').on('hidden.bs.modal', function () {
                $("#myModal .iframe-load").html('');
              });
            });
          })(jQuery);
        </script>
        <script>
          (function ($) {
            $('#myModal').on('shown.bs.modal', function (e) {
              var sync1 = $("#sync1");
              var sync2 = $("#sync2");

              sync1.owlCarousel({
                singleItem : true,
                slideSpeed : 1000,
                navigation: true,
                pagination:false,
                afterAction : syncPosition,
                responsiveRefreshRate : 200,
                transitionStyle : "fade",
                mouseDrag: false,
                navigationText: ["<i class=\"fas fa-angle-left\"></i>", "<i class=\"fas fa-angle-right\"></i>"],
              });

              sync2.owlCarousel({
                itemsCustom: [
                  [0, 3],
                  [450, 3],
                  [600, 4],
                  [700, 4],
                  [1000, 4],
                  [1200, 4],
                  [1400, 4],
                  [1600, 4]
                ],
                pagination:false,
                mouseDrag: false,
                responsiveRefreshRate : 100,
                transitionStyle : "fade",
                afterInit : function(el){
                  el.find(".owl-item").eq(0).addClass("synced");
                }
              });

              function syncPosition(el){
                var current = this.currentItem;
                $("#sync2")
                .find(".owl-item")
                .removeClass("synced")
                .eq(current)
                .addClass("synced")
                if($("#sync2").data("owlCarousel") !== undefined){
                  center(current)
                }
              }

              $("#sync2").on("click", ".owl-item", function(e){
                e.preventDefault();
                var number = $(this).data("owlItem");
                sync1.trigger("owl.goTo",number);
              });

              function center(number){
                var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
                var num = number;
                var found = false;
                for(var i in sync2visible){
                  if(num === sync2visible[i]){
                    var found = true;
                  }
                }

                if(found===false){
                  if(num>sync2visible[sync2visible.length-1]){
                    sync2.trigger("owl.goTo", num - sync2visible.length+2)
                  }else{
                    if(num - 1 === -1){
                      num = 0;
                    }
                    sync2.trigger("owl.goTo", num);
                  }
                } else if(num === sync2visible[sync2visible.length-1]){
                  sync2.trigger("owl.goTo", sync2visible[1])
                } else if(num === sync2visible[0]){
                  sync2.trigger("owl.goTo", num-1)
                }

              }
            });
          })(jQuery);
        </script>
		<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/60d9601565b7290ac6383941/1f98hul82';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--        <script>-->
<!--            window.addEventListener("load",function(){-->
<!--                setTimeout(-->
<!--                    function open(event){-->
<!--                        document.querySelector(".modal").style.display = "block"-->
<!--                    },-->
<!--                    500-->
<!--                )-->
<!--            });-->
<!---->
<!--            document.querySelector('#close').addEventListener("click",function(){-->
<!--                document.querySelector(".modal").style.display = "none";-->
<!--            });-->
<!--        </script>-->
<!--End of Tawk.to Script-->
    </body>
</html>
