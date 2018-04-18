jQuery(document).ready(function() {

    $('dropdown').data('open', false);
    $('dropdown_dash').data('open', false);

    $('#dropdown-button').click(function() {
        if($('#dropdown').data('open')) {
            $('#dropdown').data('open', false);
        } else
            $('#dropdown').data('open', true);
            setAllRead();
    });

    $('.notification-list').slimScroll({
        height: 350,
    });

    if (userKey) {
        $.ajax({
            type : "POST",
            url  : "/notification/getNotifications",
            dataType: 'json',
            beforeSend: function( xhr ) {
            },
            success : function(result, textStatus, jqXHR) {
                $('#notificationCount').html(result.count);
                var notifyContent = '';
                var notifyFooter = '';
                if (result.list.length > 0) {
                    $('#notificationCount').removeClass('hide');
                    $.each(result.list, function(k, item) {
                        notifyContent += '<div class="notification">';
                        if (item.nc_type == 1) {
                            notifyContent += '<span class="notification-icon"><i class="fa fa-envelope text-primary"></i></span>';
                        } else if (item.nc_type == 2) {
                            notifyContent += '<span class="notification-icon"><i class="fa fa-quote-right text-primary"></i></span>';
                        } else {
                            notifyContent += '<span class="notification-icon"><i class="fa fa-envelope text-primary"></i></span>';
                        }

                        notifyContent += '<span class="notification-description">'+ item.nc_content +'</span>';
                        notifyContent += '<span class="notification-time">'+ item.nc_created +'</span>';
                        notifyContent += '</div> <!-- / .notification -->';
                    });
                } else {
                    notifyContent += '<div style="text-align:center;margin-top:120px;">';
                    notifyContent += '<h4 class="notification-empty-title">'+ noNotificationTitle +'</h4>';
                    notifyContent += '<p class="notification-empty-text">'+ noNotificationDescription +'</p>';
                    notifyContent += '</div>';
                }

                $('#notifyContent').html(notifyContent);
            },
                error : function(jqXHR, textStatus, errorThrown) {
            }
        });
    }
});

function setAllRead()
{
    $.ajax({
        type: "POST",
        url : "/notification/setAllRead",
        dataType: 'json',
        beforeSend: function(xhr) {
        },
        success: function(result, textStatus, jqXHR) {
            if (!$('#notificationCount').hasClass('hide')) {
                $('#notificationCount').addClass('hide');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
        }
    });
}
