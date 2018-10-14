$(function(){

    var bodyH = document.documentElement.clientHeight;
    var bodyW = document.documentElement.clientWidth;


    $("table.pro td").click(function(){
        $("table.pro td.btn span").css("display","block");
        $("table.pro td.btn input").css("display","none");
    })
    $("table.pro td.btn").click(function(){
        $(this).find("span").css("display","none");
        $(this).find("input").css("display","block");
    })


    $(".tc").css("top",bodyH/2-92);
    $(".tc").css("left",bodyW/2-200);


    $(".sub_content .tab a").click(function(){
        var tabin = $(".sub_content .tab a").index(this);
        $(".sub_content .tab a").removeClass("hover").eq(tabin).addClass("hover");
        $(".sub_content_box").css("display","none").eq(tabin).css("display","block");
    }).eq(0).click();


    var blen = $(".home_top_info li").length;
    $(".home_top_info ul").append($(".home_top_info ul").html());
    var bin = 0;
    var bTimer;
    $(".home_top_info ul").css("top",-blen*24);
    $(".home_top_info").hover(function(){
        clearInterval(bTimer);
    },function(){
        bTimer = setInterval(function(){
            bin++;
            if(bin==blen){bin=0;$(".home_top_info ul").css("top",-(blen-1)*24);}
            $(".home_top_info ul").animate({top:-(bin+blen)*24},500)
        } , 3500);
    }).mouseleave();


    $(".job_box table.tab td p.sc a").click(function(){
        if($(this).hasClass("hover")){
            $(this).removeClass("hover");
        } else {
            $(this).addClass("hover");
        }
    });
});


$(window).resize(function(){
    var bodyH = document.documentElement.clientHeight;
    var bodyW = document.documentElement.clientWidth;
    $(".tc").css("top",bodyH/2-92);
    $(".tc").css("left",bodyW/2-200);
});


