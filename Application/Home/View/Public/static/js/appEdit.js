var pgyAppEdit = {

    "init": function() {
        this.initIconUploader();
        this.initScreenUploader();
        this.initAppEditValidate();
        this.reloadScreenshotDisplay();
        this.selItem(templateName);
    },

    "initAppFinish": function() {
        if (identifier != 0) {
            this.initIconUploader();
        } else {
            this.initAddIconUploader();
        }

        this.initScreenUploader();
        this.initAppFinishValidate();
        this.reloadScreenshotDisplay();
        this.selItem(templateName);
    },

    "initAppEditValidate": function() {
        pgy = this;

        $(".sky-form").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 1,
                    maxlength: 100
                },
                password: {
                    required: {
                    depends: function(element){
                        return (isInhouse == '1' && isOriginalBuildInHouse == '2');
                        }
                    },
                    minlength: 1,
                    maxlength: 30
                },
                shortcutURL: {
                    required: true,
                    minlength: 4,
                    maxlength: 100,
                    remote: {
                        url: "/app/checkShortcutURL",
                        type: "post",
                        dataType: "json",
                        data: {
                            shortcutURL: function() {
                                return $("#shortcutURL").val();
                            },
                            key: function() {
                                return $("#key").val();
                            }
                        }
                    }
                }
            },

            messages: {
                name: {
                    required: requireAppName,
                    minlength: minAppName,
                    maxlength: maxAppName
                },
                password: {
                    required: passwordRequire,
                    minlength: minPassword,
                    maxlength: minPassword
                },
                shortcutURL: {
                    required: requireShortcut,
                    minlength: minShortcut,
                    maxlength: maxShortcut,
                    remote: existsShortcut
                }
            },

            submitHandler: function(form) {
                pgy.appEditSaveData(form);
            },

            errorPlacement: function(error, element) {
                error.insertAfter(element.parent());
            }
        });
    },

    "appEditSaveData": function() {
        $.ajax({
            type : "POST",
            data : $('#form').serialize(),
            dataType: 'json',
            beforeSend: function( xhr ) {
                $('#submitButton').text(submiting).addClass('btn-u-default').attr('disabled', 'disabled');
            },
            success : function(result, textStatus, jqXHR) {
                console.log(result);
                code = result.code;
                if (code == 0) {
//                    window.location.href = '/my/appHistory/' + pgyOptions.aIdentifier + '/' + pgyOptions.aType;
                    window.location.href = '/manager/dashboard/app/' + pgyOptions.agKey;
                } else {
                    alert(result.message);
                    $('#submitButton').text(submit).removeClass('btn-u-default').removeAttr('disabled');
                }
            },
            error : function(jqXHR, textStatus, errorThrown) {
                $('#submitButton').text(submit).removeClass('btn-u-default').removeAttr('disabled');
            }
        });
    },

    "screenshotDelete": function(key) {
        if (!confirm(deleteScreenshot)) {
            return;
        }

        var pgy = this;

        $.ajax({
            type : "POST",
            data : {"aId": pgyOptions.aId, 'key': key},
            url : "/my/screenshotDelete",
            dataType: 'json',
            beforeSend: function( xhr ) {
            },
            success : function(result, textStatus, jqXHR) {
                code = result.code;
                if (code == 0) {
                    pgy.reloadScreenshotDisplay();
                } else {
                    alert(result.message);
                }
            },
            error : function(jqXHR, textStatus, errorThrown) {
            }
        });
    },

    "initScreenUploader": function() {
        var pgy = this;
        var pgyOptions2 = pgyOptions;
        $fub = $('#uploader-screenshot');

        var uploader = new qq.FineUploaderBasic({
            button: $fub[0],
            request: {
                endpoint: '/my/uploadScreenshot'
            },
            validation: {
                allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
            },
            maxConnections: 1,
            callbacks: {
                onSubmit: function(id, fileName) {
                    this.setParams({'key': pgyOptions2.aKey});
                },
                onUpload: function(id, fileName) {
                },
                onProgress: function(id, fileName, loaded, total) {
                },
                onError: function(event, id, reason ) {
                    alert(reason);
                },
                onComplete: function(id, fileName, responseJSON) {
                    if (responseJSON.success) {
                        pgy.reloadScreenshotDisplay();
                    }
                }
            },
            debug: true
        });
    },

    "reloadScreenshotDisplay": function() {
        $.ajax({
            type : "POST",
            data : {"key": pgyOptions.aKey},
            url : "/my/screenshotDisplay",
            dataType: 'html',
            success : function(result, textStatus, jqXHR) {
                $("#screenshotTableContainer").html(result);
            },
            error : function(jqXHR, textStatus, errorThrown) {
            }
        });
    },

    "initIconUploader": function() {
        $fub = $('#uploader-icon');
        $button = $fub.children().eq(0);
        $message = $fub.children().eq(1);
        var pgy = this;
        var pgyOptions2 = pgyOptions;

        var uploader = new qq.FineUploaderBasic({
            button: $fub[0],
            request: {
                endpoint: '/my/changeAppIcon'
            },
            validation: {
                allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
            },
            callbacks: {
                onSubmit: function(id, fileName) {
                    this.setParams({'key': pgyOptions2.aKey});
                    $button.hide();
                    $message.show();
                },
                onUpload: function(id, fileName) {
                },
                onProgress: function(id, fileName, loaded, total) {
                },
                onError: function(event, id, reason ) {
                    alert(reason);
                },
                onComplete: function(id, fileName, responseJSON) {
                    if (responseJSON.success) {
                        $('#iconImg').attr('src', pgyOptions.appIconHost + '/image/view/app_icons/' + responseJSON.key + '/120');
                        $('#icon').val(responseJSON.key);
                    }

                    setTimeout(function() {
                        $button.show();
                        $message.hide();
                    }, 500)
                }
            },
            debug: true
        });
    },

    "initAddIconUploader": function() {
        $fub = $('#uploader-icon');
        $button = $fub.children().eq(0);
        $message = $fub.children().eq(1);
        var pgy = this;
        var pgyOptions2 = pgyOptions;

        var uploader = new qq.FineUploaderBasic({
            button: $fub[0],
            request: {
                endpoint: '/my/addAppIcon'
            },
            validation: {
                allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
            },
            callbacks: {
                onSubmit: function(id, fileName) {
                    $button.hide();
                    $message.show();
                },
                onUpload: function(id, fileName) {
                },
                onProgress: function(id, fileName, loaded, total) {
                },
                onError: function(event, id, reason ) {
                    alert(reason);
                },
                onComplete: function(id, fileName, responseJSON) {
                    if (responseJSON.success) {
                        $('#iconImg').attr('src', pgyOptions.appIconHost + '/image/view/app_icons/' + responseJSON.key + '/120');
                        $('#icon').val(responseJSON.key);
                        $('#addicon').html(changeIcon);
                    }

                    setTimeout(function() {
                        $button.show();
                        $message.hide();
                    }, 500)
                }
            },
            debug: true
        });
    },

    "syncAppStore": function() {
        if (!confirm(sycnStoreWarning)) {
            return;
        }

        $.ajax({
            type : "POST",
            data : {"aId": pgyOptions.aId},
            url : "/my/syncAppStore",
            dataType: 'json',
            beforeSend: function( xhr ) {
                $('#syncAppStoreStoreLink').hide();
                $('#syncAppStoreIng').show();
            },
            success : function(result, textStatus, jqXHR) {
                code = result.code;
                if (code == 0) {
                    window.location.reload();
                } else {
                    $('#syncAppStoreStoreLink').show();
                    $('#syncAppStoreIng').hide();
                }
            },
            error : function(jqXHR, textStatus, errorThrown) {
                $('#syncAppStoreStoreLink').show();
                $('#syncAppStoreIng').hide();
            }
        });
    },

    "appFinishSaveData": function() {
        var pgyOptions2 = pgyOptions;

        if (isIOS && isOriginalBuildInHouse == '1' && $('#password').val() == '') {
            if (!confirm(inhousePasswordAlert)) {
                $('#password').focus();
                return false;
            }
        }

        $.ajax({
            type : "POST",
            data : $('#form').serialize(),
            url : "/app/finish",
            dataType: 'json',
            beforeSend: function( xhr ) {
                $('#submitButton').text(launching).addClass('btn-u-default').attr('disabled', 'disabled');
                $('#ed_loading').show();
            },
            success : function(result, textStatus, jqXHR) {
                code = result.code;
                if (code == 0) {
                    window.location.href = '/app/view/' + pgyOptions2.aKey;
                } else {
                    alert(result.message);
                    $('#submitButton').text(launchApp).removeClass('btn-u-default').removeAttr('disabled');
                    $('#ed_loading').hide();
                }
            },
            error : function(jqXHR, textStatus, errorThrown) {
                $('#submitButton').text(launchApp).removeClass('btn-u-default').removeAttr('disabled');
                $('#ed_loading').hide();
            }
        });
    },

    "initAppFinishValidate": function() {
        var pgy = this;

        if (identifier != 0) {
            $(".sky-form").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 1,
                        maxlength: 100
                    },
                    password: {
                        required: {
                            depends: function(element){
                                return ($('#publishRange').val() == '1' && isOriginalBuildInHouse == '2') ;
                            }
                        },
                        minlength: 1,
                        maxlength: 30
                    },
                    shortcutURL: {
                        required: true,
                        minlength: 4,
                        maxlength: 100,
                        remote: {
                            url: "/app/checkShortcutURL",
                            type: "post",
                            dataType: "json",
                            data: {
                                shortcutURL: function() {
                                    return $("#shortcutURL").val();
                                },
                                key: function() {
                                    return $("#key").val();
                                }
                            }
                        }
                    }
                },

                messages: {
                    name: {
                        required: nameValidRequire,
                        minlength: nameValidRequire,
                        maxlength: nameValidMaxLen
                    },
                    password: {
                        required: passwordRequire,
                        minlength: passwordValidMax,
                        maxlength: passwordValidMax
                    },
                    shortcutURL: {
                        required: shortcutValidRequire,
                        minlength: shortcutValidMin,
                        maxlength: shortcutValidMax,
                        remote: shortcutValidExists
                    }
                },

                submitHandler: function(form) {
                    pgy.appFinishSaveData(form);
                },

                errorPlacement: function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        } else {
            $(".sky-form").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 1,
                        maxlength: 100
                    },
                    identifier: {
                        required: true
                    },
                    password: {
                        required: {
                            depends: function(element){
                                return ($('#publishRange').val() == '1' && isOriginalBuildInHouse == '2') ;
                            }
                        },
                        minlength: 1,
                        maxlength: 30
                    },
                    shortcutURL: {
                        required: true,
                        minlength: 4,
                        maxlength: 100,
                        remote: {
                            url: "/app/checkShortcutURL",
                            type: "post",
                            dataType: "json",
                            data: {
                                shortcutURL: function() {
                                    return $("#shortcutURL").val();
                                },
                                key: function() {
                                    return $("#key").val();
                                }
                            }
                        }
                    }
                },

                messages: {
                    name: {
                        required: nameValidRequire,
                        minlength: nameValidRequire,
                        maxlength: nameValidMaxLen
                    },
                    identifier: {
                        required: identifierRequired
                    },
                    password: {
                        required: passwordRequire,
                        minlength: passwordValidMax,
                        maxlength: passwordValidMax
                    },
                    shortcutURL: {
                        required: shortcutValidRequire,
                        minlength: shortcutValidMin,
                        maxlength: shortcutValidMax,
                        remote: shortcutValidExists
                    }
                },

                submitHandler: function(form) {
                    pgy.appFinishSaveData(form);
                },

                errorPlacement: function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        }
    },
    "setTemplate": function(tName) {
        $('.caption').hide();
        $('#template').val(tName);
        $('#templateContainer img').removeClass('checked'); 
        $('#templateContainer a').removeClass('checked'); 
        $('.template-check').show();
        $('.over-lay').show();
        $('#' + tName + 'Img').addClass('checked'); 
        var l = $('#' + tName + 'Img' ).position().left;
        var h = $('#' + tName + 'Img' ).height();
        var w = $('#' + tName + 'Img' ).width();
        $('.template-check').css('left',l+34);
        $('.over-lay').css('left', l);
        $('.over-lay').height(h);
        $('.over-lay').width(w+1);
    },
    "selItem": function(tName) {
        $('.caption').hide();
        $('.app_edit_screenshot_img').removeClass('checked');
        $('#caption-' + tName).show();
        $('#' + tName + 'Img').addClass('checked');
        $('#template').val(tName);
    }
};
