$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});
/*--------------------- Search Box */
$(function () {
  $('.search-panel .dropdown-menu').find('a').click(function(e) {
    e.preventDefault();
    var param = $(this).attr("href").replace("#","");
    var concept = $(this).text();
    $('.search-panel span#search_concept').text(concept);
    $('.input-group #search_param').val(param);
  });
});
/*--------------------- Boostrap Number */
$('.after-btn').bootstrapNumber();
/*--------------------- Menu Main */
// (function ($) {
//   var windowWidth = $(window).width();
//   var ifsub = $('.header-bottom--menu-main .first-menu .wsmenu-submenu > li > a.ipad-lans');
//   ifsub.click(function(e) {
//     if(windowWidth == 1024 && $(this).closest('li').find('.wsmenu-submenu-sub').length){
//       e.preventDefault();
//     }
//   });
// })(jQuery);
//
// (function ($) {
//   $('#first-menu').click(function(e) {
//     $(this).closest('.first-menu').find('.wsmenu-submenu').toggleClass("active");
//     e.preventDefault();
//   });
// })(jQuery);
//
// (function ($) {
//   var getHeight_Menu = $('.first-menu .wsmenu-submenu').height();
//   var offsetTop_Menu = ($('.first-menu .wsmenu-submenu').offset().top - getHeight_Menu + 5);
//   $('.first-menu .wsmenu-submenu-sub').css({'height' : getHeight_Menu, 'top' : offsetTop_Menu});
// })(jQuery);
/*--------------------- Poppup Account */
$('.header-top--account a.btn-click-open-tab').click(function(e) {
  e.preventDefault();
  var href = $(this).attr('data-href');
  $(href).trigger("click");

  $('.popup-sign-up').addClass('active');
  $('.closeall').addClass('active');
  $('body').css('left', '-280px');
});

$('#closeall').click(function(e) {
  e.preventDefault();
  $('.popup-sign-up').removeClass('active');
  $('.closeall').removeClass('active');
  $('body').css('left', '0px');
});
$(function () {
  var header = $(".header--bottom");
  $(window).scroll(function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 200) {
      header.addClass("show-header");
    } else {
      header.removeClass("show-header");
    }
  });
});
/*--------------------- Auto Slider */
(function ($) {
  $('.carousel').carousel({
    interval: 2000
  })
})(jQuery);
/*--------------------- Plus Mins Add to cart */
(function ($) {
  $('.minus-btn').on('click', function(e) {
    e.preventDefault();
    var $this = $(this);
    var $input = $this.closest('div').find('input');
    var value = parseInt($input.val());

    if (value > 1) {
      value = value - 1;
    } else {
      value = 0;
    }

    $input.val(value);

  });

  $('.plus-btn').on('click', function(e) {
    e.preventDefault();
    var $this = $(this);
    var $input = $this.closest('div').find('input');
    var value = parseInt($input.val());

    if (value < 100) {
      value = value + 1;
    } else {
      value =100;
    }

    $input.val(value);
  });
})(jQuery);
/*--------------------- Product Bestview */
$('.product-bestview-rows').slick({
  dots: false,
  infinite: false,
  slidesPerRow: 1,
  rows: 5,
  // prevArrow: $(".pp2"),
  // nextArrow: $(".nn2"),
  responsive: [
    {
      breakpoint: 780,
      settings: {
        slidesPerRow: 2,
        rows: 4,
        infinite: true,
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesPerRow: 1,
        rows: 3,
      }
    },
    {
      breakpoint: 380,
      settings: {
        slidesPerRow: 1,
        rows: 2,
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});
/*--------------------- Product Bestseller */
(function ($) {

  var owl = $("#product-bestseller");

  owl.owlCarousel({

    pagination: false,
    navigation: true,
    slideSpeed: 500,
    navigationText: ["<i class=\"fas fa-angle-left\">", "<i class=\"fas fa-angle-right\">"],
    autoPlay: 5000, //Set AutoPlay to 3 seconds

    itemsCustom: [
      [0, 2],
      [450, 2],
      [600, 3],
      [700, 3],
      [1000, 4],
      [1200, 5],
      [1400, 5],
      [1600, 5]
    ],

  });
})(jQuery);
/*--------------------- Box Partner */
(function ($) {
  $("#owl-partner").owlCarousel({
    pagination: false,
    margin:10,
    navigation: true,
    slideSpeed: 1000,
    navigationText: ["<i class=\"fas fa-angle-left\">", "<i class=\"fas fa-angle-right\">"],
    autoPlay: false, //Set AutoPlay to 3 seconds

    itemsCustom : [
      [0, 2],
      [450, 2],
      [600, 3],
      [780, 3],
      [1000, 5],
      [1200, 5],
      [1400, 7],
      [1600, 7]
    ],

  });
})(jQuery);
/*--------------------- Box Pagination */
$(function(){
  $('.box-pagination ul').addClass('justify-content-center');
  $('.box-pagination ul li').addClass('page-item');
  $('.box-pagination ul li a').addClass('page-link');
});
/*--------------------- Box Slide Single Product */
(function ($) {
  $('.slider-producted').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    asNavFor: '.slider-thumbnail-navigation',
    autoplay: false
  });
  $('.slider-thumbnail-navigation').slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    asNavFor: '.slider-producted',
    dots: false,
    centerMode: false,
    focusOnSelect: true
  });
})(jQuery);
/*--------------------- Box Payment */
(function ($) {
  $('.box-payment ul li .block-content').addClass('d-none');
  $('.box-payment ul li:first-child .block-content').removeClass('hide');
  $("[name=payment_1]").click(function() {
    $('.block-content').hide();
    $("#payment-" + $(this).val()).removeClass('d-none').show(600);
  });
})(jQuery);
/*-------------------------- Script Rating Comment */
__data_comment = {};
$(document).on('submit', '#f-comment', function(){
  var f_comment = '#f-comment-';
  var product_id = parseInt($('#product_id').val());
  var author = $(f_comment + 'author').val();
  var subject = $(f_comment + 'subject').val();
  var content = $(f_comment + 'content').val();

  if($.trim(author) == ''){
    alert('Vui lòng nhập họ tên bạn để nhận xét!');
    $(f_comment + 'author').focus();
    return false;
  }

  if($.trim(subject) == ''){
    alert('Vui lòng nhập tiêu đề nhận xét!');
    $(f_comment + 'subject').focus();
    return false;
  }

  if($.trim(content) == ''){
    alert('Vui lòng nhập nội dung nhận xét!');
    $(f_comment + 'content').focus();
    return false;
  }

  __data_comment['product_id'] = product_id;
  __data_comment['author'] = author;
  __data_comment['subject'] = subject;
  __data_comment['comment'] = content;

  //console.log(__data_comment);
  //return false;
  $.ajax({
    url: base_url + "danh-gia-san-pham-ajax",
    type: 'POST',
    cache: false,
    data: __data_comment,
    dataType: 'json',
    success: function (response) {
      //console.log(response);
      if (response.status == 'success') {
        alert(response.message);
        $('#f-comment')[0].reset();

        //them comment vao html, thong ke
        $('#data-comments').prepend(response.content.item);
        $('#data-comments-stars').html(response.content.comments_stars);
        $('#data-comments-statistics').html(response.content.comments_statistics);
        if($('#container-comments').hasClass('hide')){
          $('#container-comments').removeClass('hide');
        }

        //hide form danh gia
        $('.your-choice-was').hide();
        $('.overplay-rating').css('position', 'absolute');
      }
    },
    error: function (e) {
      console.log('Lỗi: ' + e.responseText);
    }
  });
  return false;
});
/*--------------------- Rating Box */
(function ($) {
  if($(".my-rating-6").length){
    $(".my-rating-6").starRating({
      totalStars: 5,
      useFullStars: true,
      initialRating: 4,
      strokeColor: '#894A00',
      strokeWidth: 10,
      starSize: 25,
      //readOnly: true,
      starShape: 'rounded',
      callback: function(currentRating, $el){
        // alert('rated ' +  currentRating);
        // console.log('DOM Element ', $el);
        __data_comment['val'] = parseInt(currentRating);
        console.log(__data_comment['val']);
        if (currentRating) {
          $('.your-choice-was').show();
          //$('.choice').text(currentRating);
          $('.overplay-rating').css('position', 'static');
        }
        return false;
      }
    });
  }
})(jQuery);
/*--------------------- Post Home */
(function ($) {
  var sudoSlider = $("#sudoslider").sudoSlider({
    vertical:true,
    auto:true,
    continuous:true
  });
})(jQuery);
/*--------------------- Product Cat Home*/
(function ($) {

  $('.owl-length-cat').each(function(i, obj) {
    var id = $(this).find('.owl-carousel').attr('id');
    //alert(id);

    $("#"+id).owlCarousel({
      items : 10,
      scrollPerPage: true,
      pagination: false,
      navigation: true,
      navigationText: ["<i class=\"fas fa-angle-left\">", "<i class=\"fas fa-angle-right\">"],

      itemsCustom:
      [
        [0, 3],
        [450, 3],
        [600, 6],
        [700, 6],
        [1000, 6],
        [1200, 7],
        [1400, 7],
        [1600, 7]
      ],

    });
    $('#' + id + ' .owl-item:first-child').addClass('active');
  });
})(jQuery);

(function ($) {
  $('.owl-catelogy-slide').each(function(i, obj) {
    var id = $(this).attr('id');
    console.log(id);
    $("#"+id).owlCarousel({
      pagination: false,
      scrollPerPage: true,
      navigation: true,
      slideSpeed: 1000,
      navigationText: ["<i class=\"fas fa-angle-left\">", "<i class=\"fas fa-angle-right\">"],
      autoPlay: false, //Set AutoPlay to 3 seconds

      itemsCustom : [
        [0, 2],
        [450, 2],
        [600, 3],
        [700, 3],
        [1000, 5],
        [1200, 5],
        [1400, 5],
        [1600, 5]
      ],

    });
  });
})(jQuery);
/*--------------------- Custom Tabs Pandel Widget*/
(function ($) {
  $('.field-product-header a').on('click',function(e){
    e.preventDefault();
    var $panel = $(this).closest('.box-product-cat');
    $panel.find('a.active').removeClass('active');
    $(this).addClass('active');
    //figure out which panel to show
    var penalToShow = $(this).attr('href');

    // hide current panel
    $panel.find('.tab-pane.active').fadeOut(300, showNextPanel);

    //show new panel
    function showNextPanel(){
      $(this).removeClass('active');
      $(penalToShow).fadeIn(300, function(){
        $(this).addClass('active');
      });
    }

  });
})(jQuery);
/*--------------------- Custom Tabs Pandel Widget*/
(function ($) {
  $('.owl-item').on("click",function() {
    var owl_carousel = $(this).closest('.owl-carousel').find('.owl-item');
    owl_carousel.each(function() {
      $(this).removeClass('active');
    });
    $(this).addClass('active');
  });
})(jQuery);
(function ($) {
  var owl_length = ($('.owl-length').length + 1);
  //alert(owl_length);
  for(var i=1; i<=owl_length; i++){
    $('#owl-tabproduct'+i+' .owl-item:first-child').addClass('active');
  }
})(jQuery);
/*--------------------- Hotline Fixed Bottom */
(function ($) {
  $('.close-hotline').on("click",function() {
    $(this).closest('.icons-hotline-fixed').find('.box-hotline-fixed-bottom').addClass('collapsed');
    $(this).closest('.icons-hotline-fixed').find('.open-hotline').addClass('active');
  });

  $('.open-hotline').on("click",function() {
    $(this).closest('.icons-hotline-fixed').find('.box-hotline-fixed-bottom').removeClass('collapsed');
    $(this).closest('.icons-hotline-fixed').find('.open-hotline').removeClass('active');
  });
})(jQuery);
$('document').ready(function () {
	$('a').removeAttr('style');
});
