$(document).ready(function(){
	/*banner切换*/
	var url = window.location.href.toLowerCase().replace("http://","");
	if(url.indexOf("hr") > 0){
		$(".h_menu li").removeClass("active");
	    $("#nav_hr").addClass("active");
	}
	else if(url.indexOf("resume") > 0){
		$(".h_menu li").removeClass("active");
	    $("#nav_person_center").addClass("active");
	}
	else{
		$(".h_menu li").removeClass("active");
	    $("#nav_home").addClass("active");
	}
	
	/*登录后头部菜单切换*/
	$(".per_name").click(function(){
		$("#per_login").toggleClass("hover");
		$("#per_login > .quick_links").toggleClass("hide");
	});
});

/*消除按钮虚线框*/
$(function(){ 
    $('a,map,area,input[type="button"],input[type="submit"],input[type="checkbox"],input[type="radio"]').bind('focus',function(){ 
        if(this.blur){ 
           this.blur(); 
        }; 
    }); 
}); 

/*右则滚动*/
$(function(){
    var BoxMarginTop = $(".arrow_icon").scrollTop();
    $(window).scroll(function(){
		var top = $(document).scrollTop() + ($(window).height() - $(".arrow_icon").height()) / 2;
		$(".arrow_icon").animate({'top':top},{duration:400,queue:false});
	});
});

/*提示信息*/
function alertNews(News){
	$(".alert").html(News);
	var w = $(".alert").width();
	var h = $(".alert").height();
	var left = ($(window).width() - w) / 2;
	var top = $(document).scrollTop() + ($(window).height() - h) / 2 - 100;
	$(".alert").show();
	$(".alert").css({"left":left,"top":top});
	$(".alert").delay(5000).fadeOut(200); 
}

/*提示信息*/
function lodding(News){
	$(".alert").html(News);
	var w = $(".alert").width();
	var h = $(".alert").height();
	var left = ($(window).width() - w) / 2;
	var top = $(document).scrollTop() + ($(window).height() - h) / 2 - 100;
	$(".alert").show();
	$(".alert").css({"left":left,"top":top});
}

/*提示信息*/
function loddingexit(){
	$(".alert").hide();
}