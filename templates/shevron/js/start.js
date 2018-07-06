var sliderCont;

jQuery(function(){
    
    var win_width = jQuery(window).width(),
        maxSlides,minSlides;


    jQuery('.backtoTop').bind('click',function(){
        jQuery('html, body').animate({scrollTop:0},500);
    });
    

    jQuery(window).scroll(function(){
        var top = jQuery(window).scrollTop();
        if(top >= 200){
            jQuery('.backtoTop').fadeIn('fast');
        }else{
            jQuery('.backtoTop').fadeOut('fast');
        }
    });

    jQuery(document).on('click touchstart','.closePopup,.shadow',function(){
        jQuery('.shadow,.popup').remove();
    });


    jQuery(".calendar").datepicker();

    

    jQuery('.popupTerm').bind('click',function(){
        
           var url = jQuery(this).attr('href');
           jQuery('body').append('<div class="shadow"></div><div class="popup popupDefault fixScreen"><div class="container content paddingSide"></div><a href="#" class="closePopup" style="margin-bottom: 15px;">Agree</a></div>');
        
           jQuery('.popup .paddingSide').load(url+' .content',function(data){
            //console.log(data)
           });
           return false;
    });


    var subMenu = jQuery('.submenuCountry');
    jQuery(document).on('mouseover','.submenuCountry #submenuCountry',function(){
        subMenu.show();
        subMenu.find('#submenuCountry').show();
        jQuery('.destinationMenu > a').addClass('hover');
    }).on('mouseout','.submenuCountry #submenuCountry',function(){
        subMenu.hide();
        subMenu.find('#submenuCountry').hide();
        jQuery('.destinationMenu > a').removeClass('hover');
    }).on('mouseover','.submenuCountry #submenuAbout',function(){
        subMenu.show();
        subMenu.find('#submenuAbout').show();
        jQuery('.aboutMenu > a').addClass('hover');
    }).on('mouseout','.submenuCountry #submenuAbout',function(){
        subMenu.hide();
        subMenu.find('#submenuAbout').hide();
        jQuery('.aboutMenu > a').removeClass('hover');
    }).on('mouseover','.submenuCountry #submenuContact',function(){
        subMenu.show();
        subMenu.find('#submenuContact').show();
        jQuery('.contactMenu > a').addClass('hover');
    }).on('mouseout','.submenuCountry #submenuContact',function(){
        subMenu.hide();
        subMenu.find('#submenuContact').hide();
        jQuery('.contactMenu > a').removeClass('hover');
    });



    /*jQuery('.submenuCountry').hover(function(e){
        jQuery('.submenuCountry').show();
        jQuery('.submenuCountry #submenuCountry').show();
        jQuery('.destinationMenu > a').addClass('hover');
    },function(){
        jQuery('.submenuCountry').hide();
        jQuery('.submenuCountry #submenuCountry').hide();
        jQuery('.destinationMenu > a').removeClass('hover');
    });*/

    jQuery(document).on('mouseover','.destinationMenu > a',function(){
        subMenu.show();
        subMenu.find('#submenuCountry').show();
        jQuery(this).addClass('hover');
    }).on('mouseout','.destinationMenu > a',function(){
        subMenu.hide();
        subMenu.find('#submenuCountry').hide();
        jQuery(this).removeClass('hover');
    }).on('mouseover','.aboutMenu > a',function(){
        subMenu.show();
        subMenu.find('#submenuAbout').show();
        jQuery(this).addClass('hover');
    }).on('mouseout','.aboutMenu > a',function(){
        subMenu.hide();
        subMenu.find('#submenuAbout').hide();
        jQuery(this).removeClass('hover');
    }).on('mouseover','.contactMenu > a',function(){
        subMenu.show();
        subMenu.find('#submenuContact').show();
        jQuery(this).addClass('hover');
    }).on('mouseout','.contactMenu > a',function(){
        subMenu.hide();
        subMenu.find('#submenuContact').hide();
        jQuery(this).removeClass('hover');
    });


    


    jQuery('a.btnUpload,#valFile').bind('click',function(){
        jQuery('#upload').click();
    });
    jQuery('#upload').change(function(){
        var t = jQuery(this).val();
        jQuery('#valFile').val(t);
    });


    var flag = false;
    jQuery(document).on('mouseover','#modalbox > div',function(){
        flag = true;
    }).on('mouseout',function(){
        flag = false;
    });

    jQuery('a.learnMore').bind('click',function(){
        var t = jQuery(this);
        jQuery('body').addClass('openPopup').append('<div id="modalbox" />');
        switch(t.data('rel')){
            case 'description':
                var title = t.parent().find('.title').text();
                var description = t.parent().find('.subtitle').html();
                var room = t.parent().find('.room').html();
                var price = t.parent().find('> .price_content').html();
                var link = t.parent().find('a.booknow').attr('href');
                
                var html = '<h4>'+title+'</h4>';
                    html += '<p>'+description+'</p>';
                    html += '<p>'+room+'</p>';
                    html += '<p class="price">'+price+'</p>';
                    html += '<a href="'+ link +'" class="booknow" target="_blank">'+t.parent().find('a.booknow').text()+'</a>';
                    html += '<a class="closePopup">Close</a>';
                var popupType = '<div class="descriptionPopup">'+html+'</div>';


                jQuery('#modalbox').append(jQuery(popupType));
                break;
            case 'gallery':
                jQuery.ajax({
                    url: 'index.php?option=com_ariva&task=hotelitem.loadImagesHotel',
                    type: 'POST',
                    data: { id: t.parents('li').find('.apartment_id').val()},                    
                    success: function(data) {
                        var popupType = '<div class="galleryPopup"><a class="closePopup">Close</a>'+data+'</div>';
                        jQuery('#modalbox').html(jQuery(popupType));

                        /*jQuery("#modalbox .thumbsGallery").wowSlider({
                            effect:"basic", 
                            prev:"<", 
                            next:">", 
                            duration: 1000,
                            width:1024,
                            height:768,
                            autoPlay:false,
                            autoPlayVideo:false,
                            playPause:false,
                            caption: true,
                            controls:true,
                            controlsThumb:false,
                            responsive:1,
                            fullScreen:false,
                            bullets:0,
                            images:0,
                            captionEffect:"slider",
                        });*/



                        var popupGallery = jQuery("#modalbox .thumbsGallery ul").owlCarousel({
                            itemsDesktop : [1199, 1],
                            itemsDesktopSmall : [979, 1],
                            itemsTablet: [768,1],
                            itemsMobile: [479,1],
                            items : 1,
                            navigation: true,
                            pagination: true,
                            afterInit: afterOWLinit,
                            //afterUpdate: afterOWLinit
                        });
                        if(typeof popupGallery.data('owlCarousel') != 'undefined') {
                            jQuery(window).resize(function(){
                                popupGallery.data('owlCarousel').destroy();
                                setTimeout(function(){
                                    popupGallery.owlCarousel({
                                        itemsDesktop : [1199, 1],
                                        itemsDesktopSmall : [979, 1],
                                        itemsTablet: [768,1],
                                        itemsMobile: [479,1],
                                        items : 1,
                                        navigation: true,
                                        pagination: true,
                                        afterInit: afterOWLinit,
                                        //afterUpdate: afterOWLinit
                                    });
                                },1000)
                            }); 
                        }
                        
                        
                        var $booknow = '';
                        if(t.parent().parent().find('a.booknow').attr('href')){
                            $booknow = '<a href="'+ t.parent().parent().find('a.booknow').attr('href') +'" target="_blank" class="booknow">Book Now</a>';
                        }
                        
                        jQuery('.galleryPopup').append('<div class="rightPopup"><p class="price">'+t.parent().parent().find('p.price').text()+'</p>'+$booknow+'</div>');
                    },
                    error: function(e) {
                        console.log(e.message);
                    }
                });
                break;
            case 'video':
                var data = t.attr('href');
                var title = t.parent().parent().find('strong').text();
                var popupType = '<div class="videoPopup"><a class="closePopup">Close</a><h4>'+title+'</h4><iframe width="100%" height="315" src="'+data+'" frameborder="0" allowfullscreen></iframe></div>';
                jQuery('#modalbox').append(jQuery(popupType));
                break;
            default:
                break;
        }
        return false;

    });


    jQuery(document).on('click touchstart','.closePopup',function(){
        jQuery('#modalbox').remove();
        jQuery('body').removeClass('openPopup');
    });


    jQuery('a.icon-search').bind('click',function(){
        if(jQuery(this).hasClass('showSearch')){
            jQuery(this).removeClass('showSearch');
            jQuery('#top .search').hide();
        }else{
            jQuery(this).addClass('showSearch');
            jQuery('#top .search').show();
        }
    });
    jQuery(document).on('click','.readmore',function(){
        if(jQuery(this).hasClass('showed')){
            jQuery(this).parents('.re-reviewContent').find('.expanded').show();
            jQuery(this).parents('.re-reviewContent').find('.more').hide();
            jQuery(this).removeClass('showed');
        }else{
            jQuery(this).parents('.re-reviewContent').find('.expanded').hide();
            jQuery(this).parents('.re-reviewContent').find('.more').show();
            jQuery(this).addClass('showed').remove();
        }
    });

    jQuery("select").not(':hidden').select2({
        dropdownAutoWidth : 'true',
    });
    

    jQuery('#adminForm').validate();
    jQuery.validator.addMethod("selectRequired", function(value, element, arg){
        return arg != value;
    }, jQuery.validator.format("Please select a option!"));  

    jQuery.validator.addMethod('phonenumber', function(phone_number, element) {
        return phone_number.match(/^\+?(?:[0-9] ?){1,14}[0-9]$/);
    }, 'Please specify a valid phone number');


    jQuery(window).resize(function(){
        jQuery("select").not(':hidden').select2({
            dropdownAutoWidth : 'true',
        });
    });
    
});



jQuery(window).load(function(){
    setHeightItem();
});

function afterOWLinit() {

    var owl = jQuery("#modalbox .thumbsGallery ul");
    // adding A to div.owl-page
    $('.owl-controls .owl-page').append('<a class="item-link" href="#"/>');

    var pafinatorsLink = $('.owl-controls .item-link');
    $.each(this.owl.userItems, function (i) {
         /* $(pafinatorsLink[i]).append('<img src="'+$(this).find('img').attr('src')+'" alt="thumbsGallery'+i+'" height="100%" width="100%" />')
        $(pafinatorsLink[i]).click(function () {
                owl.trigger('owl.goTo', i);
                return false;
            });*/

      $(pafinatorsLink[i]).css({
                'background': 'url(' + $(this).find('img').attr('src') + ') center center no-repeat',
                '-webkit-background-size': 'cover',
                '-moz-background-size': 'cover',
                '-o-background-size': 'cover',
                'background-size': 'cover'
            }).click(function () {
                owl.trigger('owl.goTo', i);
                return false;
            });

    });
    var thub = jQuery("#modalbox .thumbsGallery .owl-pagination");

    thub.owlCarousel({
            itemsDesktop : [1199, 6],
            itemsDesktopSmall : [979, 6],
            itemsTablet: [768,5],
            itemsMobile: [479,3],
            item: 6,
            navigation: true,
            pagination: false,
        });



}

function setHeightItem(){
    if(jQuery('.listHotel li').length){
        var heightItemHotel = 0;
        jQuery('.listHotel li').each(function(){
            if(jQuery(this).height() > heightItemHotel){
                heightItemHotel = jQuery(this).height();
            }
        });
        jQuery('.listHotel li').not(':hidden').height(heightItemHotel);
    }
}