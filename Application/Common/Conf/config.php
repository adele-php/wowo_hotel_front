<?php
return array(
    'URL_MODEL' => '2',
    /* ���ݿ����� */
    'DB_TYPE'               =>  'mysql',     // ���ݿ�����
    'DB_HOST'               =>  '192.168.18.121', // ��������ַ
    'DB_NAME'               =>  'wowo_airchina',          // ���ݿ���
    'DB_USER'               =>  'root',      // �û���
    'DB_PWD'                =>  'wowoyoo',          // ����
    'DB_PORT'               =>  '3306',        // �˿�
    'DB_PREFIX'             =>  'air_',    // ���ݿ��ǰ׺
    'DB_DEBUG'  			=>  TRUE, // ���ݿ����ģʽ ��������Լ�¼SQL��־
    'DB_FIELDS_CACHE'       =>  true,        // �����ֶλ���
    'DB_CHARSET'            =>  'utf8',      // ���ݿ����Ĭ�ϲ���utf8

    //֧�������ò���
    'alipay_config'=>array(
        'partner' =>'2088011710753353',   //���������ڳɹ�����֧�����ӿں��ȡ����PID��
        'key'=>'jrdrwj6wbwjywtwx27ppwqrkcuplhyp1',//���������ڳɹ�����֧�����ӿں��ȡ����Key
        'sign_type'=>strtoupper('MD5'),
        'input_charset'=> strtolower('utf-8'),
        'cacert'=> getcwd().'\\cacert.pem',
        'transport'=> 'http',
    ),
    //����������Ǵӽӿڰ���alipay.config.php �ļ��и��ƹ������������ã�

    'alipay'   =>array(
        //���������ҵ�֧�����˺ţ�Ҳ����������ӿ�ʱע���֧�����˺�
        'seller_email'=>'tmall@wowoyoo.com',
//�������첽֪ͨҳ��url���ύ����Ŀ��Pay��������notifyurl������
        'notify_url'=>'http://adele.ngrok.cc/home/index/testNotifyurl',
//������ҳ����ת֪ͨurl���ύ����Ŀ��Pay��������returnurl������
        'return_url'=>'http://adele.ngrok.cc/home/index/testReturnurl',
//֧���ɹ���ת����ҳ�棬��������ת����Ŀ��User��������myorder������������payed����֧���б�
        'successpage'=>'User/myorder?ordtype=payed',
//֧��ʧ����ת����ҳ�棬��������ת����Ŀ��User��������myorder������������unpay��δ֧���б�
        'errorpage'=>'User/myorder?ordtype=unpay',
    ),
);