  $("#tip-text").bind('change', function() {
      $("#info").html($("#tip-text").val());
  })
  $("#countdown_time").datetimepicker({
      format: 'yyyy-mm-dd hh:ii',
      language: "zh-CN",
      autoclose: true,
      startDate:new Date()
  });

  $("#switch").bind('click', function() {
      if ($(this).attr('src') == '/image/switch2_on.png') {
          $(this).attr('src', '/image/switch2_off.png');
          $("#count_select").val(0);
      } else {
          $(this).attr('src', '/image/switch2_on.png');
          $("#count_select").val(1);
      }
  });

