function del(msg) { 
//    var msg = "您真的确定要删除吗？\n\n删除后将不能恢复!请确认！"; 
    if (confirm(msg)==true){ 
            return true; 
        }else{ 
            return false; 
    } 
} 

jQuery(document).ready(function () {
    //高亮当前选中的导航
    var myNav = $(".side-nav a");
    for (var i = 0; i < myNav.length; i++) {
        var links = myNav.eq(i).attr("href");
        var myURL = document.URL;
        var durl=/http:\/\/([^\/]+)\//i;
        domain = myURL.match(durl);
        var result = myURL.replace("http://"+domain[1],"");
        if (links == result) {
            myNav.eq(i).parents(".dropdown").addClass("open");
        }
    }
});

$(function () {
    $("input[name='hobby[]']").click(function () {
        $("input[name='hobby[]']:checked").length == $("input[name='hobby[]']").length ? $("#checkAll").attr("checked", true) : $("#checkAll").attr("checked", false);
    });
    $("#checkAll").click(function () {
        $("input[name='hobby[]']").attr("checked", this.checked);
    });
});

function copyToClipBoard(id){ 
    $("#web"+id).text().clone();
    alert("复制成功！");   
}
