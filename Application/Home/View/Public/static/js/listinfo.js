function mergeModalShow(id) {
    $.ajax({
        url:"getMergeList",
        type:"post",
        data:{
            id:id
        },
        dataType:"json",
        success:function(data){
            if(data.code == 1){
                var html='';
                data.data.forEach(function (item) {
                    html +='<tr>\
                        <td><input type="radio" name="merge-radio" value="'+item.id+'" /></td>\
                        <td>'+item.id+'</td>\
                        <td>'+item.nickname+'</td>\
                        <td><img src="'+item.hslogo+'"></td>\
                        </tr>';
                });
                $('#modal-merge table tbody').html(html);
            }else{
                alert(data.msg);
            }
        },
        error:function(xmlHttpRequest,textStatus,errorThrown){
            alert(textStatus+"出错！"+errorThrown);
        }
    });
    $('input[name="first_id"]').val(id);
    $('#modal-merge').modal('show');
    //

}

$('#merge_commit').click(function () {
    var sec_id = $('input[name="merge-radio"]:checked').val();
    if (sec_id == '') return false;
    $.ajax({
        url: "mergeApp",
        type: "post",
        data: {
            first_id: $('input[name="first_id"]').val(),
            sec_id: sec_id
        },
        dataType: "json",
        success: function (data) {
            if (data.code == 1) {
                $('#modal-merge').modal('hide');
            } else {
                alert(data.msg);
            }
        },
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            alert(textStatus + "出错！" + errorThrown);
        }
    });
});

function updateIcon(d) {
    $.ajax({
        url: "updateIcon",
        type: "post",
        data: {
            id: d,
        },
        dataType: "json",
        success: function (data) {
            alert(data.msg);
        },
        error: function (xmlHttpRequest, textStatus, errorThrown) {
            console.log(textStatus + "出错！" + errorThrown);
        }
    });
}


