jQuery(document).ready(function($){
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
    
    $(".horoscope-list li").mouseover(function(){ $(this).addClass("state-active") }).
        mouseout(function(){ $(this).removeClass("state-active") });
    
    $(".realty-slides").itemSlider(2000);
});


