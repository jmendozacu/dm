	// Preloader
	//==================================================================================
	if( !device.tablet() && !device.mobile() ) {
		
		$(document).ready(function () {
		
			$('#logo, #header_social, #slide_content').css({
				visibility: 'hidden'
			});
		
    		$("body").queryLoader2({
        		barColor: "#00c0b6",
        		percentage: true,
        		barHeight: 6,
        		completeAnimation: "grow",
				overlayId: "preloader",
				minimumTime: 1000,
				onComplete: function()
				{
					
					// WayPoint
					//====================================================
					$('.animation').css({
						visibility: 'hidden'
					});	
	
					$('.animation').waypoint(function() {
						$(this).css({ visibility: 'visible' });
 						$(this).addClass('animated');
						}, {
							offset: '95%'
					});
					
					// Hide Preloader
					//====================================================
					$("#start_preloader").fadeOut("fast", function(){
                   		$(this).remove();
                	}); 
					
					// Animate Logo and Slide Text
					//====================================================
					$('#logo, #slide_content, #header_social').css({visibility: 'visible'});
					$("#logo, #header_social").addClass("animated fadeInDown");
					$("#slide_content").addClass("animated delay1 fadeInRight");
					
				}
    		});
		});	
	}
	else
	{
		
		$('#logo, #header_social, #slide_content').css({
			visibility: 'hidden'
		});
		
		window.addEventListener("DOMContentLoaded", function() {	
		
    		$("body").queryLoader2({
        		barColor: "#00c0b6",
        		percentage: true,
        		barHeight: 6,
        		completeAnimation: "fade",
				overlayId: "preloader",
				minimumTime: 1000,
				onComplete: function()
				{	
					// Hide Preloader
					//====================================================
					$("#start_preloader").fadeOut("fast", function(){
                   		$(this).remove();
                	}); 
					
					// Animate Logo and Slide Text
					//====================================================
					$('#logo, #header_social, #slide_content').css({visibility: 'visible'});
					$("#logo, #header_social").addClass("animated fadeInDown");
					$("#slide_content").addClass("animated delay1 fadeInRight");
				}
    		});
		});
	}

	// Supersized Slider
	//==================================================================================
	jQuery(function($){
		$.supersized({
			slides  :  	
			[ 
				{	image : 'images/preloader_background.jpg' } ,
				{	image : 'images/preloader_background.jpg' }
			]
		});
	});

	// Sticky
	//==================================================================================
	$(document).ready(function(){
		$("#logo, #header_social").sticky({ topSpacing: 0 });
		$("#nav").sticky({ topSpacing: 0 });
	});

	// One Page Nav
	//==================================================================================
	$(document).ready(function() {
		$('#nav_list').onePageNav({
    		scrollSpeed: 3000,
    		currentClass: 'active',
    		changeHash: true,
    		filter: ':not(.external)'
		});
	});
	
	// Responsive Nav Hack. Hide Menu After Click It
	//==================================================================================
	$(document).ready(function() {
		var navMain = $(".navbar-collapse");
        navMain.on("click", "a", null, function () {
            if ($(this).attr("href") !== "#") {
                navMain.collapse('hide');
            }
        });
	});
	
	// NiceScroll
	//==================================================================================
	$(document).ready(
		function() {
			$('html').niceScroll({
    			cursorcolor: "#1A212C",
    			zindex: '99999',
    			cursorminheight: 60,
    			scrollspeed: 80,
    			cursorwidth: 7,
    			autohidemode: true,
    			background: "#aaa",
    			cursorborder: 'none',
    			cursoropacitymax: .7,
    			cursorborderradius: 0,
    			horizrailenabled: false
			});
		}
	);
	
	
	
	// Photo Item Roll Over 
	//==================================================================================
	$('.photo_item_preview > a').click(function() {
		return false;
	});
	
	// Popup
	//=================================================================================
	$('.popup').magnificPopup({
  		type: 'ajax',
		ajax: {
			settings: {cache:false} 
			// Ajax settings object that will extend default one - http://api.jquery.com/jQuery.ajax/#jQuery-ajax-settings
			// For example:
			// settings: {cache:false, async:false}
		},
		callbacks: {
    		open: function() {
      			// Will fire when this exact popup is opened\
    		},
    		close: function() {
      			// Will fire when popup is closed
	  			$('body').css('overflow','hidden');
    		}
  		},
	});
	
	// Gallery - Photo Caption Animation
	//==================================================================================
	$('.photo_item').hover(
		function() {
			$(this).find( ".photo_caption" ).addClass('animated');
		},
		function() {
			$(this).find( ".photo_caption" ).removeClass('animated');
		}
	);
	
    // Gallery - Places
    //==================================================================================
    var $gallery = $('#places_gallery');
    // initialize Masonry after all images have loaded  
    $gallery.imagesLoaded( function() {
        $gallery.masonry({
            columnWidth: ".grid_sizer",
            itemSelector: ".masonry_col",
            transitionDuration: "1s",
        });
    });
	
	// Blog-2
	//==================================================================================
	var $blog = $('#checklist_blog');
	// initialize Masonry after all images have loaded  
	$blog.imagesLoaded( function() {
		$blog.masonry({
			columnWidth: ".grid_sizer_blog",
			itemSelector: ".masonry_col_blog",
			transitionDuration: "1s",
			gutter: 20
		});
	});
	
	
	$(document).ready(function() {
		var a = 0;
	});
	
	// Style Switcher
	//==================================================================================
	var clicked = 0;
	$('#theme_options').click(function(){
		if (clicked == 0)
		{
			$( "#style_switcher" ).animate({ "left": "0px" }, "fast" );
			clicked = 2;
			return false;
		}
		else
		{
			$( "#style_switcher" ).animate({ "left": "-110px" }, "fast" );
			clicked = 0;
			return false;
		}
	});