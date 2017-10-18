function copyText(obj) {
    var copyObj = $(obj).prev();
    copyObj.select();
    document.execCommand("Copy"); // 执行浏览器复制命令
    $("#insertModal").modal('toggle');
}


function insetAddress(iframeAd, flashAd, htmlAd) {
    $("#iframe-text").val(iframeAd);
    $("#flash-text").val(flashAd);
    $("#h5-text").val(htmlAd);

    $("#insertModal").modal();
}
$('#timer').daterangepicker({
    // startDate: moment().startOf('day'),
    //endDate: moment(),
    //minDate: '01/01/2012',    //最小时间
    maxDate: moment(), //最大时间
    dateLimit: {
        days: 30
    }, //起止时间的最大间隔
    showDropdowns: true,
    showWeekNumbers: false, //是否显示第几周
    timePicker: false, //是否显示小时和分钟
    ranges: {
        //'最近1小时': [moment().subtract('hours',1), moment()],
        '今日': [moment().startOf('day'), moment()],
        '昨日': [moment().subtract('days', 1).startOf('day'), moment().subtract('days', 1).endOf('day')],
        '最近7日': [moment().subtract('days', 6), moment()],
        '最近30日': [moment().subtract('days', 29), moment()]
    },
    opens: 'right', //日期选择框的弹出位置
    buttonClasses: ['btn btn-default'],
    applyClass: 'btn-small btn-primary blue',
    cancelClass: 'btn-small',
    format: 'YYYY-MM-DD ', //控件中from和to 显示的日期格式
    separator: ' to ',
    locale: {
        applyLabel: '确定',
        cancelLabel: '取消',
        fromLabel: '起始时间',
        toLabel: '结束时间',
        customRangeLabel: '自定义',
        daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
        monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
            '七月', '八月', '九月', '十月', '十一月', '十二月'
        ],
        firstDay: 1
    }
}, function(start, end, label) { //格式化日期显示框

    $('#reportrange span').html(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format('YYYY-MM-DD HH:mm:ss'));
});

$(".content-item").each(function(index) {
    $(this).bind('mouseover', function() {
        $(this).find('.add-address').removeClass('hide');
    });
    $(this).bind('mouseleave', function() {
        $(this).find('.add-address').addClass('hide');
    })
    $(this).bind('click', function() {
        if ($(this).hasClass('choosed')) {
            $(this).removeClass('choosed');
        } else {
            $(this).addClass('choosed');
        }
    })
})

function selectAll() {
    $(".content-item").each(function(index) {
        if ($(this).hasClass('choosed')) {
            $(this).removeClass('choosed');
        } else {
            $(this).addClass('choosed');
            $("#selectAll").hide();
            $("#cancelSelect").show();

        }
    })
}

function cancelSelect() {
    $(".content-item").each(function(index) {
        $(this).removeClass('choosed');
        $("#cancelSelect").hide();
        $("#selectAll").show();
    })
}
