jQuery(function($){
	
	$(document).bind('contextmenu', function (e) {
        e.preventDefault();
	});

   
	//get view port
	function viewport() {
		var e = window, a = 'inner';
		if (!('innerWidth' in window )) {
			a = 'client';
			e = document.documentElement || document.body;
		}
		return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
	}
	
	var win_width = viewport().width;
	
	//desktop
	if(win_width > 767) 
	{
		//Search input toggle
		$('#top .search-button').click(function(){
			$(this).css('display', 'none');
			$('#top .top-menu').css('margin-right', '275px');
			$('#top .search-container').css('display', 'inline');
			$('#top .search-container input').focus();
			$('#cart_list').css('right', '244px');
		});
		
		$('#top .search-button-icon').click(function(){
			$('#top .search-button').css('display', 'block');
			$('#top .search-container').css('display', 'none');
			$('#top .top-menu').css('margin-right', '100px');
			$('#cart_list').css('right', '0');
		});
		
	}
	
	
	
	//mobile
	if(win_width < 767 && win_width > 425) {
		$('#top .search-button').click(function(){
			$(this).css('display', 'none');
			$('#top .top-menu').css('margin-right', '200px');
			$('#top .search-container').css('display', 'inline');
			$('#top .search-container input').focus();
			$('#cart_list').css('right', '244px');
			
		});
		
		$('#top .search-button-icon').click(function(){
			$('#top .search-button').css('display', 'block');
			$('#top .search-container').css('display', 'none');
			$('#top .top-menu').css('margin-right', '100px');
			$('#cart_list').css('right', '0');
		});
	}
	
	if(win_width <= 425) {
		$('#top .search-button').click(function(){
			$(this).css('display', 'none');
			$('#top .top-menu').css('display', 'none');
			$('#top .search-container').css('display', 'inline');
			$('#top .search-container input').focus();
			$('#cart_list').css('right', '244px');
			$("#top form").css('width', '100%');
			
			
		});
		
		$('#top .search-button-icon').click(function(){
			$('#top .search-button').css('display', 'block');
			$('#top .search-container').css('display', 'none');
			$('#top .top-menu').css('display', 'block');
			$('#cart_list').css('right', '0');
			$("#top form").css('width', 'auto');
		});
	}
	

	//Dropdown cart scrollbar
	$('.scrollbar-inner').scrollbar();
	
	//Product details slider
	
	$('.slider-for').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  arrows: false,
	  fade: true,
	  asNavFor: '.slider-nav'
	});
	if(win_width > 425) {
		$('.slider-nav').slick({
		  slidesToShow: 3,
		  slidesToScroll: 1,
		  asNavFor: '.slider-for',
		  dots: false,
		  centerMode: true,
		  focusOnSelect: true
		});
	}
	else if(  win_width <= 425) {
		$('.slider-nav').slick({
		  slidesToShow: 2,
		  slidesToScroll: 1,
		  asNavFor: '.slider-for',
		  dots: false,
		  centerMode: true,
		  focusOnSelect: true
		});
	}
	//Quantity
	$('.quantity-plus').click(function(){
		var $quantity_box = $(this).closest('.quantity-container').find('.quantity-input');
		var quantity = parseInt($quantity_box.val());
		if(quantity === 0)
			quantity = 0;
		$quantity_box.val(quantity+1);
	});
	$('.quantity-minus').click(function(){
		var $quantity_box = $(this).closest('.quantity-container').find('.quantity-input');
		var quantity = parseInt($quantity_box.val());
		if(quantity === 0)
			quantity = 0;
		if(quantity > 0)
			$quantity_box.val(quantity-1);
	});
	$('.quantity-input').click(function(){
		if( $(this).val() < 0 )
			$(this).val(0);
	});
	
	//Password reset
	$('.reset .controls input').attr("placeholder", "Email*");
	$('.reset-confirm .controls #jform_username').attr("placeholder", "Email*");
	$('.reset-confirm .controls #jform_token').attr("placeholder", "Verification*");
	
	//Desktop menu
	$("#header li.deeper").hover(
        function() { 
        $(this).toggleClass('open');
        },
        function() { 
        $(this).toggleClass('open');
    });
	  
	//Product modal
	$('#productModal, #brandModal, #modal-confirm-del-address, #modal-change-password').appendTo("body");
	
	//Products page: Click to view next/previous product
	$('body').on('click', '.product-details-modal .next', function(){
		var url = $(this).attr('data-href');
		$('.product-details-modal').html('Loading...');
		$.ajax({
			type: 'GET',
			url: url,
			cache: false,
			dataType: 'html',
			success: function(data) {
				$('.product-details-modal').html(data);
			}
		});
	});
	$('body').on('click', '.product-details-modal .prev', function(){
		var url = $(this).attr('data-href');
		$('.product-details-modal').html('Loading...');
		$.ajax({
			type: 'GET',
			url: url,
			cache: false,
			dataType: 'html',
			success: function(data) {
				$('.product-details-modal').html(data);
			}
		});
	});
	$('body').on('click', '.owl-carousel-product .item .product-item a', function(){
		$('.product-details-modal').html('Loading...');
	});
	$('body').on('click', '.owl-carousel-brands .item .brand-item a', function(){
		$('.owl-carousel-product-modal').html('Loading...');
	});
	  
	//Delete address modal
	var edit_address_dataID,
		edit_address_addrType;
	$('.delete-address').bind('click',function(){
			edit_address_dataID = $(this).data('id');
			edit_address_addrType = $(this).data('addrtype');
	})
	$('#modal-confirm-del-address').on('shown.bs.modal', function (event) {
          $(this).find('#virtuemart_userinfo_id').val(edit_address_dataID);
		  $(this).find('#addrtype').val(edit_address_addrType);
    });
	$(document).ready(function(){
		
		$(".product-image img").each(function(){
			var src = $(this).attr('src');
			$(this).attr('data-zoom-image', src);
		});
		 
		//all image
		/* $('.product-image img:not(.non-zoom)').elevateZoom({
			zoomType: "inner",
			cursor: "crosshair",
			zoomWindowWidth:300,
			zoomWindowHeight:300,
		}); */
		  
		//just image in slick
		if(win_width > 992) {
			$('.product-image img.non-zoom').elevateZoom({
				zoomType	: "inner", 
				cursor: "crosshair"
			});
		}
		//foronly slick
		 //when change image slick
		$('.slick-slide').bind('click',function(){
			src = $('.slider-for .slick-current').find('img').attr('src');
			$(".zoomWindow").each(function(){
				url = $(this).css('background-image').replace(/^url|[\(\)]/g, '');
				
				if ( url.includes(src) ){
					$(this).parent().parent().show();
				}
				else	
					$(this).parent().parent().hide();
			});
		 })
		 //inital load first slick
		 
		var src = $('.slider-for .slick-current').find('img').attr('src');
		if ( src && src.length){
			setTimeout(function(){ 
				$(".zoomWindow").each(function(){
					url = $(this).css('background-image').replace(/^url|[\(\)]/g, '');
					
					if ( url.includes(src) ){
						$(this).parent().parent().show();
					}
					else	
						$(this).parent().parent().hide();
				}); }, 500);
		} 
	});
	
});