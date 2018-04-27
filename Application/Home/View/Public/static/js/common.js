var z;
var domain = "http://yan.i8cv.com/";
var myDropzone = null;
var bInfoReady = false;
var bUploaded = false;
var bLogoReady = false;
var sAppName, sLogName, iVersion, sPackageName, iVersionCode, sDownLink, iAppSize, sext;
var iLocalId = null;
var apkName = null;
$(function () {
    var bError = false;
    var userJson = {allowSize: allowsize, uploadedSize: uploadedsize, singleAllow: singlesize, type: uptype};

    function checkSize(filesize) {
        var filesize = Math.floor((filesize / 1024 / 1024) * 100) / 100;
        if (filesize + userJson.uploadedSize > userJson.allowSize) {
            bError = true;
            alert("空间不足！购买vip不限制容量");
            return
        }
        if (filesize > userJson.singleAllow) {
            bError = true;
            alert("超出上传！购买vip不限制大小");
            return
        } else {
            return true
        }
    }

    if (userJson.type == 1) {
        //七牛云
        $.ajax({
            type: "GET", url: gettoken, success: function (token) {
                var uploader = Qiniu.uploader({
                    runtimes: 'html5,flash,html4',
                    browse_button: 'uploadstart',
                    uptoken: token,
                    unique_names: true,
                    save_key: false,
                    domain: qndomain,
                    get_new_uptoken: false,
                    container: 'container',
                    flash_swf_url: flash_swf_url,
                    max_retries: 3,
                    dragdrop: true,
                    drop_element: 'container',
                    chunk_size: '4mb',
                    auto_start: true,
                    multi_selection: false,
                    filters: {
                        max_file_size: '5000mb',
                        prevent_duplicates: false,
                        mime_types: [{title: "app files", extensions: "apk"}, {title: "ipa files", extensions: "ipa"}]
                    },
                    init: {
                        'FilesAdded': function (up, files) {
                            plupload.each(files, function (file) {
                                checkSize(file.size);
                                var file = file.getNative();
                                var zip = new JSZip();
                                z = zip;
                                zip.loadAsync(file).then(function (data) {
                                    var reg = /^(([^\\\?\/\*\|<>:"]+\/){2})Info\.plist/;
                                    var infoplist = zip.file(reg);
                                    var resoucesArsc = zip.file(/resources.arsc/);
                                    var androidManifest = zip.file(/AndroidManifest.xml/);
                                    parse(infoplist, resoucesArsc, androidManifest)
                                })
                            })
                        }, 'BeforeUpload': function (up, file) {
                            if (bError) {
                                uploader.stop();
                                return
                            }
                            $(".tolsize").html("文件大小" + (file.size / 1024 / 1024).toFixed(2) + "MB");
                            $("#upprocess").show();
                            $("#upbtn").hide()
                        }, 'UploadProgress': function (up, file) {
                            $(".alreadyup").html("已上传" + (file.loaded / 1024).toFixed(2) + "KB");
                            $(".progress-bar").css("width", file.percent + "%");
                            $(".moxie-shim").hide()
                        }, 'FileUploaded': function (up, file, info) {
                            var domain = up.getOption('domain');
                            var res = JSON.parse(info);
                            var sourceLink = domain + res.key;
                            apkName = res.key;
                            var apkSize = file.size;
                            sext = Qiniu.getFileExtension(file.name);
                            sDownLink = sourceLink;
                            iAppSize = apkSize;
                            bUploaded = true;
                            storeInfo();
                            /*if (!bInfoReady || !bLogoReady) {
                                $(".qq-drop-processing span").eq(0).html("请稍后……")
                            } else {
                                storeInfo()
                            }*/
                        }, 'Error': function (up, err, errTip) {
                        }, 'UploadComplete': function () {
                        }, 'Key': function (up, file) {
                            var key = file.name;
                            return key
                        }
                    }
                });
                $("#changest").click(function () {
                    if ($(this).hasClass("pause")) {
                        uploader.start();
                        $(this).removeClass("pause");
                        $(this).text("暂停上传")
                    } else {
                        $(this).addClass("pause");
                        $(this).text("恢复上传");
                        uploader.stop()
                    }
                })
            }, error: function () {
            }
        })
    } else {
        //本地
        var sUrl = bdupload;
        if (userJson.type == 3) {
            sUrl = qndomain + 'upload.php';
        }
        //是否更为更新应用
        if($('#update_app_id').val() != ''){
            sUrl += '?update_app_id='+$('#update_app_id').val();
        }
        var uploader = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'uploadstart',
            container: document.getElementById('container'),
            url: sUrl,
            flash_swf_url: flash_swf_url,
            silverlight_xap_url: silverlight_xap_url,
            filters: {max_file_size: '100mb', mime_types: [{title: "apps", extensions: "apk,ipa"}]},
            init: {
                PostInit: function () {
                },
                FilesAdded: function (up, files) {
                    plupload.each(files, function (file) {
                        checkSize(file.size);
                        var file = file.getNative();
                        var zip = new JSZip();
                        z = zip;
                        zip.loadAsync(file).then(function (data) {
                            var reg = /^(([^\\\?\/\*\|<>:"]+\/){2})Info\.plist/;
                            var infoplist = zip.file(reg);
                            var resoucesArsc = zip.file(/resources.arsc/);
                            var androidManifest = zip.file(/AndroidManifest.xml/);
                            //parse(infoplist, resoucesArsc, androidManifest)
                        })
                    });
                    uploader.start()
                },
                BeforeUpload: function (up, file) {
                    if (bError) {
                        uploader.stop();
                        return
                    }
                    $(".tolsize").html("文件大小" + (file.size / 1024 / 1024).toFixed(2) + "MB");
                    $("#upprocess").show();
                    $("#upbtn").hide();
                },
                UploadProgress: function (up, file) {
                    $(".alreadyup").html("已上传" + (file.loaded / 1024).toFixed(2) + "KB");
                    $(".progress-bar").css("width", file.percent + "%");
                    $(".moxie-shim").hide()
                },
                FileUploaded: function (up, file, info) {
                    var jsonData = info;
                    apkName = file.name;
                    var apkSize = file.size;
                    sDownLink = JSON.parse(jsonData.response).path;
                    iLocalId = JSON.parse(jsonData.response).id;
                    sext = Qiniu.getFileExtension(file.name);
                    iAppSize = apkSize;
                    bUploaded = true;
                    storeInfo()
                },
                Error: function (up, err) {
                }
            }
        });
        $("#changest").click(function () {
            if ($(this).hasClass("pause")) {
                uploader.start();
                $(this).removeClass("pause");
                $(this).text("暂停上传")
            } else {
                $(this).addClass("pause");
                $(this).text("恢复上传");
                uploader.stop()
            }
        });
        uploader.init()
    }
    $("#submit").on("click", function () {
        $("input[name='hlogo']").val($("#image").attr("src"));
        $("input[name='happName']").val($("#appName").val());
        $("input[name='hversion']").val($("#version").val());
        $("input[name='hpackageName']").val($("#packageName").val());
        $("input[name='hversionCode']").val($("#versionCode").val());
        myDropzone.processQueue()
    });
    $("#reset").on("click", function () {
        location = location
    });

    /**
     * 解析安装包
     * @param infoplist
     * @param resoucesArsc
     * @param androidManifest
     */
    function parse(infoplist, resoucesArsc, androidManifest) {
        var data = {};
        if (androidManifest.length > 0) {
            data.system = "android";
            resoucesArsc[0].async("uint8array").then(function (arsc) {
                androidManifest[0].async("uint8array").then(function (mainifest) {
                    data.arsc = Base64.encode(arsc);
                    data.mainifest = Base64.encode(mainifest);
                    //callback(data)
                })
            })
        } else if (infoplist.length > 0) {
            data.system = "ios";
            infoplist[0].async("uint8array").then(function (d) {
                data.plist = Base64.encode(d);
                //callback(data)
            })
        }

        function callback(data) {
            showLoading();
            $.ajax({
                url: domain + "GetAppInfo.ashx", type: "POST", data: data, cache: false, success: function (d) {
                    ajaxCallBack(d, data.system);
                    if (d.Success) {
                        sAppName = d.Name;
                        iVersion = d.Version;
                        sPackageName = d.PackageName;
                        iVersionCode = d.VersionCode;
                        bInfoReady = true;
                        if (bUploaded && bLogoReady) {
                            storeInfo()
                        }
                    } else {
                        hideLoading();
                        alert("获取不到安装包信息!")
                    }
                }, error: function () {
                    hideLoading();
                    alert("试用限制，官网下载http://i8cv.com")
                }
            })
        }
    }
});

function showLoading() {
    $("#full,#loading").fadeIn()
}

function hideLoading() {
    $("#full,#loading").fadeOut()
}

function ajaxCallBack(d, sys) {
    $("input[name='hsysname']").val(sys);
    $(".zone,.tip").hide();
    $("input[name='happName']").val(d.Name);
    $("input[name='hversion']").val(d.Version);
    $("input[name='hpackageName']").val(d.PackageName);
    $("input[name='hversionCode']").val(d.VersionCode);
    var logoType = logotype(d.LogoName);
    if (sys == "ios" && d.LogoName.indexOf(".") == -1) {
        d.LogoName = "/" + d.LogoName + "([\\d]+x[\\d]+)?(@[\\d]x)?.png"
    } else if (sys == "ios") {
        d.LogoName = "/" + d.LogoName
    }
    var logo = z.file(new RegExp(d.LogoName, "i"));
    if (!logo.length) {
        hideLoading();
        return
    }
    logo[0].async("uint8array").then(function (d) {
        var dd = Base64.encode(d);
        $.ajax({
            url: domain + "GetString.ashx",
            type: "POST",
            cache: false,
            data: {image: dd, system: sys},
            complete: function () {
            },
            error: function () {
            },
            success: function (abc) {
                bLogoReady = true;
                $("input[name='hlogo']").val(abc);
                $("#image").attr("src", "data:" + logoType + ";base64," + abc);
                sLogName = abc;
                if (bInfoReady && bUploaded) {
                    storeInfo()
                }
            }
        })
    })
}

function logotype(filename) {
    if (/(\.|\/)(png)$/i.test(filename)) {
        return 'image/png'
    }
    if (/(\.|\/)(ico)$/i.test(filename)) {
        return 'image/x-icon'
    }
    if (/(\.|\/)(gif)$/i.test(filename)) {
        return 'image/gif'
    }
    if (/(\.|\/)(bmp)$/i.test(filename)) {
        return 'image/bmp'
    }
    if (/(\.|\/)(jpeg|jpg|jpe)$/i.test(filename)) {
        return 'image/jpeg'
    }
    return 'image/png'
}

function storeInfo() {
    $.ajax({
        type: 'POST',
        url: upload,
        dataType: "",
        data: {
            "iLocalId": iLocalId,
            "apkName": apkName,
            "hslogo": sLogName,
            "hext": sext,
            "happName": sAppName,
            "hversion": iVersion,
            "hpackageName": sPackageName,
            "hversionCode": iVersionCode,
            "sDownLink": sDownLink,
            "iAppSize": iAppSize
        },
        success: function (json) {
            window.location.href = "/index.php/so/" + json.id
        },
        error: function (data) {
            window.location.href = "/index.php/Appipa/index/listinfo"
        }
    })
}