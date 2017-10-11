'use strict';

// 日期选择插件(自定义)
var custom_close_date_times=2;  //当点击两次时关掉日历插件
var custom_close_date=0;  //统计点击日历插件的次数
var first_close_date=0; //第一次关闭日期插件

var date = function ($) {

  $.fn.hotelDate = function (options) {

    var nowdate = new Date(); // 获取当前时间
    var dateArr = new Array(); // 获取到的时间数据集合
    var btn = $(this);

    btn.on('click', initTemplate); // 初始化(显示)插件

    // 初始化模板
    function initTemplate() {
      var entertime = $('.entertime').text();
      var leavetime = $('.leavetime').text();
      var listIndex = 0;
      $('body').css({
        overflow: 'hidden'
      });
      // 主容器模板
      var dateTemplate = '\n        <div class =\'date container c-gray\'>\n          <h4 class="tac bold">选择旅行日期</h4>\n          <div class=\'close-btn\'>&lt;&nbsp;返回</div>\n        </div>      \n      ';

      $('body').append(dateTemplate); // 向body添加插件

      // action容器模板
      dateArr.forEach(function (item, index) {
        var template = '\n          <div class=\'action mt10\'>\n            <div class=\'title tac c-blue\'><div class="y">' + item.getFullYear() + '</div>\u5E74<div class="m">' + (item.getMonth() + 1) + '</div>\u6708</div>\n            <ul class=\'week border-bottom\'><li class="weekend_li">\u65E5</li><li>\u4E00</li><li>\u4E8C</li><li>\u4E09</li><li>\u56DB</li><li>\u4E94</li><li class="weekend_li">\u516D</li></ul>\n            <ul class=\'day f-small\'></ul>\n          </div>        \n        ';
        $('.date').append(template);
      });

      // 天数模板 
      $('.action').each(function (index, item) {

        var days = getDays(dateArr[index]); // 当月天数
        var nowweek = dateArr[index].getDay(); // 当月1号是星期几
        for (var i = 0; i < days + nowweek; i++) {
          var template = '';
          // 空白填充
          if (i < nowweek) {
            template = '<li></li>';
          } else if (i < nowdate.getDate() + nowweek - 1 && dateArr[index].getMonth() === nowdate.getMonth()) {
            // 当月已经过去的日期 不能点击
            listIndex++;
            template = '<li index=\'' + listIndex + '\' class=\'disable\'>' + (i - nowweek + 1) + '</li>';
          } else if (dateArr[index].getMonth() == Number(entertime.split('/')[0]) - 1 && i - nowweek + 1 == Number(entertime.split('/')[1])) {
            // 默认入住时间
            listIndex++;
            if(i%7==0 || i%7==6 || i==0 || i==6){ //判断周末
              template = '<li index=\'' + listIndex + '\' date-date="' + dateArr[index].getFullYear() + '-' + (dateArr[index].getMonth() + 1) + '-' + (i - nowweek + 1) + '" class=\'weekend_li\'>' + (i - nowweek + 1) + '</li>';
            }else{
              template = '<li index=\'' + listIndex + '\' date-date="' + dateArr[index].getFullYear() + '-' + (dateArr[index].getMonth() + 1) + '-' + (i - nowweek + 1) + '">' + (i - nowweek + 1) + '</li>';
            }
            //template = '<li index=\'' + listIndex + '\' class=\'enter\' date-date="' + dateArr[index].getFullYear() + '-' + (dateArr[index].getMonth() + 1) + '-' + (i - nowweek + 1) + '">' + (i - nowweek + 1) + '<span class="enter_text"><br>出发</span></li>';
          } else if (dateArr[index].getMonth() == Number(leavetime.split('/')[0]) - 1 && i - nowweek + 1 == Number(leavetime.split('/')[1])) {
            // 默认离开时间
            listIndex++;
            if(i%7==0 || i%7==6 || i==0 || i==6){
              template = '<li index=\'' + listIndex + '\' date-date="' + dateArr[index].getFullYear() + '-' + (dateArr[index].getMonth() + 1) + '-' + (i - nowweek + 1) + '" class=\'weekend_li\'>' + (i - nowweek + 1) + '</li>';
            }else{
              template = '<li index=\'' + listIndex + '\' date-date="' + dateArr[index].getFullYear() + '-' + (dateArr[index].getMonth() + 1) + '-' + (i - nowweek + 1) + '">' + (i - nowweek + 1) + '</li>';
            }
            //template = '<li index=\'' + listIndex + '\' class=\'leave\' date-date="' + dateArr[index].getFullYear() + '-' + (dateArr[index].getMonth() + 1) + '-' + (i - nowweek + 1) + '">' + (i - nowweek + 1) + '<span class="leave_text"><br>返回</span></li>';
          } else {
            listIndex++;
            if(i%7==0 || i%7==6 || i==0 || i==6){
              template = '<li index=\'' + listIndex + '\' date-date="' + dateArr[index].getFullYear() + '-' + (dateArr[index].getMonth() + 1) + '-' + (i - nowweek + 1) + '" class=\'weekend_li\'>' + (i - nowweek + 1) + '</li>';
            }else{
              template = '<li index=\'' + listIndex + '\' date-date="' + dateArr[index].getFullYear() + '-' + (dateArr[index].getMonth() + 1) + '-' + (i - nowweek + 1) + '">' + (i - nowweek + 1) + '</li>';
            }
            //template = '<li index=\'' + listIndex + '\' date-date="' + dateArr[index].getFullYear() + '-' + (dateArr[index].getMonth() + 1) + '-' + (i - nowweek + 1) + '">' + (i - nowweek + 1) + '</li>';
          }
          $(item).find('.day').append(template);
        }
        //return false;
      });

      // 事件监听
      // 关闭插件
      $('.close-btn').on('click', function () {
        if(!$('.enter').parents('.day').siblings('.title').find('.y').text()){
          //mui.alert('请选择出发时间');
          //return false;
        }

        if(!$('.leave').parents('.day').siblings('.title').find('.m').text() && $('.enter').parents('.day').siblings('.title').find('.y').text()){
          alert('请选择退房时间');
          return false;
        }
        custom_close_date=0;

        $('.leave_text').remove();
        $('.enter_text').remove();

        if(!$('.enter').parents('.day').siblings('.title').find('.y').text() && !$('.leave').parents('.day').siblings('.title').find('.m').text()){
          $('.date').remove(); // 移除插件
          $('body').css({ overflow: 'auto' });
          return false;
        } 

        //console.log($('.enter').parents('.day').siblings('.title').find('.y').text());
        // 获取入住时间
        var enterYear = $('.enter').parents('.day').siblings('.title').find('.y').text();
        var enterMonth = $('.enter').parents('.day').siblings('.title').find('.m').text();
        enterMonth.length === 1 ? enterMonth = '0' + enterMonth : false;
        var enterDay = $('.enter').text();
        enterDay.length === 1 ? enterDay = '0' + enterDay : false;
        var enterTime = enterMonth + '/' + enterDay;
        // 获取离开时间
        var leaveYear = $('.enter').parents('.day').siblings('.title').find('.y').text();
        var leaveMonth = $('.leave').parents('.day').siblings('.title').find('.m').text();
        leaveMonth.length === 1 ? leaveMonth = '0' + leaveMonth : false;
        var leaveDay = $('.leave').text();
        leaveDay.length === 1 ? leaveDay = '0' + leaveDay : false;
        var leaveTime = leaveMonth + '/' + leaveDay;
        var night = Number($('.leave').attr('index')) - Number($('.enter').attr('index'));

        if(enterTime!='/'){
          $('.entertime').text(enterYear+'/'+enterTime); // 显示
          $('.input-enter').val(enterYear + '/' + enterTime);
        }else{
          
        }

        if(leaveTime!='/'){
          $('.leavetime').text(enterYear+'/'+leaveTime);
          $('.input-leave').val(leaveYear + '/' + leaveTime);
        }else{
          $('.leavetime').text('');
          $('.input-leave').val('');
          $('.total-day').val('');
          $('.night').text('');
        }
        
        if(night){
          $('.total-day').val(night);
          $('.night').text(night + '天');
        }

        if(enterTime!='/' && leaveTime!='/' && $('.input-enter').val()==$('.input-leave').val()){
          //$('.total-day').val(1);
          //$('.night').text(1 + '天');
        }

        $(".nh_li_left_text").css({
          'color': '#4d4d4d'
        });

        $('.date').remove(); // 移除插件
        $('body').css({ overflow: 'auto' });
      });

      var num = 1;
      // if(first_close_date){
      //   num=1;
      // }
      // 时间选择
      $('.day li').on('click', function () {
        if (!$(this).hasClass('disable')) {
          var thisIndex = Number($(this).attr('index'));
          var enterIndex = Number($('.enter').attr('index'));
          var leaveIndex = Number($('.leave').attr('index'));
          if (num % 2 === 0) {
            if($(this).hasClass('enter')){  //假如是点选了当天的出发日期，就提示
              alert('请不要选择当天返回');
              return false;
            }

            removeLeave();
            $(this).addClass('leave');

            $('.leave_text').remove();
            $(this).append('<span class="leave_text"><br>退房</span>');

            num++;
          } else if (num % 2 !== 0) {
            removeEnter();
            $(this).addClass('enter');

            $('.enter_text').remove();
            $(this).append('<span class="enter_text"><br>入住</span>');

            //去除出发前的日期
            var now_index=$(this).attr('index');  //获取当前点击的day索引
            now_index=parseInt(now_index);
            for (var i = 1; i < now_index; i++) {
              $('.day li[index="'+i+'"]').attr('class','disable');
              $('.day li[index="'+i+'"]').removeAttr('date-date');
            };

            num++;

            $(".nh_date_both").show();
          }

          // 当入住时间 大于 离开时间 两者互换
          if (Number($('.enter').attr('index')) > Number($('.leave').attr('index'))) {
            $('.day .enter')[0].className = 'leave';
            $('.day .leave')[0].className = 'enter';

            $('.leave_text').remove();
            $('.enter_text').remove();
            $('.day .leave').append('<span class="leave_text"><br>退房</span>');
            $('.day .enter').append('<span class="enter_text"><br>入住</span>');
          }

        }

        custom_close_date++;
        if(custom_close_date>=custom_close_date_times){
          setTimeout(function(){
             $('.close-btn').click();
             first_close_date=1;
          },300);
        }
      });
    }

    function removeLeave() {
      $('.day li').removeClass('leave');
      $('.leave_text').remove();
    }

    function removeEnter() {
      $('.day li').removeClass('enter');
      $('.enter_text').remove();
    }

    // 获取num个月的时间数据
    function getDate(num) {

      var year = nowdate.getFullYear();
      var month = nowdate.getMonth() - 1;

      for (var i = 0; i < num; i++) {
        month <= 12 ? month++ : (month = 1, year++);
        var data = new Date(year, month); // 从当前月开始算 一共个6个月的数据
        dateArr.push(data);
      }
    }

    // 获取当月天数
    function getDays(date) {
      //构造当前日期对象
      var date = date;
      //获取年份
      var year = date.getFullYear();
      //获取当前月份
      var mouth = date.getMonth() + 1;
      //定义当月的天数；
      var days;
      //当月份为二月时，根据闰年还是非闰年判断天数
      if (mouth == 2) {
        days = year % 4 == 0 ? 29 : 28;
      } else if (mouth == 1 || mouth == 3 || mouth == 5 || mouth == 7 || mouth == 8 || mouth == 10 || mouth == 12) {
        //月份为：1,3,5,7,8,10,12 时，为大月.则天数为31；
        days = 31;
      } else {
        //其他月份，天数为：30.
        days = 30;
      }
      return days;
    }

    function initDay() {
      var enterYear = String(nowdate.getFullYear());
      var enterMonth = String(nowdate.getMonth() + 1);
      enterMonth.length === 1 ? enterMonth = '0' + enterMonth : false;
      var enterDay = String(nowdate.getDate());
      enterDay.length === 1 ? enterDay = '0' + enterDay : false;
      var enterTime = enterMonth + '/' + enterDay;
      // 获取离开时间
      var leaveYear = String(nowdate.getFullYear());
      var leaveMonth = String(nowdate.getMonth() + 1);
      leaveMonth.length === 1 ? leaveMonth = '0' + leaveMonth : false;
      var leaveDay = String(nowdate.getDate() + 1);
      leaveDay.length === 1 ? leaveDay = '0' + leaveDay : false;
      var leaveTime = leaveMonth + '/' + leaveDay;
      //$('.entertime').text(enterTime); // 显示
      //$('.leavetime').text(leaveTime);
      //$('.input-enter').val(enterYear + '/' + enterTime);
      //$('.input-leave').val(leaveYear + '/' + leaveTime);
    }

    getDate(6); // 获取数据 参数: 拿6个月的数据
    initDay(); // 初始化入住和离店时间
  };
}(jQuery);
