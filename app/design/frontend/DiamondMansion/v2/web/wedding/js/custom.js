jQuery(document).ready(function() {
    
	/*-----------------------------------------------------------------------------------*/
	/*	Smooth Scroll
	/*  Thanks to: https://github.com/davist11/jQuery-One-Page-Nav
	/*-----------------------------------------------------------------------------------*/

	function smoothScroll(){
		jQuery(".nav").onePageNav({
			filter: ':not(.external)',
			scrollSpeed: 1500
		});

		// Scrolls to RSVP section
		jQuery(".js-scroll").click(function() {
			jQuery('html, body').animate({
				scrollTop: jQuery("#section-6").offset().top
			}, 2000);
			return false;
		});

		return false;
	}

	smoothScroll();

	/*-----------------------------------------------------------------------------------*/
	/*	Backstretch
	/*  Thanks to: http://srobbin.com/jquery-plugins/backstretch/
	/*-----------------------------------------------------------------------------------*/

	function backStrech() {
		jQuery("aside").backstretch([
			jQuery("aside").data("image-1"),
			jQuery("aside").data("image-2"),

			], {duration: 5000, fade: 1000});
	}

	backStrech();

	/*-----------------------------------------------------------------------------------*/
	/*	Flexslider
	/*  Thanks to: http://www.woothemes.com/flexslider/
	/*-----------------------------------------------------------------------------------*/

	function flexSlider(){
        jQuery('#cut-type-content').flexslider({
            animation: "slide",
            direction: "vertical", 
            slideshow: false,
            controlNav: false,
            directionNav: false,
            sync: "#cut-type-list",
            animationLoop: false,
            touch: true
        });
        jQuery('#cut-type-list').flexslider({
            animation: "slide",
            direction: "vertical", 
            slideshow: false,
            controlNav: false,
            directionNav: false,
            asNavFor: "#cut-type-content",
            animationLoop: false,
            touch: true
        });
        jQuery('#cut-type-list li').bind("click", function() {
            jQuery(this).addClass("flex-active-slide");
            jQuery("#"+jQuery(this).parent().attr("for")).val(jQuery(this).index() * 10);
        });
        jQuery('#clarity-type-content').flexslider({
            animation: "slide",
            direction: "vertical", 
            slideshow: false,
            controlNav: false,
            directionNav: false,
            sync: "#clarity-type-list",
            animationLoop: false,
            touch: true
        });
        jQuery('#clarity-type-list').flexslider({
            animation: "slide",
            direction: "vertical", 
            slideshow: false,
            controlNav: false,
            directionNav: false,
            asNavFor: "#clarity-type-content",
            animationLoop: false,
            touch: true
        });
        jQuery('#clarity-type-list li').bind("click", function() {
            jQuery(this).addClass("flex-active-slide");
            jQuery("#"+jQuery(this).parent().attr("for")).val(jQuery(this).index() * 10);
        });
        jQuery('#color-type-content').flexslider({
            animation: "slide",
            direction: "vertical", 
            slideshow: false,
            controlNav: false,
            directionNav: false,
            sync: "#color-type-list",
            animationLoop: false,
            touch: true
        });
		jQuery('#color-type-list').flexslider({
			animation: "slide",
            direction: "vertical", 
			slideshow: false,
            controlNav: false,
            directionNav: false,
            asNavFor: "#color-type-content",
            animationLoop: false,
			touch: true
		});
        jQuery('#color-type-list li').bind("click", function() {
            jQuery(this).addClass("flex-active-slide");
            jQuery("#"+jQuery(this).parent().attr("for")).val(jQuery(this).index() * 10);
        });
        jQuery('#cut-part-list').flexslider({
            animation: "slide",
            direction: "vertical", 
            slideshow: false,
            controlNav: false,
            directionNav: false,
            animationLoop: false,
            touch: true
        });
        jQuery('#cut-part-list li').bind("click", function() {
            jQuery("#cut-part-list li").removeClass("flex-active-slide");
            jQuery(this).addClass("flex-active-slide");
            jQuery("#cut-part-image").attr("src", jQuery(this).attr("image"));
            jQuery("#cut-part-caption").html(jQuery(this).attr("caption"));
        });
        jQuery('.noUiSlider').each(function (index, value) {
            var maxValue = (jQuery("#"+jQuery(this).attr("for")+" li").length - 1) * 10;
            jQuery(this).noUiSlider({
                range: [0, maxValue],
                start: 0,
                handles: 1,
                orientation: "vertical",
                step: 1,
                behaviour: 'tap-drag'
            }).change( function(){
                var index = parseInt(jQuery(this).val()/10);
                jQuery("#"+jQuery(this).attr("for") + " ul li:eq("+index+")").trigger("click");   
            });
        });
	}

	flexSlider();
    
    var scrollTimer = null;
    jQuery(window).scroll(function () {
        
        if (jQuery("aside").css("display") == "none") {
            return;
        }
        
        if (scrollTimer) {
            clearTimeout(scrollTimer);   // clear any previous pending timer
        }
        scrollTimer = setTimeout(handleScroll, 5);   // set new timer
    });

    function handleScroll() {
        scrollTimer = null;
        var headerBottom = jQuery(".page-header").outerHeight();
        var footerTop = jQuery(document).height() - jQuery(window).height() -  jQuery(".footer").height();

        var scrollTop = jQuery(window).scrollTop();

        if (scrollTop == 0) {
            jQuery(".cms-diamond-education aside.columns").css("margin-top", "94px");
        } else if (scrollTop <= headerBottom) {
            jQuery(".cms-diamond-education aside.columns").css("margin-top", (headerBottom - scrollTop) + "px");
        } else if (scrollTop > headerBottom && scrollTop <= footerTop) {
            jQuery(".cms-diamond-education aside.columns").css("margin-top", headerBottom + "px");
        } else {
            jQuery(".cms-diamond-education aside.columns").css("margin-top", (footerTop - scrollTop) + "px");
        }
    }
    
    handleScroll();

    function changeShapeCaratViewerImage() {
        var shapeLabel = jQuery("#shape-list li.active").attr("rel");
        var shapeCode = jQuery("#shape-list li.active").attr("code");
        var carat = jQuery("#carat-list li.active").attr("rel");
        
        var imageUrl = jQuery("#main").data("wedding-dir") + "/img/shape-carat/" + shapeLabel + "/" + shapeCode + " " + carat + ".jpg";
        
        jQuery('#carat-size-image').fadeTo(500, 0, function() {
            jQuery('#carat-size-image').attr('src',imageUrl).bind('onreadystatechange load', function(){
                if (this.complete) jQuery(this).fadeTo(300, 1);
            });
        });        
    }
    
    function changeTab(type, tabIndex, className) {
        elm = jQuery(type + " li").eq(tabIndex);
        jQuery(type + " li." + className).removeClass(className);
        elm.addClass(className);
        elm.trigger("click");
        
        if (tabIndex == 0) { jQuery(type + "-prev").css("visibility", "hidden"); } else { jQuery(type + "-prev").css("visibility", "visible"); }
        if (tabIndex == jQuery(type + " li").length - 1) { jQuery(type + "-next").css("visibility", "hidden"); } else { jQuery(type + "-next").css("visibility", "visible"); }
    }
    
    jQuery(".prev-arrow").bind("click", function () {
        var type = "#" + jQuery(this).attr("rel");
        var className = jQuery(this).attr("classname");
        var tabIndex = jQuery(type + " li." + className).index();
        changeTab(type, tabIndex - 1)
        return false;
    });
    
    jQuery(".next-arrow").bind("click", function () {
        var type = "#" + jQuery(this).attr("rel");
        var className = jQuery(this).attr("classname");
        var tabIndex = jQuery(type + " li." + className).index();
        changeTab(type, tabIndex + 1)
        return false;
    });
    
    jQuery("#shape-list li").bind("click", function () {
        jQuery("#shape-list li.active").removeClass("active");
        jQuery(this).addClass("active");
        changeShapeCaratViewerImage();
    });
    jQuery("#carat-list li").bind("click", function () {
        jQuery("#carat-list li.active").removeClass("active");
        jQuery(this).addClass("active");
        changeShapeCaratViewerImage();
    });
    
    jQuery(".top-links-section a").bind("click", function() {
        jQuery(".primary-nav a[href='"+jQuery(this).attr("href")+"']").trigger("click");
        return false;
    });

    jQuery(".primary-nav a").on('click', function () {
        jQuery(window).scrollTop(jQuery(jQuery(this).attr('href')).offset().top - 150);
        return false;
    });
});

jQuery(document).foundation();