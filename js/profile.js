
jQuery(document).ready(function($){
    $("#change-password").click(function(){
        $(".password.overlay").toggle();
        return false;
    });
    
    $(".gradient").mouseover(function(){
        $(this).addClass("state-active");
    }).mouseout(function(){
        $(this).removeClass("state-active");
    });
    
    $("select").selectBox();

    $(".subscribers-list-block h3").click(function(){
        $(this).parent().parent().find(".body").slideToggle();
    });

    $(".hover").hover(function(){
        $(this).find(".manage").show();
    },function(){
        $(this).find(".manage").hide();
    });
});