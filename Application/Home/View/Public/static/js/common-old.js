var $zip;
var domain = "http://asp.shqiche.net/";
var myDropzone = null;
var bInfoReady = false;
var bUploaded = false;
var bLogoReady = false;
var sAppName,sLogName,iVersion,sPackageName,iVersionCode,sDownLink,iAppSize,sext;
$(function () {

    $.ajax({
        type:"GET",
        url:"./index.php?m=&c=index&a=gettoken",
        success:function (token)
        {
            //引入Plupload 、qiniu.js后
            var uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',    //上传模式,依次退化
                browse_button: 'uploadstart',       //上传选择的点选按钮，**必需**
                //uptoken_url: '/gettoken.php',        //Ajax请求upToken的Url，**强烈建议设置**（服务端提供）
                uptoken : token, //若未指定uptoken_url,则必须指定 uptoken ,uptoken由其他程序生成
                // unique_names: true, // 默认 false，key为文件名。若开启该选项，SDK为自动生成上传成功后的key（文件名）。
                save_key: true,   // 默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK会忽略对key的处理
                domain: 'http://ob8l7s2t0.bkt.clouddn.com/',   //bucket 域名，下载资源时用到，**必需**
                get_new_uptoken: false,  //设置上传文件的时候是否每次都重新获取新的token
                container: 'container',           //上传区域DOM ID，默认是browser_button的父元素，
                flash_swf_url: 'js/plupload/Moxie.swf',  //引入flash,相对路径
                max_retries: 3,                   //上传失败最大重试次数
                dragdrop: true,                   //开启可拖曳上传
                drop_element: 'container',        //拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
                chunk_size: '4mb',                //分块上传时，每片的体积
                auto_start: true,                 //选择文件后自动上传，若关闭需要自己绑定事件触发上传
                multi_selection: false,          //限制只能选择一个 为true可多选
                filters : {
                    max_file_size: '500mb',         //最大文件体积限制
                    prevent_duplicates: false,      //是否防止重复文件
                    // Specify what files to browse for
                    mime_types: [
                        {title : "app files", extensions : "apk"}, // 限定apk后缀上传格式上传
                        {title : "ipa files", extensions : "ipa"}// 限定ipa后缀上传
                    ]
                },
                init: {
                    'FilesAdded': function(up, files) {

                        plupload.each(files, function(file) {
                            var file = file.getNative();
                            var zip = new JSZip();
                            $zip = zip;
                            zip.loadAsync(file).then(function (data) {
                                var reg = /^(([^\\\?\/\*\|<>:"]+\/){2})Info\.plist/;
                                var infoplist = zip.file(reg);
                                var resoucesArsc = zip.file(/resources.arsc/);
                                var androidManifest = zip.file(/AndroidManifest.xml/);
                                parse(infoplist, resoucesArsc, androidManifest)
                            });

                        });
                    },
                    'BeforeUpload': function(up, file) {

                        $(".tolsize").html("文件大小"+(file.size/1024/1024).toFixed(2)+"MB");
                        $("#upprocess").show();
                        $("#upbtn").hide();
                        //alert("上传前函数被执行"+JSON.stringify(file));
                    },
                    'UploadProgress': function(up, file) {
                        // 每个文件上传时,处理相关的事情
                        $(".alreadyup").html("已上传"+(file.loaded/1024).toFixed(2)+"KB");
                        $(".progress-bar").css("width",file.percent+"%");
                        $(".moxie-shim").hide();

                    },
                    'FileUploaded': function(up, file, info) {

                        //文件上传结束后的函数 发送参数到后台API即可

                        var domain = up.getOption('domain');
                        //var res = JSON.parse(info);
                        //var sourceLink = domain + file.name;
                        var apkName = file.name;
                        var apkSize = file.size;
                            sext = Qiniu.getFileExtension(file.name);
                        sDownLink = apkName;
                        iAppSize = apkSize;

                        bUploaded = true;

                        if(!bInfoReady||!bLogoReady)
                        {
                            $(".qq-drop-processing span").eq(0).html("正在获取应用消息……请稍后");
                        }
                        else
                        {
                            ajaxStoreInfo ();
                        }

                        /*$.ajax({
                            type: 'POST',
                            url: "index.php?m=&c=index&a=upa",
                            dataType:"",
                            data:{"filesize":apkSize,"filename":apkName},
                            success: function (json) {
                                window.location.href="index.php?m=&c=index&a=upa";
                                //ajax发送成功函数 写在这里
                            },
                            error: function (data) {
                                //ajax发送失败函数写在这里
                            }
                        });*/

                    },
                    'Error': function(up, err, errTip) {
                        //上传失败函数
                    },
                    'UploadComplete': function() {




                    },
                    'Key': function(up, file) {
                        // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                        // 该配置必须要在 unique_names: false , save_key: false 时才生效

                        var key = "";
                        // do something with key here
                        return key
                    }
                }
            });

            //暂停上传恢复上传
            $("#changest").click(function () {
                if($(this).hasClass("pause"))
                {
                    uploader.start();
                    $(this).removeClass("pause");
                    $(this).text("暂停上传");
                }
                else
                {
                    $(this).addClass("pause");
                    $(this).text("恢复上传");
                    uploader.stop();
                }

            });


        },
        error:function ()
        {
            //获取token失败函数

        }
    })

    //提交上传
    $("#submit").on("click", function () {
        $("input[name='hlogo']").val($("#image").attr("src"));
        $("input[name='happName']").val($("#appName").val());
        $("input[name='hversion']").val($("#version").val());
        $("input[name='hpackageName']").val($("#packageName").val());
        $("input[name='hversionCode']").val($("#versionCode").val());
        myDropzone.processQueue();
    });

    //重新选择
    $("#reset").on("click", function () {
        location = location;
    });

    function parse(infoplist, resoucesArsc, androidManifest) {
        var data = {};
        if (androidManifest.length > 0) {
            data.system = "android";
            resoucesArsc[0].async("uint8array").then(function (arsc) {
                androidManifest[0].async("uint8array").then(function (mainifest) {
                    data.arsc = Base64.encode(arsc);
                    data.mainifest = Base64.encode(mainifest);
                    callback(data);
                });
            });
        } else if (infoplist.length > 0) {
            data.system = "ios";
            infoplist[0].async("uint8array").then(function (d) {
                data.plist = Base64.encode(d);
                callback(data);
            });
        }

        function callback(data) {
            showLoading();
            $.ajax({
                url: domain+"GetAppInfo.ashx",
                type: "POST",
                data: data,
                cache: false,
                success: function (d) {

                    if (d.Success) {
                        ajaxCallBack(d,data.system);
                        sAppName = d.Name;
                        iVersion = d.Version;
                        sPackageName = d.PackageName;
                        iVersionCode = d.VersionCode;

                        //成功获取应用消息
                        bInfoReady= true;
                        if(bUploaded&&bLogoReady)
                        {
                            ajaxStoreInfo ();
                        }



                    } else {
                        hideLoading();
                        alert("获取不到安装包信息!");
                    }
                },
                error: function () {
                    hideLoading();
                    alert("error");
                }
            });
        }
    }
});

function showLoading() {
    $("#full,#loading").fadeIn();
}
function hideLoading() {
    $("#full,#loading").fadeOut();
}

function ajaxCallBack(d, sys) {
    $("input[name='hsysname']").val(sys);
    //切换
    $(".zone,.tip").hide();
    //$(".info").show();
    //给字段赋值hlogo
    $("input[name='happName']").val(d.Name);
    $("input[name='hversion']").val(d.Version);
    $("input[name='hpackageName']").val(d.PackageName);
    $("input[name='hversionCode']").val(d.VersionCode);
    //$("#logo").val(d.LogoName);
    var logoType = logotype(d.LogoName);
    //如果是ios并且没有扩展名称
    if (sys == "ios" && d.LogoName.indexOf(".") == -1) {
        d.LogoName = "/" + d.LogoName + "([\\d]+x[\\d]+)?(@[\\d]x)?.png";
    } else if (sys == "ios") {
        d.LogoName = "/" + d.LogoName;
    }
    
    //图片
    var logo = $zip.file(new RegExp(d.LogoName, "i"));
    if (!logo.length) {
        hideLoading();
        return;
    }
    logo[0].async("uint8array").then(function (d) {
        var dd = Base64.encode(d);
        $.ajax({
            url: domain+"GetString.ashx",
            type: "POST",
            cache: false,
            data: { image: dd , system: sys },
            complete: function () {
                //hideLoading();
               // myDropzone.processQueue();
            },
            error: function () {

            },
            success: function (abc) {
                bLogoReady = true;
                $("input[name='hlogo']").val(abc);
                $("#image").attr("src", "data:" + logoType + ";base64," + abc);
                sLogName = abc;
                if(bInfoReady&&bUploaded)
                {
                    ajaxStoreInfo ();
                }
            }
        });
    });
}

function logotype(filename) {
    if (/(\.|\/)(png)$/i.test(filename)) {
        return 'image/png';
    }
    if (/(\.|\/)(ico)$/i.test(filename)) {
        return 'image/x-icon';
    }
    if (/(\.|\/)(gif)$/i.test(filename)) {
        return 'image/gif';
    }
    if (/(\.|\/)(bmp)$/i.test(filename)) {
        return 'image/bmp';
    }
    if (/(\.|\/)(jpeg|jpg|jpe)$/i.test(filename)) {
        return 'image/jpeg';
    }
    return 'image/png';
}

function ajaxStoreInfo ()
{
    $.ajax({
        type: 'POST',
        url: "index.php?m=&c=index&a=upload",
        dataType:"",
        data:{"hslogo":sLogName,"hext":sext,"happName":sAppName,"hversion":iVersion,"hpackageName":sPackageName,"hversionCode":iVersionCode,"sDownLink":sDownLink,"iAppSize":iAppSize},
        success: function (json) {
            window.location.href="index.php?m=&c=index&a=listinfo";
            //ajax发送成功函数 写在这里
        },
        error: function (data) {
            window.location.href="index.php?m=&c=index&a=listinfo";
            //ajax发送失败函数写在这里
        }
    });
}