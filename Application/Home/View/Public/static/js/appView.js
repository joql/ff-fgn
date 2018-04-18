function isMobile() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}
function install() {
    if (aType == 'ios') {
        if (!isMobileRequest) {
            alert(askBrowserAlert);
            return;
        }
    }

    if (aType == 'ios' && browseType == 'android') {
        alert(forIosAlert);
        return;
    } else if (aType == 'android' && browseType == 'ios') {
//        alert(forAndroidAlert);
//        return;
    }

//    if ( isWechatRequest && aType == 'android') {
    if ( isWechatRequest) {
        alert(reminderWechatContent);
        return;
    }

    if (isQQRequest && aType == 'android') {
        alert(reminderQQContent);
        return ;
    }

    if (isUCRequest) {
        alert(reminderUCContent);
        return ;
    }

    url = appdownurl + appkey;
    window.location.href = url;
}

function install_loading() {
    if (aType == 'ios') {
        if (!isMobileRequest) {
            alert(askBrowserAlert);
            return;
        }
    }

    if (aType == 'ios' && browseType == 'android') {
        alert(forIosAlert);
        return;
    } else if (aType == 'android' && browseType == 'ios') {
//        alert(forAndroidAlert);
//        return;
    }

//    if ( isWechatRequest && aType == 'android') {
    if ( isWechatRequest) {
        alert(reminderWechatContent);
        return;
    }

    if ( isWeiboRequest) {
        alert(reminderWeiboContent);
        return;
    }

    if (isQQRequest && aType == 'android') {
        alert(reminderQQContent);
        return ;
    }

    if (isUCRequest) {
        alert(reminderUCContent);
        return ;
    }

    if(aType == 'ios'){
        $("#down_load").hide();
        $(".loading").css("display","inline-block");
        setTimeout('check()',5000);
    }

    url = appdownurl + appkey;
    window.location.href = appdownurl;
}
 function check() {
    $(".loading").hide();
    $("#showtext").show();
 }

function saveData() {
    $.ajax({
        type : "POST",
        data : $('#form').serialize(),
        dataType: 'json',
        beforeSend: function( xhr ) {
            if (isMobileRequest) {
                $('#submitButton').text( submiting ).attr('disabled', 'disabled');
            } else {
                $('#submitButton').text( submiting ).addClass('btn-u-default').attr('disabled', 'disabled');
            }
        },
        success : function(result, textStatus, jqXHR) {         
            code = result.code;
            if (code == 0) {
                window.location.reload();
            } else {
                alert(result.message);
                $('#submitButton').text( submitText ).removeClass('btn-u-default').removeAttr('disabled');
            }
        },
        error : function(jqXHR, textStatus, errorThrown) {
            $('#submitButton').text( submitText ).removeClass('btn-u-default').removeAttr('disabled');
        }
    });
}
    
function initView() {
    $('.history_row').click(function() {
       var shorturl = $(this).attr('shorturl');
       window.location.href = appurl + shorturl;
    });

    $('.history_show_more').click(function() {
       $('.history_row').show();
       $(this).hide();
    });
}

//ggowo
function is_weixin(){
	var ua = navigator.userAgent.toLowerCase();
	if(ua.match(/MicroMessenger/i)=="micromessenger") {
		return true;
	} else {
		return false;
	}
}
function install_loading2(){
	var browser = {
		versions: function() {
			var u = navigator.userAgent, app = navigator.appVersion;
			return {
				trident: u.indexOf('Trident') > -1,
				presto: u.indexOf('Presto') > -1,
				webKit: u.indexOf('AppleWebKit') > -1,
				gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,
				mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/),
				ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),
				android: u.indexOf('Android') > -1 || u.indexOf('android') > -1,
				iPhone: u.indexOf('iPhone') > -1,
				iPad: u.indexOf('iPad') > -1,
				webApp: u.indexOf('Safari') == -1
			};
		}()
	}
	if (aType == 'ipa'){
		if (browser.versions.ios || browser.versions.iPhone || browser.versions.iPad) {
			//寰俊鎵撳紑鏃� 绯荤粺涓篿os
			if(is_weixin()){
				$('#weixin').show();
				return ;
			}
		} else {
			//寮瑰嚭鎻愮ず涓嶆槸 ios 璁惧
			alert(askBrowserAlert);
			return ;
		}
	} else {
		if (browser.versions.android) {
			//寰俊鎵撳紑鏃� 绯荤粺涓哄畨鍗�
			if(is_weixin()){
				$('#weixin2').show();
				return ;
			}
		} else {
			//寮瑰嚭鎻愮ず涓嶆槸 瀹夊崜 璁惧
			alert(forAndroidAlert);
			return ;
		}
	}
	if (isallowdown == '0') {
		alert(notallowdowninfo);
		return ;
	}
	
	//涓嬭浇
	if (aType == 'ipa'){
		$("#down_load").hide();
		$(".loading").css("display","inline-block");
		setTimeout('check()',5000);
	}
	window.location.href = appdownurl + appkey;
}