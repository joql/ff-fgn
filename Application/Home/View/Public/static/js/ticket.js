jQuery(document).ready(function() {
    if (userKey) {
        $.ajax({
            type : "POST",
            url  : "/ticket",
            dataType: 'json',
            beforeSend: function( xhr ) {
            },
            success : function(result, textStatus, jqXHR) {
                if (result.code == 0 && result.data['count'] > 0) {
                    $('#ticketCount').html(result.data['count']);
                    $('#ticketCount').removeClass('hide');
                }
            },
                error : function(jqXHR, textStatus, errorThrown) {
            }
        });
    }
});
