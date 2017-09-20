<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function __construct() {
        parent::__construct();
        $_GET = I('get.');
    }
    public function index(){

        if(isset($_GET['city_id'])){
            $this->assign('city',['cname'=>I('get.city'),'id'=>I('get.city_id')]);
        }

        if( isset($_GET['adult_count'])){
            $people = ['adult_count'=>$_GET['adult_count'],'child_count'=>$_GET['child_num'],'children_ages'=>$_GET['children_ages']];
            $this->assign('people',$people);
            $this->assign('people_query',http_build_query($people));
        }

        $time = array();
        if( isset($_GET['check_in'])){
            $time['start_time'] = $_GET['check_in'];
            unset($_GET['check_in']);
        }
        if( isset($_GET['check_out'])){
            $time['end_time'] = $_GET['check_out'];
            unset($_GET['check_out']);
        }

        $spt = S('keyword');
        if(false===$spt){
            $spt = curl('http://demo.wowoyoo.com/airchina_api/keywords');
            if($spt['status']!=1){
                $this->error();
            }
            S('keyword',$spt,90000);
        }


        $keyword_default = [];
        $keyword_show    = [];
        if( isset($_GET['prices']) && I('get.prices')!=-1 ){
            $keyword_default['prices'] = trim(I('get.prices'));
            $keyword_show['prices'] = trim(I('get.prices'));
        }
        if( isset($_GET['stars']) && I('get.stars')!=-1 ){
            $keyword_default['stars'] = trim(I('get.stars'));
            $keyword_show['stars'] = $spt['data']['stars'][$keyword_default['stars']];
        }
        if( isset($_GET['hotel_types']) && I('get.hotel_types')!=-1 ){
            $keyword_default['hotel_types'] = trim(I('get.hotel_types'));
            $keyword_show['hotel_types'] = $spt['data']['hotel_types'][$keyword_default['hotel_types']];
        }


        $this->assign('keyword_default',$keyword_default);
        $this->assign('keyword_show',$keyword_show);
        $this->assign('time',$time);

        $query = http_build_query(I('get.'));
        $this->assign('data',I('get.'));
        $this->assign('query',$query);
        $this->display();
    }

    public function selectCity(){
        $data = S('citys');
        if(false===$data){
            $data = curl('http://demo.wowoyoo.com/airchina_api/citys');
            S('citys',$data,30000);
        }
        $citys = [];
        if($data['status']!=1){
            $this->error('error',U('home/index/index'));
        }
        $data = $data['data'];
        foreach($data['国内'] as $v){
            $citys['国内']['prefix'][$v['prefix']][]=$v;
        }
        foreach($data['国外'] as $v){
            $citys['国外']['prefix'][$v['prefix']][]=$v;
        }
        $citys['国内']['hot'] = $data['国内热门'];
        $citys['国外']['hot'] = $data['国外热门'];
        ksort($citys['国内']['prefix']);
        ksort($citys['国外']['prefix']);


        $this->assign('citys',$citys);

        if( isset($_GET['city_id']) ){
            unset($_GET['city_id']);
        }
        if( isset($_GET['city']) ){
            unset($_GET['city']);
        }

        $query = http_build_query(I('get.'));
        $this->assign('query',$query);
        $this->display();
    }

    //联想搜索
    public function associativeSearch(){
        $data = S('citys');
        if(false===$data){
            $data = curl('http://demo.wowoyoo.com/airchina_api/citys');
            S('citys',$data,30000);
        }

        $city = I('post.city');
        $preg_city = array();
        //模糊匹配国内
        foreach($data['data']['国内'] as $k=>$v){
            //拼音匹配
            if( preg_match("/.*{$city}.*/is",$v['ename']) ){
                $preg_city[] = [
                    'country_cn'=>$v['country_cn'],                                     //国家
                    'state_cn'=>empty($v['state_cn'])?$v['country_cn']:$v['state_cn'],  //省份
                    'cname'=>$v['cname'],                                               //城市
                    'id'=>$v['id'],
                ];
            }
            //中文匹配
            if( preg_match("/.*{$city}.*/",$v['cname']) ){
                $preg_city[] = [
                    'country_cn'=>$v['country_cn'],                                     //国家
                    'state_cn'=>empty($v['state_cn'])?$v['country_cn']:$v['state_cn'],  //省份
                    'cname'=>$v['cname'],                                               //城市
                    'id'=>$v['id'],
                ];
            }
        }

        //模糊匹配国外
        foreach($data['data']['国外'] as $k=>$v){
            //拼音匹配
            if( preg_match("/.*{$city}.*/i",$v['ename']) ){
                $preg_city[] = [
                    'country_cn'=>$v['country_cn'],                                     //国家
                    'state_cn'=>empty($v['state_cn'])?$v['country_cn']:$v['state_cn'],  //省份
                    'cname'=>$v['cname'],                                               //城市
                    'id'=>$v['id'],
                ];
            }
            //中文匹配
            if( preg_match("/.*{$city}.*/",$v['cname']) ){
                $preg_city[] = [
                    'country_cn'=>$v['country_cn'],                                     //国家
                    'state_cn'=>empty($v['state_cn'])?$v['country_cn']:$v['state_cn'],  //省份
                    'cname'=>$v['cname']     ,                                          //城市
                    'id'=>$v['id'],
                ];
            }
        }
        echo json_encode($preg_city);

    }

    public function selectPeople(){
        //孩子年龄
        if(I('get.child_num')!=0){
            $child_ages = explode('-',I('get.children_ages'));
            array_pop($child_ages);
            $this->assign('child_ages',json_encode($child_ages));
        }

        $this->assign('data',I('get.'));
        if( isset($_GET['adult_count']) ){
            unset($_GET['adult_count']);
        }

        if( isset($_GET['child_num']) ){
            unset($_GET['child_num']);
        }

        if( isset($_GET['children_ages']) ){
            unset($_GET['children_ages']);
        }

        $query = http_build_query(I('get.'));
        $this->assign('query',$query);
        $this->display();
    }

    public function selectOther(){

        $keyword = S('keyword');
        if(false===$keyword){
            $keyword = curl('http://demo.wowoyoo.com/airchina_api/keywords');
            if($keyword['status']!=1){
                $this->error();
            }
            S('keyword',$keyword,90000);
        }

        $keyword_default = ['stars'=>-1,'prices'=>-1,'hotel_types'=>-1];
        if( isset($_GET['prices']) ){
            $keyword_default['prices'] = trim(I('get.prices'));
            unset($_GET['prices']);
        }
        if( isset($_GET['stars']) ){
            $keyword_default['stars'] = trim(I('get.stars'));
            unset($_GET['stars']);
        }
        if( isset($_GET['hotel_types']) ){
            $keyword_default['hotel_types'] = trim(I('get.hotel_types'));
            unset($_GET['hotel_types']);
        }

        $this->assign('keyword_default',$keyword_default);
        $query = http_build_query(I('get.'));
        $this->assign('query',$query);
        $this->assign('keyword',$keyword['data']);
        $this->display();
    }

    public function hotelList(){
        $_GET['keyword'] = $_GET['keyword']==='thinkphp'?'':$_GET['keyword'];
        $this->assign('data',I('get.'));
        $opt = [
            CURLOPT_POST=>1,
            CURLOPT_POSTFIELDS=>$_GET
        ];
        $hotel = curl('http://demo.wowoyoo.com/airchina_api/hotel_query',$opt);

        if($hotel['status']!=1){
            $this->error($hotel['errmsg']);
        }

        if( isset($_GET['child_num'])){
            $_GET['children_count'] = $_GET['child_num'];
            unset($_GET['child_num']);
        }

        if(isset($_GET['children_ages']) ){
            $_GET['children_ages'] = explode('-',$_GET['children_ages']);
            array_pop($_GET['children_ages']);
            $_GET['children_ages'] = implode(',',$_GET['children_ages']);
        }


        $query = http_build_query($_GET);
        $this->assign('hotel',$hotel);
        $this->assign('query',$query);

        $this->display();
    }

    public function details(){
        $query = http_build_query(I('get.'));
//        $hotel_rooms = curl('http://demo.wowoyoo.com/airchina_api/hotel_rooms?'.$query);
//        var_dump($hotel_rooms);die;
//        $hotel_info = curl('http://demo.wowoyoo.com/airchina_api/hotel_info?hotel_id='.I('hotel_id'));
//        $hotel_img = curl('http://demo.wowoyoo.com/airchina_api/hotel_picture?hotel_id='.I('hotel_id'));


//    var_dump($hotel_rooms);die;
//        if($hotel_rooms['status']!=1){
//            $this->error($hotel_rooms['errmsg']);
//        }
//        $this->assign('hotel_info',$hotel_info['data']);
//        $this->assign('hotel_img',['count'=>count($hotel_img['data']['paths']),'paths'=>$hotel_img['data']['paths']]);
//        $this->assign('hotel_rooms',$hotel_rooms['data']);
//
//        foreach( $hotel_rooms['data'] as $v){
//            foreach($v['list'] as $list){
//                $data = S($list['rate_code']);
//                if(false===$data){
//                    S($list['rate_code'],$list,3000);
//                }
//            }
//        }
        $this->assign('data',$_GET);
        $this->assign('query',$query);
        $this->display();
    }

    public function rooms(){
        $query = http_build_query(I('get.'));
        $hotel_rooms = curl('http://demo.wowoyoo.com/airchina_api/hotel_rooms?'.$query);
        if($hotel_rooms['status']!=1){
            echo json_encode($hotel_rooms);die;
        }
        foreach( $hotel_rooms['data'] as $v){
            foreach($v['list'] as $list){
                $data = S($list['rate_code']);
                if(false===$data){
                    S($list['rate_code'],$list,3000);
                }
            }
        }
        echo json_encode($hotel_rooms['data']);
    }
    public function roomImgs(){
        $hotel_img = curl('http://demo.wowoyoo.com/airchina_api/hotel_picture?hotel_id='.I('hotel_id'));
        $hotel_img = [
            'count'=>count($hotel_img['data']['paths']),
            'paths'=>$hotel_img['data']['paths']
        ];
        echo json_encode($hotel_img);
    }
    //酒店图片
    public function hotelImg(){
        $hotel_img = curl('http://demo.wowoyoo.com/airchina_api/hotel_picture?hotel_id='.I('hotel_id'));
        $this->assign('hotel_img',$hotel_img['data']);
        $this->display();
    }

    public function hotelDetail(){
        $hotel_info = curl('http://demo.wowoyoo.com/airchina_api/hotel_info?hotel_id='.I('hotel_id'));
        $this->assign('hotel_info',$hotel_info['data']);
        $this->display();
    }

    public function order(){
//        var_dump(session());die;
        $hotel_info = curl('http://demo.wowoyoo.com/airchina_api/hotel_info?hotel_id='.I('hotel_id'));
        //房型信息
        if(false === $data=S($_GET['rate_code'])){
            $this->error('过期，重新选择房型');
        }

//        var_dump($data);die;
        $this->assign('data',$data);

        $this->assign('info',$_GET);
        $this->assign('hotel_info',$hotel_info['data']);
        $this->display();
    }

    public function createOrder(){

        if(empty(session('mobile'))){
            echo 0;return;
        }
        $oid = M('order')->add([
            'contact_tel'     =>I('post.mobile'),
            'contact_username'=>I('post.name'),
            'city_id'         =>I('post.city_id'),
            'confirm_type'    =>I('post.confirm_type'),
            'hotel_id'        =>I('post.hotel_id'),
            'room_count'      =>I('post.room_count'),
            'adult_count'     =>I('post.adult_count'),
            'children_count'  =>I('post.children_count'),
            'children_ages'   =>I('post.children_ages'),
            'mobile'          =>session('mobile'),    //TODO 用户登录手机号
        ]);
        echo $oid;
        //没有创建成功(已创建重复刷新) TODO

    }

    //二次确认---订单提交成功，等待审核 页面
    public function orderCheck(){
        //用户重复刷新
        if( $order_detail=S(I('get.oid')) ){
            $this->assign('data',$order_detail['data']);
            $this->display();
            return '';
        }

        //订单信息
        if(false === $order_detail=S(I('get.oid'))){
            $opt = [
                CURLOPT_POST=>1,
                CURLOPT_POSTFIELDS=>['order_no'=>I('get.oid'),'wowo_order_no'=>I('get.wowo_order_no')]
            ];
            $order_detail = curl('http://demo.wowoyoo.com/airchina_api/order_detail',$opt);
            S(I('get.oid'),$order_detail);
        }

        //订单状态日志
        M('order_status_log')->add([
            'order_no'=>$order_detail['data']['order_no'],
            'wowo_order_no'=>$order_detail['data']['wowo_order_no'],
            'order_status'=>$order_detail['data']['order_status'],
            'order_log_type'=>$order_detail['data']['order_logs'][0]['type'],
            'order_log_remark'=>$order_detail['data']['order_logs'][0]['remark'],
            'order_log_time'=>$order_detail['data']['order_logs'][0]['time']['date'],
        ]);

        //订单信息补充
        M('order')
            ->where("order_no=".$order_detail['data']['order_no'])
            ->save([
                'order_status'=>$order_detail['data']['order_status'],
                'room_name'=>$order_detail['data']['room_name'],
                'check_in'=>$order_detail['data']['check_in'],
                'check_out'=>$order_detail['data']['check_out'],
                'total_money'=>$order_detail['data']['total_money'],
                'wowo_order_no'=>$order_detail['data']['wowo_order_no'],
                'rate_code'=>$order_detail['data']['rate_code'],
                'confirm_code'=>$order_detail['data']['confirm_code'],
        ]);
        $this->assign('data',$order_detail['data']);
        $this->display();

    }

    //支付页面
    public function pay(){
        //用户重复刷新
        if( $order_detail=S(I('get.oid')) ){
            $this->assign('data',$order_detail['data']);
            $this->display();
            return '';
        }

        //订单信息
        if(false === $order_detail=S(I('get.oid'))){
            $opt = [
                CURLOPT_POST=>1,
                CURLOPT_POSTFIELDS=>['order_no'=>I('get.oid'),'wowo_order_no'=>I('get.wowo_order_no')]
            ];
            $order_detail = curl('http://demo.wowoyoo.com/airchina_api/order_detail',$opt);
            S(I('get.oid'),$order_detail);
        }

        //订单状态日志
        M('order_status_log')->add([
            'order_no'=>$order_detail['data']['order_no'],
            'wowo_order_no'=>$order_detail['data']['wowo_order_no'],
            'order_status'=>$order_detail['data']['order_status'],
            'order_log_type'=>$order_detail['data']['order_logs'][0]['type'],
            'order_log_remark'=>$order_detail['data']['order_logs'][0]['remark'],
            'order_log_time'=>$order_detail['data']['order_logs'][0]['time']['date'],
        ]);

        //订单信息补充
        M('order')
            ->where("order_no=".$order_detail['data']['order_no'])
            ->save([
                'order_status'=>$order_detail['data']['order_status'],
                'room_name'=>$order_detail['data']['room_name'],
                'check_in'=>$order_detail['data']['check_in'],
                'check_out'=>$order_detail['data']['check_out'],
                'total_money'=>$order_detail['data']['total_money'],
                'wowo_order_no'=>$order_detail['data']['wowo_order_no'],
                'rate_code'=>$order_detail['data']['rate_code'],
                'confirm_code'=>$order_detail['data']['confirm_code'],
            ]);
//        var_dump($order_detail);die;
        $this->assign('data',$order_detail['data']);
        $this->display();
    }


    public function orderDetail(){
        $condition = ['order_no'=>I('get.oid'),'wowo_order_no'=>I('get.wowo_order_no')];
        $data = M('order')
            ->where($condition)
            ->find();
        $data['contact_username'] = $this->_handleName($data['contact_username']);
        $status = M('order_status_log')
            ->where($condition)
            ->select();

        $this->assign('status',$status);
        $this->assign('data',$data);
        $this->display();
    }
    private function _handleName($name){
        //丁:流水
        return str_replace(':','',$name);
    }

    public function modifyOrder(){
        $condition = ['order_no'=>I('get.oid'),'wowo_order_no'=>I('get.wowo_order_no')];

        $order_detail = M('order')->where($condition)->find();
        $contact_username = explode('|',$order_detail['contact_username']);
        $order_detail['room_count'] = count($contact_username);

        foreach($contact_username as $k=>$v){
            $contact_username[$k] = explode(':',$v);
        }

        $this->assign('contact_username',$contact_username);
        $this->assign('data',$order_detail);
        $this->display();
    }

    //订单修改接口
    public function orderModify(){
        $opt = [
            CURLOPT_POST=>1,
            CURLOPT_POSTFIELDS=>I('post.')
        ];
        $res = curl('http://demo.wowoyoo.com/airchina_api/change_order',$opt);
        if( $res['status']!=1 ){
            echo json_encode($res);
            return;
        }

        $condition = ['order_no'=>I('post.order_no'),'wowo_order_no'=>I('post.wowo_order_no')];
        $order = M('order')->where($condition)->find();
        $order['room_username_list'] = $order['contact_username'];
        $order['contact_username'] = $this->_handleName(explode('|',$order['contact_username'])[0]);

        //得到修改的内容
        $keys = array_keys(I('post.'));
        foreach($order as $k =>$v ){
            if( !in_array($k,$keys) ){unset($order[$k]);}
        }
        $diff = array_diff(I('post.'),$order);
        $diff_keys = array_keys($diff);
        $map = [
            'check_in'=>'入住时间',
            'check_out'=>'离店时间',
            'room_count'=>'房间数',
            'contact_tel'=>'联系电话',
            'contact_username'=>'联系人',
            'room_username_list'=>'入住人',
        ];

        $res = ['status'=>1];
        foreach($diff_keys as $key){
            $res['msg'] .= isset($map[$key])?($map[$key].'变更、'):'';
        }
        $res['msg']=substr($res['msg'],0,-strlen('、'));
        echo json_encode($res);

    }

    //订单修改提交 审核页
    public function orderModifyCheck(){
        $condition = ['order_no'=>I('get.oid'),'wowo_order_no'=>I('get.wowo_order_no')];
        $data = M('order')
            ->where($condition)
            ->find();

        $this->assign('data',$data);
        $this->assign('change',I('get.change'));
        $this->display();
    }

    public function roomInfo(){
        $query = http_build_query(I('get.'));
        $hotel_rooms = curl('http://demo.wowoyoo.com/airchina_api/hotel_rooms?'.$query);
        $hotel_rooms = $hotel_rooms['data'];

        $info = [];
        foreach( $hotel_rooms as $room){
            if($room['cname']==I('room_name')){
                foreach($room['list'] as $v){
                    if($v['rate_code']==I('rate_code')){
                        foreach($v['money_list'] as $val){
                            foreach( $val as $time => $money){
                                $info[] = ['time'=>$time,'money'=>$money,'breakfast'=>$v['breakfast']];
                            }
                        }
                        break;
                    }
                }
                break;
            }
        }

        echo json_encode($info);
    }


    //订单列表
    public function orderList(){
        $mobile = '18006661209';
        $orders = M('order')
            ->where('mobile='.$mobile)
            ->select();

        foreach( $orders as $k=>$v){
            $orders[$k]['contact_username'] = $this->_handleName(explode('|',$orders[$k]['contact_username'])[0]);
        }

        $this->assign('orders',$orders);
        $this->display();
    }

    //订单取消申请页
    public function orderCancel(){
        $condition = ['order_no'=>I('get.oid'),'wowo_order_no'=>I('get.wowo_order_no')];
        $data = M('order')
            ->where($condition)
            ->find();
        $data['contact_username'] = $this->_handleName($data['contact_username']);

        $this->assign('data',$data);
        $this->display();
    }
    //订单取消接口
    public function cancelOrder(){
        $opt = [
            CURLOPT_POST=>1,
            CURLOPT_POSTFIELDS=>I('get.')
        ];
        echo json_encode( curl('http://demo.wowoyoo.com/airchina_api/cancel_order',$opt) );

    }

    //订单取消提交 审核页
    public function orderCancelCheck(){
        $condition = ['order_no'=>I('get.oid'),'wowo_order_no'=>I('get.wowo_order_no')];
        $data = M('order')
            ->where($condition)
            ->find();

        $this->assign('data',$data);
        $this->display();
    }

    //一键延住
    public function extend(){
        $condition = ['order_no'=>I('get.oid'),'wowo_order_no'=>I('get.wowo_order_no')];

        $data = M('order')->where($condition)->find();
        $data['room_user_list']   = $data['contact_username'];
        $data['contact_username'] = $this->_handleName(explode('|',$data['contact_username'])[0]);

        $this->assign('data',$data);
        $this->display();
    }
    //二次确认---延住提交成功，等待审核 页面
    public function extendCheck()
    {
        $condition = ['order_no'=>I('get.oid'),'wowo_order_no'=>I('get.wowo_order_no')];
        //订单信息
        $opt = [
            CURLOPT_POST=>1,
            CURLOPT_POSTFIELDS=>$condition
        ];
        $order_detail = curl('http://demo.wowoyoo.com/airchina_api/order_detail',$opt);

        //用户重复刷新 TODO

        //订单状态日志
        M('order_status_log')->add([
            'order_no'=>$order_detail['data']['order_no'],
            'wowo_order_no'=>$order_detail['data']['wowo_order_no'],
            'order_status'=>$order_detail['data']['order_status'],
            'order_log_type'=>$order_detail['data']['order_logs'][0]['type'],
            'order_log_remark'=>$order_detail['data']['order_logs'][0]['remark'],
            'order_log_time'=>$order_detail['data']['order_logs'][0]['time']['date'],
        ]);

        //订单信息补充
        M('order')
            ->where("order_no=".I('get.oid'))
            ->save([
                'order_status'=>$order_detail['data']['order_status'],
                'room_name'=>$order_detail['data']['room_name'],
                'check_in'=>$order_detail['data']['check_in'],
                'check_out'=>$order_detail['data']['check_out'],
                'total_money'=>$order_detail['data']['total_money'],
                'wowo_order_no'=>$order_detail['data']['wowo_order_no'],
                'rate_code'=>$order_detail['data']['rate_code'],
                'confirm_code'=>$order_detail['data']['confirm_code'],
            ]);
        $order     =  M('order')->where($condition)->find();

        $this->assign('data',$order);
        $this->display();
    }
    //订单变更
    public function orderChange(){
        $condition = [
            'order_no'=>I('get.oid'),
            'wowo_order_no'=>I('get.wowo_order_no'),
        ];
        $order = M('order')->where($condition)->find();

        $condition['check_in']           = empty(I('get.check_in'))          ?$order['check_in']          :I('get.check_in');
        $condition['check_out']          = empty(I('get.check_out'))         ?$order['check_out']         :I('get.check_out');
        $condition['room_count']         = empty(I('get.room_count'))        ?$order['room_count']        :I('get.room_count');
        $condition['contact_tel']        = empty(I('get.contact_tel'))       ?$order['contact_tel']       :I('get.contact_tel');
        $condition['contact_username']   = empty(I('get.contact_username'))  ?$order['contact_username']  :I('get.contact_username');
        $condition['room_username_list'] = empty(I('get.room_username_list'))?$order['room_username_list']:I('get.room_username_list');

        $opt = [
            CURLOPT_POST=>1,
            CURLOPT_POSTFIELDS=>$condition
        ];
        echo curl('http://demo.wowoyoo.com/airchina_api/change_order',$opt);
    }

    //支付结果页
    public function paySuccess(){
        $condition = ['order_no'=> I('get.oid')];
        $order = M('order')->where($condition)->find();
        $this->assign('data',$order);
        $this->display();
    }
    //支付失败
    public function payFail(){
        $condition = ['order_no'=>I('get.oid'),'wowo_order_no'=>I('get.wowo_order_no')];
        $order = M('order')
            ->where(['order_no'=>I('get.oid'),'wowo_order_no'=>I('get.wowo_order_no')])
            ->find();
        $this->assign('data',$order);
        $this->display();
    }

    //支付  调用支付宝接口
    public function payMoney(){
        $condition = ['order_no'=>I('get.oid'),'wowo_order_no'=>I('get.wowo_order_no')];
        //校对价格
        $order_info = M('order')->where($condition)->find();
        if($order_info['total_money']!=I('get.total_money')){
            //TODO  error
            die('请勿更改价格！');
        }
        $param = array(
            "out_trade_no"    => $order_info['order_no'],
            "subject"         => $order_info['room_name'],
            "total_fee"       => $order_info['total_money'],
            "body"            => $order_info['room_name'].' 从 '.$order_info['check_in'].' 到 '.$order_info['check_out'],
        );
        $this->order_no =I('get.wowo_order_no');
        $this->alipay($param);
    }

    //TODO
    public function asjd(){
        $opt = [
            CURLOPT_POST=>1,
            CURLOPT_POSTFIELDS=>['order_no'=>I('get.oid'),'wowo_order_no'=>I('get.wowo_order_no'),'total_money'=>I('get.total_money')]
        ];
        $payment_success = curl('http://demo.wowoyoo.com/airchina_api/payment_success',$opt);
    }

    /* ************************支付宝支付**************************** */


    private function _alipayInit(){
        vendor('Alipay.Corefunction');
        vendor('Alipay.Md5function');
        vendor('Alipay.Notify');
        vendor('Alipay.Submit');
    }
    public function alipay($param=array()){
        $this->_alipayInit();
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            "partner" => trim(C('alipay_config.partner')),
            "_input_charset"    => trim(strtolower(C('alipay_config.input_charset'))),
            "payment_type"    => "1",
            "notify_url"    => C('alipay.notify_url'),
            "return_url"    => C('alipay.return_url'),
            "seller_email"    => C('alipay.seller_email'),
            "out_trade_no"    => '201709150016',                 //订单号
            "subject"    => 'test',                           //订单名称
            "total_fee"    => '0.01',                       //金额
            "body"            => '美国专业护腕鼠标垫，舒缓式凝胶软垫模拟手腕的自然曲线和运动，创造和缓的GelFlex舒适地带！',                         //描述
            "show_url"    => '',                         //商品展示地址
            "anti_phishing_key"    => "",
            "exter_invoke_ip"    => get_client_ip(),
        );
        $parameter = array_merge($parameter,$param);
        //建立请求
        $alipay_config=C('alipay_config');
        $alipaySubmit = new \AlipaySubmit($alipay_config);

        $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
        echo $html_text;
    }

    public function testReturnurl(){
        //导入接口文件
        $this->_alipayInit();
        $alipay_config=C('alipay_config');

        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();

        if($verify_result) {
            //验证成功
            $this->redirect( 'home/index/paySuccess',array('oid'=>I('get.out_trade_no')),2,'即将进入支付结果页' );
        }else{
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            echo "支付失败！";
        }
    }

    public function testNotifyurl()
    {
        $this->_alipayInit();

        //这里还是通过C函数来读取配置项，赋值给$alipay_config
        $alipay_config = C('alipay_config');
        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if ($verify_result || 1==1) {
            //验证成功  插入notice  log
            M('alipay_notice_log')->add(I('post.'));

            //对账
            $condition=[
                'order_no'=>I('post.out_trade_no'),   //订单号
                'room_name'=>trim(I('post.subject')),        //商品名称
                'total_money'=>I('post.total_fee'),      //订单的总金额
            ];
            $order =  M('order')->where($condition);
            $order_info = $order->find();
//            file_put_contents(__DIR__.'/1.txt',$order_info);die;
            if(!$order_info){
                //对账失败 TODO

            }

            if ($_POST['trade_status'] == 'TRADE_FINISHED' ||$_POST['trade_status'] == 'TRADE_SUCCESS' ) {
                //wowoyou推送支付成功
                $opt = [
                    CURLOPT_POST=>1,
                    CURLOPT_POSTFIELDS=>[
                        'order_no'=>$order_info['order_no'],
                        'wowo_order_no'=>$order_info['wowo_order_no'],
                        'total_money'=>$order_info['total_money']
                    ],
                ];
                $payment_success = curl('http://demo.wowoyoo.com/airchina_api/payment_success',$opt);

                if($payment_success['status']!=1){
                    //接收失败 TODO 金额不一致
                }

                //修改本地订单支付状态
                $res = M('order')->where($condition)->save(['order_status'=>3]);

                //增加订单状态变更日志
                M('order_status_log')->add([
                    'order_no'=>$order_info['data']['order_no'],
                    'wowo_order_no'=>$order_info['data']['wowo_order_no'],
                    'order_status'=>3,
                    'order_log_type'=>'订单支付',
                    'order_log_remark'=>'支付成功',
                    'order_log_time'=>I('post.gmt_payment'),
                ]);

                echo "success";        //请不要修改或删除
            }

        } else {
            //验证失败
            echo "fail";
        }
    }



    // ------------------------------------登录注册------------------------------------
    // 登录页
    public function login(){
        if(!empty(I('post.check'))){
            $verify = new \Think\Verify();
            //验证验证码
            if( !$verify->check(I('post.code')) ){
                echo json_encode(['status'=>0,'errmsg'=>'验证码错误！']);return;
            }

            unset($_POST['check']);
            $opt = [
                CURLOPT_POST=>1,
                CURLOPT_POSTFIELDS=>I('post.'),
                CURLOPT_SSL_VERIFYPEER=>FALSE,
                CURLOPT_SSL_VERIFYHOST=>FALSE
            ];
            $userinfo = curl('https://wowoyoo.com/member_api/login',$opt);
            if($userinfo['status']==0){
                //登录失败
                echo json_encode($userinfo);return;
            }
            session('mobile',$userinfo['data']['mobile']);
            echo json_encode(['status'=>1]);return;

        }
        $this->display();
    }
    //验证码
    public function code(){
        $Verify =     new \Think\Verify();
        $Verify->useImgBg = true;
        $Verify->entry();
    }

    //注册页
    public function register(){
        if(!empty(I('post.check'))){
            $verify = new \Think\Verify();
            //验证验证码
            if( !$verify->check(I('post.code')) ){
                echo json_encode(['status'=>0,'errmsg'=>'验证码错误！']);
            }

            unset($_POST['check']);
            $opt = [
                CURLOPT_POST=>1,
                CURLOPT_POSTFIELDS=>I('post.'),
                CURLOPT_SSL_VERIFYPEER=>FALSE,
                CURLOPT_SSL_VERIFYHOST=>FALSE
            ];
            $userinfo = curl('https://wowoyoo.com/member_api/register',$opt);
            echo json_encode($userinfo);
            return;
        }
        $this->display();
    }

    //找回密码
    public function getPassword(){
        $opt = [
            CURLOPT_SSL_VERIFYPEER=>FALSE,
            CURLOPT_SSL_VERIFYHOST=>FALSE
        ];
        $userinfo = curl('https://wowoyoo.com/member_api/getpass?mobile='.I('get.mobile'),$opt);
        echo json_encode($userinfo);
    }

    //自动注册 初始密码 000000
    public function autoRegisterAndLogin(){
        if(!empty(session('mobile'))){
            //已登录
            echo json_encode(['status'=>1]);
            return;
        }

        //检测是否注册
        $opt = [
            CURLOPT_POST=>1,
            CURLOPT_POSTFIELDS=>['mobile'=>I('get.mobile')],
            CURLOPT_SSL_VERIFYPEER=>FALSE,
            CURLOPT_SSL_VERIFYHOST=>FALSE
        ];
        $userinfo = curl('https://wowoyoo.com/member_api/login_by_tel',$opt);
        if($userinfo['status']==0){
            //未注册
            $opt[CURLOPT_POSTFIELDS]['username'] = explode('|',I('get.username'))[0] ;
            $opt[CURLOPT_POSTFIELDS]['password'] = '000000';
            $register = curl('https://wowoyoo.com/member_api/register',$opt);
            if($register['status']==1){
                session('mobile',$register['data']['mobile']);
            }
            echo json_encode($register);
        }else{
            //已注册 自动登录
            session('mobile',$userinfo['info']['tel']);
            echo json_encode($userinfo);
        }

    }



    public function test(){
        $opt = [
            CURLOPT_POST=>1,
            CURLOPT_POSTFIELDS=>['mobile'=>'18006661209'],
            CURLOPT_SSL_VERIFYPEER=>FALSE,
            CURLOPT_SSL_VERIFYHOST=>FALSE
        ];
        $userinfo = curl('https://wowoyoo.com/member_api/login_by_tel',$opt);

        var_dump($userinfo);
    }















    // ------------------------------------推送------------------------------------
    //二次确认推送 客人下订单后，当酒店有房，无房或无房酒店变更时信息推送(续住也使用这个接口)
    public function reconfirm(){
        $this->_orderLogParam();
        //重复刷新 TODO

        $rid = M('order_reconfirm_log')->add(I('post.'));

        $res = M('order')
            ->where('order_no='.I('post.order_no').' and wowo_order_no='.I('post.wowo_order_no'))
            ->save([
                'order_status'=>I('post.order_status'),
                'total_money'=>I('post.total_money'),
            ]);

        //增加订单日志
        for($i=0;$i<count(I('post.order_log_type'));$i++){
            M('order_status_log')->add([
                'order_no'=>I('post.order_no'),
                'wowo_order_no'=>I('post.wowo_order_no'),
                'order_status'=>I('post.order_status'),
                'order_log_type'=>I('post.order_log_type')[$i],
                'order_log_remark'=>I('post.order_log_remark')[$i],
                'order_log_time'=>I('post.order_log_time')[$i],
            ]);
        }

        json_encode(['status'=>$res]);
    }

    //订单状态推送  此接口主要包含(订单取消申请，提前离店申请,到店无房取消，已入住，已完成，超过未支付)的审核状态推送.订单取消通过和提前离店通过都有退款金额值
    public function orderStatus(){
        $this->_orderLogParam();
        $sid = M('order_status_log')->add(I('post.'));

        $res = M('order')
            ->where('order_no='.I('post.order_no').' and wowo_order_no='.I('post.wowo_order_no'))
            ->save([
                'order_status'=>I('post.order_status'),
            ]);
        json_encode(['status'=>$res]);
    }

    //补差订单推送 客人申请变更，如果金额超过原订单金额，则需要补差价
    public function orderDifference(){
        $this->_orderLogParam();
        $did = M('order_difference_log')->add(I('post.'));

        $res = M('order')
            ->where('order_no='.I('post.order_no').' and wowo_order_no='.I('post.wowo_order_no'))
            ->save([
                'order_status'=>I('post.order_status'),
            ]);
        json_encode(['status'=>$res]);
    }

    private function _orderLogParam(){
        $order_log = I('post.order_log');
        for($i=0;$i<count($order_log);$i++){
            $_POST['order_log_type'][$i]   =$order_log[$i]['type'] ;
            $_POST['order_log_remark'][$i] = $order_log[$i]['remark'];
            $_POST['order_log_time'][$i]   = $order_log[$i]['time']['date'];
        }

        unset($_POST['order_log']);

    }

















}