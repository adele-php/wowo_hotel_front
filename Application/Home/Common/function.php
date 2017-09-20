<?php
/**
 * Created by PhpStorm.
 * User: TC
 * Date: 2017/9/6
 * Time: 9:39
*/

function curl($url,$opt=[]){
    $ch = curl_init();
//2.设置URL和相应的选项
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    if(!empty($opt)){
        curl_setopt_array($ch,$opt);
    }
//3.抓取URL并把它传递给浏览器
    $res = curl_exec($ch);
//    echo curl_getinfo($ch)['total_time'].'<br/>';
//4.关闭cURL资源，并且释放系统资源
    curl_close($ch);
    return json_decode($res,true);
}

function curls($urls ,$opt=[]){
    $map = array();
    $responses = array();
    $multi_curl_handle = curl_multi_init();
    $curl_handles = [];
    $key = 0;

    while(1==1){
       foreach($urls as $url){
           $map[$url] = $key;
           $curl_handles[$key] = curl_init();
           curl_setopt($curl_handles[$key], CURLOPT_HEADER, 0);
           curl_setopt($curl_handles[$key], CURLOPT_RETURNTRANSFER, 1);
           if(!empty($opt)){
               curl_setopt_array($curl_handles[$key],$opt);
           }

           curl_setopt($curl_handles[$key],CURLOPT_URL,$url );
           // 把当前 curl resources 加入到 curl_multi_init 队列
           curl_multi_add_handle($multi_curl_handle, $curl_handles[$key]);
           $key++;
       }

        //执行curl multi
        $active = null;
        do {
            $mrc = curl_multi_exec($multi_curl_handle, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active > 0 && $mrc == CURLM_OK) {
            while (curl_multi_exec($multi_curl_handle, $active) === CURLM_CALL_MULTI_PERFORM);
            // 这里 curl_multi_select 一直返回 -1，所以这里就死循环了，CPU就100%了
            if (curl_multi_select($multi_curl_handle, 0.5) != -1)
            {
                do {
                    $mrc = curl_multi_exec($multi_curl_handle, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        foreach($map as $url=>$key){
            $responses[$url]=curl_multi_getcontent($curl_handles[$key]);
            curl_multi_remove_handle($multi_curl_handle, $curl_handles[$key]);
        }

    }

    return  $responses ;
}
/*
    const PAY_WAIT_AUDIT            =   1;//待确认
    const PAY_WAIT_PAY              =   2;//待支付
    const PAY_SUCCESS_WAIT_IN       =   3;//已支付，待入住
    const PAY_CHECK_IN              =   4;//已入住
    const PAY_CANCEL                =   5;//订单取消
    const PAY_COMPLETE              =   100;//订单完成
 * */
function orderStatus( $status ){
    switch($status){
        case 1:
            $status = '待确认';
            break;
        case 2:
            $status = '待支付';
            break;
        case 3:
            $status = '已支付，待入住';
            break;
        case 4:
            $status = '已入住';
            break;
        case 5:
            $status = '订单取消';
            break;
        case 100:
            $status = '订单完成';
            break;
    }
    return $status;
}

function confirmType( $type ){
    switch($type) {
        case 1:
            $type = '立即支付';
            break;
        case 2:
            $type = '二次确认';
            break;
    }
    return $type;
}