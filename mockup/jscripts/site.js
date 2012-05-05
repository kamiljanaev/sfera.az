jQuery(document).ready(function($){
    jQuery.fn.extend({
      slideRightShow: function(duration) {
        return this.each(function() {
            $(this).show('slide', {direction: 'right'}, duration);
        });
      },
      slideLeftHide: function(duration) {
        return this.each(function() {
          $(this).hide('slide', {direction: 'left'}, duration);
        });
      },
      slideRightHide: function(duration) {
        return this.each(function() {
          $(this).hide('slide', {direction: 'right'}, duration);
        });
      },
      slideLeftShow: function(duration) {
        return this.each(function() {
          $(this).show('slide', {direction: 'left'}, duration);
        });
      }
    });
    
    jQuery.fn.extend({
        itemSlider: function(duration) {
            var width = 0;
            var obj = this;
            obj.find("li").each(function(){
                width += $(this).width() + parseInt($(this).css("padding-left")) + parseInt($(this).css("padding-right"));
            });
            
        
            var window_width = obj.width()-obj.find(".scroll").width()*2;
            
            if(width>window_width)
                this.find(".scroll.next").removeClass("disabled");
                
            var duration_left = duration_right = duration;
            var scroll_width = window_width-width;
            obj.find(".scroll.next").mouseover(function(){
                obj.find("ul").animate({
                    marginLeft:scroll_width
                },duration_right,function(){
                     obj.find(".scroll.next").addClass("disabled");
                });        
            }).mouseleave(function(){
                if (parseInt(obj.find("ul").css("margin-left")) > scroll_width + 100 )
                    obj.find("ul").stop(true);
                duration_left = parseInt(obj.find("ul").css("marginLeft"))/scroll_width * duration;
                duration_right = (-parseInt(obj.find("ul").css("marginLeft"))+scroll_width)/scroll_width * duration;
                obj.find(".scroll.prev").removeClass("disabled");
            });
        
            obj.find(".scroll.prev").mouseover(function(){
                obj.find("ul").animate({
                    marginLeft:0
                },duration_left,function(){
                    obj.find(".scroll.prev").addClass("disabled");
                });        
            }).mouseleave(function(){
                if (parseInt(obj.find("ul").css("margin-left")) < - 100 )
                    obj.find("ul").stop(true);
                duration_left = parseInt(obj.find("ul").css("marginLeft"))/scroll_width * duration;
                duration_right = (-parseInt(obj.find("ul").css("marginLeft"))+scroll_width)/scroll_width * duration;
                obj.find(".scroll.next").removeClass("disabled");
            });            
        }
    })

    jQuery.fn.extend({
        scrollbar: function () {
            obj = this;
            
            obj.each(function(){
                $(this).html('<div class="viewport"><div class="overview">'+$(this).html()+'</div></div>');    
            });
            obj.prepend('<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>');
            obj.tinyscrollbar();
        }
    });

    var default_color =  $("#header-menu").css("border-top-color");
    $("#header-menu li").not(".all-services").mouseover(function(){
        $(this).removeClass("item-inactive"); 
        $(this).parents('.menu').css("border-top-color",$(this).css("background-color"));
    }).mouseout(function(){
        if(!$(this).hasClass("all-services")) {
            $(this).addClass("item-inactive");
            $(this).parents('.menu').css("border-top-color",default_color);
        }
    });
    
    $("#header .search").tabs();

        
    $(".hidelabel").focus(function(){
        $(this).prev().hide();    
    }).blur(function(){
        if(!$(this).val())
            $(this).prev().show();    
    }).each(function(){
        if(!$(this).val())
            $(this).prev().show();    
    });

    $(".scroller").scrollbar();

    $( "#news-tabs" ).tabs({
        tabTemplate: '<div><a href="#{href}"><span>#{label}</span></a></div>'
    });
    
    $(".posts-strips").tabs();

    $(".news-categories").itemSlider(2000);
});