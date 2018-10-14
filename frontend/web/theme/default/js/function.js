function modalWarning(message) {
    $.ajax({
        url: Url.warningModal,
        type: 'post',
        async: false,
        data: {message: message},
        dataType: "html",
        success: function (html) {
            $(".modal").modal("hide").remove();
            $('body').append(html);
        }
    });
}
function modalNotify(message) {
    $.ajax({
        url: Url.notifygModal,
        type: 'post',
        async: false,
        data: {message: message},
        dataType: "html",
        success: function (html) {
            $(".modal").modal("hide").remove();
            $('body').append(html);
        }
    });
}
/**
 * 不需要登录验证
 * @param url
 * @param data
 */
function loadModal(url, data) {
    $("script[src^='/assets/']").remove();
    $.ajax({
        url: url,
        type: 'post',
        async: false,
        data: data,
        dataType: "html",
        success: function (html) {
            $('.modal').modal("hide").remove();
            $('body').append(html);
        }
    });
}

function checkFrom(formId) {
    var check = true;
    $(".error", formId).each(function () {
        if (!$(this).is(":hidden")) {
            check = false;
        }
    });
    return check;
}
/**
 * 需要登录验证的弹窗
 * @param loginUrl
 * @param url
 * @param data
 * @returns {boolean}
 */
function loadMemberModal(url, data) {
    var login = null;
    $.ajax({
        url: Url.checkLoginModal,
        type: 'post',
        async: false,
        data: data,
        dataType: "json",
        success: function (data) {
            login = data;
        },
        error: function () {
            modalWarning('网络繁忙');
            login = false;
        }
    });
    if (login === false) {
        // modalWarning('请先登录');
        loadModal(Url.loginModal, {});
        return false;
    }
    $.ajax({
        url: url,
        type: 'post',
        async: false,
        data: data,
        dataType: "html",
        success: function (html) {
            $('.modal').modal("hide").remove();
            $('body').append(html);
        }
    });
}
/**
 * 根据年月获取天数
 * @param year
 * @param month
 * @returns {number}
 * @constructor
 */
function getLastDay(year, month) {
    var date = new Date(year, month, 1),
        lastDay = new Date(date.getTime() - 864e5).getDate();
    return lastDay;
}
/**
 * 去重
 * @param str
 * @returns {Array}
 */
function unique(str) {
    var newArr = [],
        i = 0,
        len = str.length;

    for (; i < len; i++) {

        var a = str[i];

        if (newArr.indexOf(a) !== -1) {

            continue;

        } else {

            newArr[newArr.length] = a;

        }

    }

    return newArr;

}

function makeMainTagActive(obj, id, initFn) {
    $('.view-tabs > li').removeClass("active");
    obj.addClass('active');
    obj.parent().parent().nextAll('div').hide();
    $("#" + obj.attr("data-target-id")).show();
    TableGrids.init(initFn, id);
}

function makeMainTagActiveMobile(obj, id, initFn, url) {
    $('.view-tabs > li').removeClass("active");
    obj.addClass('active');
    obj.parent().parent().nextAll('div').hide();
    $("#" + obj.attr("data-target-id")).show();
    TableType.init(initFn, id, url);
}

function checkNumber(obj) {
    return obj === +obj
}

function str_split(str, split_len) {
    var len = str.length;
    var result = [];
    for (var i = 0; i < len; i += split_len) {
        result.push(str.substr(i, split_len));
    }
    return result;
}


function getTableHeight() {
    var titleHeight = $(".uc-title").height();
    var searchHeight = $(".uc-search").height();
    return 702 - titleHeight - searchHeight - 150;
}

function getMaximin(arr, maximin) {
    if (maximin == "max") {
        return Math.max.apply(Math, arr);
    }
    else if (maximin == "min") {
        return Math.min.apply(Math, arr);
    }
}
/**
 * 获取方法名
 * @param _callee
 * @returns {*}
 */
function getFuncName(_callee) {
    var _text = _callee.toString();
    var _scriptArr = document.scripts;

    for (var i = 0; i < _scriptArr.length; i++) {
        var _start = _scriptArr[i].text.indexOf(_text);
        if (_start != -1) {
            if (/^function\s*.∗.*\r\n/.test(_text)) {
                var _tempArr = _scriptArr[i].text.substr(0, _start).split('\r\n');
                return _tempArr[_tempArr.length - 1].replace(/(var)|(\s*)/g, '').replace(/=/g, '');
            } else {
                var fucName = _text.match(/^function\s*([^\(]+).*\r\n/)[1];

                return _text.match(/^function\s*([^\(]+).*\r\n/)[1];
            }
        }
    }
}
