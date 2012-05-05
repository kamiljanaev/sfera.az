jQuery(document).ready(function($){
    $(".scroller").scrollbar();
    
    $(".hidelabel").focus(function(){
        $(this).prev().hide();    
    }).blur(function(){
        if(!$(this).val())
            $(this).prev().show();    
    }).each(function(){
        if(!$(this).val())
            $(this).prev().show();    
    });
    
    $(".stars-slide").mouseenter(function(){
        $(this).find(".stars-slide-label").slideLeftShow();
    }).mouseleave(function(){
        $(this).find(".stars-slide-label").slideLeftHide();
    });
    

    $("#posts-hot .posts-hot-item").mouseenter(function(){
        $(this).find(".post-info").slideDown();
    }).mouseleave(function(){
        $(this).find(".post-info").slideUp();
    });
    
    $(".post-list li").mouseover(function(){
        $(this).addClass("state-active");
    }).mouseout(function(){
        $(this).removeClass("state-active");
    })
    
    $( "#news-tabs" ).tabs({
        tabTemplate: '<div><a href="#{href}"><span>#{label}</span></a></div>'
    });
    
    $(".horoscope-list li").mouseover(function(){ $(this).addClass("state-active") }).
        mouseout(function(){ $(this).removeClass("state-active") });
    
    $(".posts-strips").tabs();

    $(".news-categories").itemSlider(2000);
    $(".realty-slides").itemSlider(2000);
});


