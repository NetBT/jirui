function layer_close_curr() {
    var id = $(".layui-layer.layui-layer-page").attr('times');
    layer.close(id);
}

/**
 * 展示详细内容
 * @param content
 */
function showContent(content) {
    layer_show_content('详细内容',content);
}

/**
 * ajax提交ActiveForm表单
 * @param formId
 * @param url
 * @param method
 */
function ajaxSubmitForm(formId, url, method='POST') {
    $.ajax({
        url : url,
        type : method,
        async: false,
        data : $(formId).serialize(),
        dataType : 'JSON',
        // beforeSend: function ()
        // {
        //     if ($(".has-error", formId).length)
        //     {
        //         return false;
        //     }
        // },
        beforeSend: function ()
        {
            if (url.length == 0)
            {
                return false;
            }
        },
        success: function(data)
        {
            var callBackFunction = '';
            if(data.code == 1000)
            {
                layer.closeAll('page');
                layer_close();
                callBackFunction  = DataTable.drawTable();
                layer.msg(data.message,{icon:6,time:2000},callBackFunction);
            } else {
                layer.msg(data.message,{icon:5,time:2000});
            }
        },
        error: function()
        {
            layer.msg('网络错误',{icon:5,time:2000});
        }
    })
}



/**
 * ajax提交
 * @param url
 * @param params
 * @param callBackFunc
 */
function ajaxSubmit(url, params, callBackFunc, handleResponseFunc) {
    if (callBackFunc === undefined) {
        callBackFunc = '';
    }
    $.ajax({
        url : url,
        type : 'POST',
        async: false,
        data : params,
        dataType : 'JSON',
        beforeSend: function ()
        {
            if (url.length == 0)
            {
                return false;
            }
        },
        success: function(data)
        {
            var callBackFunction = '';
            if(data.code == 1000)
            {
                callBackFunction  = callBackFunc;
            }
            layer.msg(data.message,{icon:6,time:2000},callBackFunction);
            if(handleResponseFunc){
                handleResponseFunc(data);
            }
        },
        error: function()
        {
            layer.msg('网络错误',{icon:5,time:2000});
        }
    });
}
/**
 * 锁定主页刷新，识别当前活动标签，并刷新
 */
function stopRefresh() {
    $("body").bind("keydown",function(event){
        var e = event || window.event || arguments.callee.caller.arguments[0];
        //windows
        if (e.keyCode == 116 || (e.ctrlKey && e.keyCode === 116)) {
            event.preventDefault(); //阻止默认刷新
        }

        var href = $('li.active', '#min_title_list').find('span').attr('data-href');
        $("iframe[src='" + href + "']").attr('src', href);

    })
}

function refreshCurrRefresh() {
    $("body").bind("keydown",function(event){
        var e = event || window.event || arguments.callee.caller.arguments[0];
        //windows
        if (e.keyCode == 116 || (e.ctrlKey && e.keyCode === 116)) {
            event.preventDefault(); //阻止默认刷新
            window.location = location;
        }
        //macos
        if (
            (e.keyCode == 224 && e.keyCode == 82) ||
            (e.keyCode == 224 && e.keyCode == 82 && e.shiftKey)
        ) {
            event.preventDefault(); //阻止默认刷新
            window.location=location;
        }
    })
}
/**
 * ajax提交含有文件上传的表单
 * @param formId
 * @param url
 * @param callBackFunc
 */
function ajaxSubmitFileForm(formId, url, callBackFunc) {
    var formData = new FormData(document.getElementById(formId));
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        /**
         *必须false才会自动加上正确的Content-Type
         */
        contentType: false,
        /**
         * 必须false才会避开jQuery对 formdata 的默认处理
         * XMLHttpRequest会对 formdata 进行正确的处理
         */
        processData: false,
        success: function (data) {
        if (data.code == 1000) {
            var callBackFunction = '';
            if(data.code == 1000)
            {
                callBackFunction  = callBackFunc;
            }
            layer.msg(data.message,{icon:6,time:2000},callBackFunction);
        } else {
            alert(data.message);
        }
    },
    error: function () {
        layer.msg('网络错误',{icon:5,time:2000});
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

var inputControl = {
    leftEl: '.controlLeft',
    rightEl: '.controlRight',
    init: function () {
        this._bindLeftControl();
        this._bindRightControl();
    },
    _bindLeftControl: function () {
        $(this.leftEl).on('click', function () {
            var obj = $(this).next('input');
            var num =  obj.val();
            num = parseInt(num);
            num --;
            if (num < 1) {
                num = 1
            }
            obj.val(num);
            changeCartNum(obj.attr('data-id'),obj);
        });
    },
    _bindRightControl: function () {
        $(this.rightEl).on('click', function () {
            var obj = $(this).prev('input');
            var num =  obj.val();
            num = parseInt(num);
            num ++;
            obj.val(num);
            changeCartNum(obj.attr('data-id'), obj);
        });
    }
};

var numberControl = {
    leftEl: '.controlLeft',
    rightEl: '.controlRight',
    init: function () {
        this._bindLeftControl();
        this._bindRightControl();
    },
    _bindLeftControl: function () {
        $(this.leftEl).on('click', function () {
            var obj = $(this).next('input');
            var num =  obj.val();
            num = parseInt(num);
            num --;
            if (num < 1) {
                num = 1
            }
            obj.val(num);
        });
    },
    _bindRightControl: function () {
        $(this.rightEl).on('click', function () {
            var obj = $(this).prev('input');
            var num =  obj.val();
            num = parseInt(num);
            num ++;
            obj.val(num);
        });
    }
};

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

function addRequireFlag() {
   var $obj = $('.required','div').find('label.form-label');
   $obj.each(function() {
       var label = '<i class="fa fa-asterisk text-hot notice-require"></i>' + $(this).html().replace(/<i class="fa fa-asterisk text-hot notice-require"><\/i>/, '');
       // var label = '<span class="text-hot" style="position: relative; font-size: 14px; top: 3px; right: 5px;">*</span>' + $(this).html();
       $(this).html(label);
   });
   // $.each($obj,function(k,v){
   //
   // });
   // var label = '<span class="text-hot" style="position: relative; font-size: 14px; top: 3px; right: 5px;">*</span>' + $obj.html();
   // $obj.html(label);
}
