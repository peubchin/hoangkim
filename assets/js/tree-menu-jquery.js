( function( $ ) {
$( document ).ready(function() {
	
	//console.log(windowWidth);
	$('#cssmenu > ul > li > a').click(function() {
		  $('#cssmenu li').removeClass('active');
		  $(this).closest('li').addClass('active');	
		  var checkElement = $(this).next();
		  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
			$(this).closest('li').removeClass('active');
			checkElement.slideUp('normal');
		  }
		  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
			$('#cssmenu ul ul:visible').slideUp('normal');
			checkElement.slideDown('normal');
		  }
		  if($(this).closest('li').find('ul').children().length == 0) {
			return true;
		  } else {
			return false;	
		  }	
	});
	
	$('#cssmenu > ul > li > ul > li > a').click(function() {
		  
		  $('#cssmenu li').removeClass('active');
		  $(this).closest('li').addClass('active');	
		  var checkElement = $(this).next();
		  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
			  
			$(this).closest('li').removeClass('active');
			checkElement.slideUp('normal');
		  }
		  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
			$(this).closest('li').find('ul').removeClass('hide');  
			$('#cssmenu ul ul ul:visible').slideUp('normal');
			checkElement.slideDown('normal');
			
		  }
		  
		  if($(this).closest('li').find('ul').children().length == 0) {
			return true;
		  } else {
			return false;	
		  }	
	});
	
	$('#cssmenu > ul > li > ul > li > ul > li > a').click(function() {
		  $('#cssmenu li').removeClass('active');
		  $(this).closest('li').addClass('active');	
		  var checkElement = $(this).next();
		  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
			$(this).closest('li').removeClass('active');
			checkElement.slideUp('normal');
		  }
		  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
			$('#cssmenu ul ul ul ul:visible').slideUp('normal');
			checkElement.slideDown('normal');
		  }
		  if($(this).closest('li').find('ul').children().length == 0) {
			return true;
		  } else {
			return false;	
		  }		
	});
});
} )( jQuery );

	$( document ).ready(function() {
		$('#cssmenu > ul > li > ul > li > ul').each(function() {
			$(this).addClass('hide');
			if($(this).find('li.active').length > 0){
				$(this).removeClass('hide');
			}				
		});
		$('#cssmenu > ul > li > ul > li').each(function() {
			$(this).not(':has(ul)').find('a span').css("background", "none");
		});
		$('#cssmenu > ul > li > ul > li > ul > li').each(function() {
			$(this).not(':has(ul)').find('a span').css("background", "none");
		});
		$('#cssmenu > ul > li > ul > li > ul > li > ul > li').each(function() {
			$(this).not(':has(ul)').find('a span').css("background", "none");
		});
	});

