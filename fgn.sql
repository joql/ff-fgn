/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : fgn

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-04-24 21:22:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lc_config
-- ----------------------------
DROP TABLE IF EXISTS `lc_config`;
CREATE TABLE `lc_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `allowsize` varchar(255) NOT NULL DEFAULT '0' COMMENT '默认空间大小',
  `singlesize` varchar(255) NOT NULL DEFAULT '0' COMMENT '默认限制上传大小',
  `uptype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '默认上传类型',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `tianshu` varchar(255) NOT NULL DEFAULT '0' COMMENT '默认天数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lc_config
-- ----------------------------
INSERT INTO `lc_config` VALUES ('1', '100000', '300', '1', '0', '3000');

-- ----------------------------
-- Table structure for lc_list
-- ----------------------------
DROP TABLE IF EXISTS `lc_list`;
CREATE TABLE `lc_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) DEFAULT NULL COMMENT '用户昵称',
  `fid` int(11) NOT NULL DEFAULT '0',
  `pihao` varchar(16) DEFAULT NULL COMMENT '批号',
  `web` text COMMENT '网址',
  `plist` text,
  `uptxt` varchar(255) DEFAULT NULL COMMENT 'app地址',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '待处理时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:新提交；2：待处理；3：已完成',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '已完成时间',
  `ext` varchar(16) DEFAULT NULL COMMENT '后缀',
  `zsize` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `hslogo` longtext COMMENT 'logo图片',
  `aqpassword` varchar(32) DEFAULT NULL COMMENT 'app密码',
  `beifen` text COMMENT '介绍',
  `xznum` int(11) NOT NULL DEFAULT '0',
  `zxlogo` text COMMENT '新上传的logo图片',
  `tianshu` int(11) NOT NULL DEFAULT '0' COMMENT '会员天数',
  `qnname` varchar(255) DEFAULT NULL COMMENT '七牛返回名称',
  `upid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lc_list
-- ----------------------------
INSERT INTO `lc_list` VALUES ('3', null, '1', null, '/index.php/so/3', null, 'http://7xl07o.com1.z0.glb.clouddn.com/o_1cblu294a17uta6c8depk01ssf9.apk', '1524375825', '0', '0', 'apk', '4090161', null, null, null, '1', null, '0', 'o_1cblu294a17uta6c8depk01ssf9.apk', '0');
INSERT INTO `lc_list` VALUES ('4', null, '1', null, '/index.php/so/4', null, 'http://7xl07o.com1.z0.glb.clouddn.com/o_1cblu6or4ada1jlv2kb16jre2i9.apk', '1524375985', '0', '0', 'apk', '4090161', null, null, null, '1', null, '0', 'o_1cblu6or4ada1jlv2kb16jre2i9.apk', '0');
INSERT INTO `lc_list` VALUES ('5', null, '1', null, '/index.php/so/5', null, 'http://7xl07o.com1.z0.glb.clouddn.com/o_1cblu7upu14jk12lbrjl11fsl6t9.apk', '1524376022', '0', '0', 'apk', '4090161', null, null, null, '1', null, '0', 'o_1cblu7upu14jk12lbrjl11fsl6t9.apk', '0');
INSERT INTO `lc_list` VALUES ('6', null, '1', null, '/index.php/so/6', null, '2018-04-22/5adc22bae1c23.apk', '1524376252', '0', '0', 'apk', '4090161', null, null, null, '2', null, '0', null, '0');
INSERT INTO `lc_list` VALUES ('7', '天天娱乐', '1', null, '/index.php/so/7', null, '2018-04-22/5adc2fa28272b.apk', '1524379555', '0', '0', 'apk', '4090161', 'data:image/jpg/png/gif;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAIAAADYYG7QAAAFhUlEQVRYw+3XWYwURRgA4P+v6nOOnZ4d9ppdh10OEZYbL4jxWMEjq0ZD1CDGIz5ofFJjSIiJD4YEiC9iiEeiiUo84oMYow8qKqJBDa4gLOvs4g7sMXszszPbPX3X74OALBKfTCRx/qfuvzqVr+qv7qpGeIXgUgoGl1hUQVVQFVQFVUFVUBX0L4f0T40EwAB0AAbgAvj/OYgDBDZ0/4zlcWq9BmovAzH7gQtu8ewwLkjS2bH92Xouc36R8KIgnN2pBDicZd++QW6FPA9W3QFaDYgz3RmyuSiSZwg+cQV8hvC73Vgrm3Pksh2qnIUSiOkgaoZ6Wj3NUQw5KVfIGW2qEqoyC3QWABAB+MTHXWPYmSMQZ4MQMPTIs4EIuUSKDj7gWBamB0iJg1sBjqABuAAA4IOEFOfuzaljtzccfGfwthN2k+YGNZK9PDZwf/rACat579jahFxpjw1cm+h1QrXPajaUGQbUbzWuNfrmR8Z05k4FibyTfCvfkbdTMBtE6Fh8qp85FnBOTAItGjiW+O0glGYgVdty+lRT975kpTx/Mju06OpvFt4qk3O01HxT7FBdMPZrMf17pdFBnAj0ATCawpH95UVHCs2NenF9zXgjjeWDORJ5cuAMWrUN8vhcHBQ2ucz3PC/DB4tuhM7W7CyIExSK4ugpcmxgjBRFxA0Wi97Q0bmgY+lE17ENhz9a+dMrCe4tlqy9eN/htmsz+tgikd0wsSconm73flH5vEmRauXDnfanseyPybBxtZQmHwpD5TIfzor6k154vXKszNYNiDk0WR+gxIBkcnOw9De3RbALQIJRqo6WrQHThIrLJqfbne7NHdKmuzamU7cV3nxd732fydZkffPepqteW/74uFJXsOKdhVeDsL8UFDEy+EXtowDUNN19xcwno+CpvOdg6qmWcHBz4eOoGNJVLSoHXdiS5alWPyv5JwxRtnjcA+4oRhjHvy1qAlA1yDQDAAvDGyP7Xmzb3Z60fp7ofXdEOhl8Vb5ONo0tQ42bRuNNRZ7Q0HsWd67Xvn2evbQxfP8h/t7XeG8NlZ6Ttn0ZvaePt28R225hn9ugr4n0TEKdrKgxmZr8nI+RZmlEVfkSP/e51LkOv+/nVwpkF3vtCUCgRMENxv6drc+tLmdLXfJBddfetDBV/VRb1JOnIB4jngQBy/Vsa52329rx1cxdlpYxkntW2bl50sB38OCu4uMMcFmy2AbmCbf+QPqJfNAADNp4Yb91d7+f4eoxkKf2hau/cdbGdCNnrwlt6dwM4V//ZQQxZj5S/9bW9I6m0fzMr/JwOHdnw90/1J2qZ5/kh9lYYYGXeEFE7wSQdHSBoSNkJkSIXOUBA8ExsIUmQgYIOvdTShlAGJIdEgYhG3VTFaEKQkBQmReQFBIS4gWfrjMzhEQNyvj2y7Y+3PA2DVPpsDKK6V21T3/AnmTTPXFjYm7mgBbpH3GOz1AHYcIm9XLKzWP9ZW5ozOOBa/OoIExicVhu6QvmrcLjrdJYRPJUcgepZYJqR4PYYjnHkLgIFfQ09FXmjIjG3nChf16hzlxJFHQmPtvU8IEwoXRc64XFLyef+VB9QABAuCRvPTa/Jpeq49OlFtNRCQAQ4uHUCve7HmklMbbCPdSlrJXJWxocmZFvbOB6rX1yusL65OYFbo/HT5ssvkJ0pUShgrF6GomQTYgxNDWY389bzwedKRkjsSzSvTWzPVPMHT6+dI/x2I/KunMzydGMSYcAmRmsDkX8z6QCrg6WSzoBRNAyKcEo1ND2QNXQ8UAhQAEsClYcTYe0ADgBBCBLECAQAEjgu6SV0BDn7fGz1lCClaJglkTCYjGg/3y3RyiJRAkSwADoEjl+XHS7rp4Yq6AqqAqqgqqgKqgKqoL+16A/AKfSnsFfa1OAAAAAAElFTkSuQmCC', null, null, '2', null, '0', null, '0');
INSERT INTO `lc_list` VALUES ('8', 'test', '0', null, '/index.php/so/8', 'itms-services://?action=download-manifest&url=https://ff-fgn.cn/Public/appipa/8.plist', '2018-04-22/5adc2ff2548ee.ipa', '1524380201', '0', '0', 'ipa', '10861723', 'G:\\code\\php\\ff-fgn\\Public/uploads/ios/1524380201848.png', null, null, '2', null, '0', null, '0');
INSERT INTO `lc_list` VALUES ('9', 'test', '1', null, '/index.php/so/9', 'itms-services://?action=download-manifest&url=https://ff-fgn.cn/Public/appipa/9.plist', '2018-04-22/5adc323ec86c1.ipa', '1524380223', '0', '0', 'ipa', '10861723', 'G:\\code\\php\\ff-fgn\\Public/uploads/ios/1524380223992.png', null, null, '1', null, '0', null, '0');
INSERT INTO `lc_list` VALUES ('10', '天天娱乐', '1', null, '/index.php/so/10', 'itms-services://?action=download-manifest&url=https://ff-fgn.cn/Public/appipa/10.plist', '2018-04-22/5adc32cd52a5b.ipa', '1524380366', '0', '0', 'ipa', '10861723', 'data:image/jpg/png/gif;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAc5pVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IlhNUCBDb3JlIDUuNC4wIj4KICAgPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICAgICAgPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIKICAgICAgICAgICAgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIgogICAgICAgICAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyI+CiAgICAgICAgIDx4bXA6Q3JlYXRvclRvb2w+QWRvYmUgRmlyZXdvcmtzIENTNjwveG1wOkNyZWF0b3JUb29sPgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICA8L3JkZjpEZXNjcmlwdGlvbj4KICAgPC9yZGY6UkRGPgo8L3g6eG1wbWV0YT4KAHiqRgAAAAlwSFlzAAAK6wAACusBgosNWgAADxJJREFUeNrFm/lvXNUVx5/Un/oX9MeKElpixzNjjz3e7TghkMWOE6+x4yWbk9jZF8LWIn6oUFvawm9tqZAqVYIW6A+tqkJ/ATVsEk0ggNp4Vnscz3hfUkgi7MSn59zlvXvfu288DgEsffXG8+689z733OWce8+zru54EL6SGtfDcBNqZwFESc2kQojuKoQYaTdpA1cLV9xH8rxdnrSLX4upmd+D7sXuife+2ri257XuHvRBflMJq4K6IG2o1iKXAkLqdxuYvPASmoMPK+BrgbbuBpRZ1WRRFVICtBVBoo2OCNYegMQqojJUNkHl25SKUCtAsfhwc4Fd8fRc9xZYWtUGLVgFVIHpkApCMofscmpF+ICrTd229j0DbnQ1X7XpaqAqZJADdqL2hPiRfRb/S3XJz+J8Jy+bkBWggrcp4FpTz6+J5wU8bIT1A1UgOx2oVJdUMaS6fUTnukQFdCkVYoCX9/VYW0L7WNvKG1b01ZiAtQcharpKs1UtaEMizIjUXqkSg3gZpwLE7xXwpAIure3p2zb0WoFNsOqI2+YC7VRB+UNrcD2o3jA7jho0IiXLu+HdFnf1b7Ol8wXWmrEDG2sVsBK0I+hqthLUgRxFSKY+rnRfqUHOeVleqwAFnDV3u5/rI7oRWhm9rdVHYwmLF+0IQ6KzFBJ7SiHZXYY3LvU2XQE66gJlUP15Cstq8BKcXT8Mqd5ySPWg9kYgiUrsCetNXGne7oHM8u239gBVgFYNQKzxPhiusCBajqqyIFZpQXyThc0siKDiYXp0i2qQ+0hlMEbar+gAHSPsM51P7xdl3fC9eN2+Mki13geJjXjvelQdPgc+S/xhC1tayAzdXKhNWZbRuupo3BqE6NbvQepMF8z99Y8w/8ZrMPf3V2Dhn3+BmVdfxNouRegA65vSqg4oh+BgEaZrpIM+EmVkBfBKkBUWgZHW70Pm2ZOw8MarsPDm66jXYPGtv8HUH16ARMs61p9pliDomNq8mXNSwAzpAVanH6qhODbhYbRm6tfPwO1bXwD7u3ObHW5NZSFGVu0shNH+CIe1LeoCtcHK4dohH9E5D7yotEPVkNhiQfbVl+gB+HOs8OP1/3wMw9ssPoh1FvM+LUdvV3+2TAOV6lhQn43W4sV+9iQs3/hc3Iff6CYBdwdgtKsIISN20+XWUUAF0DhpgFThIypToVSAA35toAaSj1gw/vJvYWVlRTwHPy58dhmG8Ryf94u10ZugVcfEMlvXcSxogIpiX0n8HIG/+B+/0W1u4ZuTGYjvDUC6G4H3l9uwTrMVoBLoMFfmSKVZh0miHJVXwMcP10BqGwH/zq7wFdHSGDCeSxFwV4k9ZbH+rDomzSpwo9eTosKJrjKI4iCRZMAuC09mId4ThDRBH6jQYRXQjAp5tBKyR6uMonMOvAPOrnWkFlLbLcgQ8Mod7TkIOErAaN1kd1ifrlxWtjzWdXlSNP3EGgTwDQNwbwjSPQEYO1jhhdVAEWpQqhqyQ1W6BhWp8AI6c7QWRhoR+BUfYKyMEeYDhDXHxG1ly5l3XdZt5WFdsjvCgX9hBk70hWCsN4iQlRoss6q0JkEMVTNNHBM6XqNLfj9U7VQAgSP0ODX1wToY2WkGXkTgGFYGc05wnpaOidvKMRuYBiq77xbaQUGivYhN7DGab/2A+0vgWh8CD1R6YQcdWBVy8oSPVHgFnKCzx+phtNnfwgQ8yjy7Uu6NoUOU6HTcT+lvW3xk9lpX+snkzcQ3E/BTxlE6sQ+B+0Nw7XAVG2XdsKpFOVgtTJ7kmhKaPFlnf+cGZ9DUShA4vQunpT+96AX+9BIH7glzYOmCCivHFStbw/ZUVKg3Z+ErkwsXf8gHGC2cRODx/mJsdlV2n+VWrTKCTp2qY5o+7WhKis4p4A40XWcjpHebgRcROC6B0REaEaEmi9pkXxYxtGW7kbuU5tzmAsYJP/Wc2cLJ/WEY34fAR6qZdWWf1WDdoGfqzVLANWvTtU5shLEWfwvHmyxIEyy6n+TLk5WdyMoZvCzj6NzmhHzkqJOv6g9cCuP7i7EZVztN2QQrQc/WwwzTRpg5J0Sfz/JzNrgCTcCTJxtgrA2B/2wA/gyBcUDjfneZHV15gNHKNrAd2FP4p8S4IwicyAGcOlAKGQKmqYaARVMmy7hhGSgBnic1wOyjDTCDmsXP7Ds8p0GL5j1BFXfKH3hRBUb/nQIYuYJiLxqowFEJ3KL236AAruDAv/QBRlcwcygCGRxUssfw4Y7XwcTJenzAjQjaAFNnGhBiE8JsRjjUhYdg9jHSFph9XIg+03d4jpU5vxmmz21iv6NrsGud2QLX9nwXfenfm4GbCbiM+/QihtZGazFwWXagsFsH5oE9Avdx4BETMLqWyZ4HINNzP0KHIHsoANmBIEwcCcHk0RBMDZbA1LEwTJ8ohemTZTBzuhxmT0dg9kw5zJ2rgFmhubN4PFvOvp85FWFl6TdTx8MwNVQCE4PFMHmsHMZoHn75N8ZpKYEj+Fi/GTjZ6TghCrD0rvh0JJdrGPAjZmCKllJDmyAz1ADZ01th4vTDMHH2EZg8txWmzm+D6Ue3w8xjqMd3wOwTjTD3FKkJ5n+yE+afdgm/m/txEysz+8QO9hv6LV2DrjV1oQlng3Uw8fpLuYH38aiNAxfzZSFlPjYAF60KDCJaubP0JXyZHYWlzAgs0RG1POGjyVG4zZReRU75ZfzfuUYalsaTsLwwC/JP9bQkcFoCy35sAwdzAHc4wKM5gL/tP2lpX+C9bgsH7gEwff6mla+F8wbOs0nzjyvfqDQLr6lJ+/bhwJqAv9UmvaZBy9CHvdNSyJ6WtHlYAC/dugXpTz6F5KWPYOSjjyF1eS36yCX/siMougfdi+4pK132YQ+wPS0V+01LhZ552HY8+vwdj88nMvD2lh/A25vuh4vbi+DitgJ4Z0chvNNI2gDv7iQVwbvNRfDergC8v1uoJQjvY0v6AI9MrUH+Heo9PE9l38PfsN820TWCcPHhB+Bf237I7ikHLAf4EiTI8ejP4XjYwKqntVvdVVjd06J5eOxgEKYPFsH0UAXMDEVg5jg6ECfQmThVCXOnq2D+bDXMn6uGhfM1sPhoLSxeqIXrj9XB9cfrdeF3ixfq2PlFLLtwrgbmz1Sza8ydqYE5dEDGB8PsnraF7eDh3zx4YMBlYrei2AHuUD0tty9tCB5y+dLJAxj0oz89PliL/nQN+tK1kD1O/i86/ye4izl5eqNwMRu4y4iaOY9HIfZZfE9lqOwU/mbyVD1zUycwcJg4UgEjGIndFMArSpNeJOBG6Vo6wQOPiflyjyd48ERLwsosWtrivwAgw8MMhYd2LKwE/TLQd4WGPEKiYMEcKfHAQS4GoH9+GEO/IxEj8IIE7kVgDCDM4aG0MIuH1+v9eE3xsFgAOCIWAESIOCGhDcG/DPj1RYB6EQvX6rC0AIAtJjuAIIfL7Sa9ojbpTxB4h4iHtQUAZcCSCwDexXe5gKcs8eRY8ZBLPGzFY6DCWYpVoZXYmIMr8LZqzasdx6pYFJY9hCADER14xQGObacVDw6c0pqz3EDns5AldwrV3YZ4i7PRTcCxzTmA2SJeiC3isZ2Fwzq0e6XSva7lrGX5rGextbEayBzEufVQmT/wNgEs1rSYde2l2oC9VGvpi/CFelzclseqZV+xs0yLcfG4XLlUod3gpmXa49U+S7WVbDDMHMB+ebDMt0nHttKqJXlYYX10bg9oWQKWuh8cNS7E57Eu3ROEMQI+EHH2kPwW4eX69JAOaEMOuRbjaSH+aDVbRkribHDTD5i8we4wX5d2uZPq9qll2hN2vK4NkFxtq4V2HvbynQd182zcZ/chM+jaZVCU8ew6VIprVLGBkWYEP+AoLVJ0hdletd539cQXy3/nUG6mIXC9H3AGYj20mRbgm2ly19BnIy2j9G//DbUKttPA95V4xbFFfmxJiX0q8B0dmAbWrhK2mZZUt1la/TbTNCs72Tr67qEBWG6X7itn26Vjyr6wvoNYru0g2hUgZO8YDnh3Dqm7ULdJ9JWwVmWy8DANrHtKnO1St3Wb+R6xZcrtiCrr1GxDvEbsDxuAo10IvGcD82HtnX87rSHiBT/ksvyAskOobY7LveEy3l2w2yR6i9k95TPI55i/8iEMb6L9YYQVwKatUs/+sDe/o5AlsjDgZ5+Apc+v86Wd5WV2vDExDlGEHencwBbAeR5GWMvrcHI5RFM/ENF3+l2iMnLn3055oO6CLYm2Zm9NT3jCxIVPLsFVGmc6ELYj5E1jUrL0rNXyKePtYbhagRd77mmA5S+1Gy3NzzDgVHsB93D2qpk7pVr6Q9qTzBLRpeR1aLkd7PflrBXFsTJvZK/ZlX57eYl9nkML/xebdKItaAPzpuxNScyZthRFlzPWWgzDtRZcfeYcTCZiMJvNwsxYGuYmJyFz5TJc7QwhcCGbDkyZPKOeTB6nAnjWTpn+vyGDhyozvTcEUZx23v/pM/DO88/Du796juniCy/AB0+eRUuus7N2Y4amnDNtSbM0WRl/fGXrOnjjQQv+sc6CN3+Ex/steCv8HYi2YwgpE9O6Q4akNB2e52yFV0lMKxW/KbFj2xGx73sFK/9S2ILLpagSCz4MWHCljls33lZkhB1eNTFNSs35IHC0eLx5PSR2FUCipQCSrYXYbwIswM6ZdqimGKoVYJKahqimIHbxCh3rw6bfj4MYRnHpXhwoMbgZ2RP2JpoaYPNLLm1SoqmWAMvbilNfaQ9xKaGkmkErs/OcpNISPecyV46lSDR1J5lKdzEuwz2Zj+XOoc6RSpxf+rCwtG/6sCmj1p063O2kJzoqESrmS6pqBq0CKRNXtRRiT970hrzypvNPENde5ChcJWdatbieDJ7aE/JAedKE1WRyE6j0oDSruppx4714BaBJz7DNLyteZMYrAFpFdIa83xuz4ZV3IFqKPFa133ZZ5b0H667fZsnnBQ/Tuw8ya77d57sOF2BbwH7bxUkEdxJH9Sa8/mt6jUfmUud6hafFDC/fcnErrqrV520W9xstO9U3Wr7u95Y0cNeAtstgdfngrab3l4q0dxjifu8sqa/u3MU7S18d2OelLe01H1EJUdEMeQvwkV1Z+kAkpVt0/V09670B9mvq2stcTrPPKROkAno3r9+p+j8tO/x5GzKBLAAAAABJRU5ErkJggg==', null, null, '4', null, '0', null, '0');

-- ----------------------------
-- Table structure for lc_member
-- ----------------------------
DROP TABLE IF EXISTS `lc_member`;
CREATE TABLE `lc_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `create_time` int(11) DEFAULT '0',
  `login_ip` varchar(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '0:禁止登陆 1:正常',
  `type` tinyint(1) DEFAULT '1' COMMENT '1:前台用户 2:管理员 ',
  `allowsize` int(11) DEFAULT '0' COMMENT '会员上传全部容量大小',
  `singlesize` int(11) DEFAULT '0' COMMENT '单个允许上传大小',
  `accesskey` varchar(255) DEFAULT NULL,
  `secretkey` varchar(255) DEFAULT NULL,
  `bucket` varchar(255) DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `uptype` tinyint(1) DEFAULT '1' COMMENT '1:七牛 2:本地 3:远程',
  `tianshu` int(11) DEFAULT '0' COMMENT '天数',
  `uploadedsize` decimal(10,2) DEFAULT '0.00' COMMENT '会员已上传大小',
  `uptoken` varchar(255) DEFAULT NULL,
  `update_time` int(11) DEFAULT '0',
  `appnum` int(11) NOT NULL DEFAULT '0',
  `isvip` tinyint(1) DEFAULT '1' COMMENT '1:putong 0:vip',
  PRIMARY KEY (`id`),
  KEY `password` (`password`) USING BTREE,
  KEY `username` (`username`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3506 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lc_member
-- ----------------------------
INSERT INTO `lc_member` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '1490609540', '127.0.0.1', '1', '2', '10000000', '88888', 'VbzfGpZyNJMXEMvKeetBVixVqB8pipP0IbjetEJG', 'KMdZfb956ZQl0ndkHoWbe3YxUNPNZcmADEUFOizM', 'joql', 'http://7xl07o.com1.z0.glb.clouddn.com/', '2', '3650', '2015.38', '', '1524375251', '10', '1');

-- ----------------------------
-- Table structure for lc_newlist
-- ----------------------------
DROP TABLE IF EXISTS `lc_newlist`;
CREATE TABLE `lc_newlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `newlogo` text NOT NULL,
  `kename` varchar(255) NOT NULL DEFAULT '',
  `fid` int(11) NOT NULL DEFAULT '0',
  `xznum` int(11) NOT NULL DEFAULT '0',
  `tid` varchar(255) NOT NULL DEFAULT '',
  `tname` varchar(255) NOT NULL DEFAULT '',
  `tweb` text NOT NULL,
  `ttext` text NOT NULL,
  `lrtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lc_newlist
-- ----------------------------
INSERT INTO `lc_newlist` VALUES ('1', '', '123', '1', '1', 'on,on', '123,132', '123,123', '', '1524382095');
INSERT INTO `lc_newlist` VALUES ('2', '', '123', '1', '1', 'on,on,on', '123,123,', '123,123,13', '123', '1524382119');
INSERT INTO `lc_newlist` VALUES ('3', 'data:image/jpg/png/gif;base64,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', '天天娱乐', '1', '2', 'on,on', 'Ios,Android', '/index.php/so/10,/index.php/so/7', '天天娱乐', '1524402725');
