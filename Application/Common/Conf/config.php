<?php
return array(
    'URL_MODEL' => '2',


    //支付宝配置参数
    'alipay_config'=>array(
        'partner' =>'2088011710753353',   //这里是你在成功申请支付宝接口后获取到的PID；
        'key'=>'jrdrwj6wbwjywtwx27ppwqrkcuplhyp1',//这里是你在成功申请支付宝接口后获取到的Key
        'sign_type'=>strtoupper('MD5'),
        'input_charset'=> strtolower('utf-8'),
        'cacert'=> getcwd().'\\cacert.pem',
        'transport'=> 'http',
    ),
    //以上配置项，是从接口包中alipay.config.php 文件中复制过来，进行配置；

    'alipay'   =>array(
        //这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
        'seller_email'=>'tmall@wowoyoo.com',
//这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
        'notify_url'=>'http://adele.ngrok.cc/home/index/testNotifyurl',
//这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
        'return_url'=>'http://adele.ngrok.cc/home/index/testReturnurl',
//支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）
        'successpage'=>'User/myorder?ordtype=payed',
//支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
        'errorpage'=>'User/myorder?ordtype=unpay',
    ),

//    /* 服务器*/
//    'DB_TYPE'               =>  'mysql',     // ���ݿ�����
//    'DB_HOST'               =>  '192.168.100.254', // ��������ַ
//    'DB_NAME'               =>  'wowo_hotel_front',          // ���ݿ���
//    'DB_USER'               =>  'ww_hotel_front',      // �û���
//    'DB_PWD'                =>  '4PVxB1KTHURV0IDH',          // ����
//    'DB_PORT'               =>  '3306',        // �˿�
//    'DB_PREFIX'             =>  'air_',    // ���ݿ��ǰ׺
//    'DB_DEBUG'  			=>  TRUE, // ���ݿ����ģʽ ��������Լ�¼SQL��־
//    'DB_FIELDS_CACHE'       =>  true,        // �����ֶλ���
//    'DB_CHARSET'            =>  'utf8',      // ���ݿ����Ĭ�ϲ���utf8


// 本地
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '192.168.18.121', // 服务器地址
    'DB_NAME'               =>  'wowo_airchina',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'wowoyoo',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'air_',    // 数据库表前缀
    'DB_DEBUG'  			=>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8


//    'SESSION_AUTO_START'    =>  false,    // 是否自动开启Session
);