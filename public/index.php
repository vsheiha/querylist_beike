<?php
use QL\QueryList;

//ini_set('display_errors', 'on');

require_once '../vendor/autoload.php';
require_once 'common.php';

try {
    $DB = new PDO("mysql:host=192.168.1.200;port=3306;dbname=yafBase", 'root', 'root');
} catch (PDOException $e) {
    die ("Error!: " . $e->getMessage() . "<br/>");
}

$hxinsert = $DB->prepare("insert into z_bkxqhx (hxid,title,img,price,info,page,addtime,xqid,detail)values(?,?,?,?,?,?,?,?,?)");

$hxdbdata = $DB->query("select huxing_href from z_bkxq where huxing_href LIKE '%/huxing/'");
$row = $hxdbdata->fetchAll();

//page规则
$pagerules = ['page' =>array('.house-lst-page-box', 'page-data')];

//户型列表规则
$listrules = array(
    'url' => array('.frameListItem .fr>a', 'href'),
    'title' => array('.frameListItem .fr>a', 'text'),
    'price' => array('.frameListItem .frameItemTotalPrice', 'text')
);

//户型规则
$hxrules = [
    'info'=>['.frameDetailInfo','html'],
    'detail'=>['.frameExplainDetail','html'],
    'img'=>[ '.frameDetailOverview img','src']];

//info 规则
$inforules = [
    'label'=>['.frameInfoItemLabel','text'],
    'data'=>['.frameInfoItemDetail','text']
];

//分间规则
$detailrules =[
    'label'=>['.frameItemLabel','text'],
    'data'=>['.frameExplainItem','text']
];


foreach ($row as $k => $v) {
    //小区id
    $xqid = explode('/', $v['huxing_href']);
    $list['xqid'] = $xqid[count($xqid) - 3];

    $pageurl = $v['huxing_href'];
    $xqlist = QueryList::getInstance()->get($v['huxing_href']);
    $data = $xqlist->rules($pagerules)->query()->getData();
    $pagedata = $data->all();

    if (!empty($pagedata)) {
        $totalPage =  json_decode($pagedata[0]['page'],true)['totalPage'];
    }else{
        $totalPage = 0;
    }
    $hxcount = 0;
    $hxfalsecount = 0;
    for ($page = 1; $page <= $totalPage; $page++) {



        if ($page !== 1) {
            $pageurl = $v['huxing_href'] . 'pg' . $page . '/';
            $xqlist = QueryList::getInstance()->get($pageurl);
        }
        //户型page
        $list['page'] = $pageurl;

        $data = $xqlist->rules($listrules)->query()->getData();
        $xqdataarr = $data->all();
        if (empty($xqdataarr)) {
            echo $pageurl . '抓取为空';
            continue;
        }
        foreach ($xqdataarr as $value) {
            //户型id
            $hxid = explode('/',$value['url']);
            $list['hxid'] = explode('.',$hxid[count($hxid)-1])[0];

            //户型id存在continue
            if (!empty($list['hxid'])){
                $hxid = $list['hxid'];
                $q = $DB->query("select id from z_bkxqhx WHERE hxid=$hxid");
                $rows = count($q->fetchall());
                if ($rows == 1) {
                    $hxfalsecount++;
                    echo $hxid,'已存在','<br/>';
                    continue;
                }
            }

            //价格
            if (!empty($value['price']) && $value['price'] !== '暂无价格'){
                $list['price'] = !empty($value['price']) ? mb_substr($value['price'],0,-1): 0;
            }else{
                $list['price'] = 0;
            }
            //标题
            $list['title'] = !empty($value['title'])?$value['title']:'';

            $hxlist = QueryList::getInstance()->get($value['url']);
            $hxdata = $hxlist->rules($hxrules)->query()->getData();
            $hxdataarr = $hxdata->all();

            //户型信息
            if (!empty($hxdataarr[0]['info'])) {
                $infodata = QueryList::html($hxdataarr[0]['info'])->rules($inforules)->query()->getData();
                $list['info'] = json_encode($infodata->all());
            }else{
                $list['info'] = '';
            }


            //户型分间
            if (!empty($hxdataarr[0]['detail'])) {
                $detaildata = QueryList::html($hxdataarr[0]['detail'])->rules($detailrules)->query()->getData();
                $detail= $detaildata->all();
                $list['detail']=[];
                if (!empty($detail)) {
                    foreach ($detail as $detailk=>$detailv) {
                        $list['detail'][$detailk]['label'] = $detailv['label'];
                        $list['detail'][$detailk]['data'] = str_replace($detailv['label'],'',$detailv['data']);
                    }
                }
                $list['detail'] = json_encode($list['detail']);
            }else{
                $list['detail'] = '';
            }

            //img
            if (!empty($hxdataarr[0]['img'])) {
                $hximgurl = $hxdataarr[0]['img'];
                $hximgname = explode('/',$hximgurl);
                $hximgname =$hximgname[count($hximgname) - 1];

                $filename = __DIR__.'/img/' . $hximgname;

                $hximgdata = GET_curl($hximgurl);
                $img = file_put_contents($filename, $hximgdata);

                $list['img'] = $img ? $hximgname : '';
                unset($hximgdata);
                unset($img);
            }else{
                $list['img'] = '';
            }

            $list['time'] = time();


           // hxid,title,img,price,info,page,addtime,xqid
            $hxinsert->bindParam(1,$list['hxid']);
            $hxinsert->bindParam(2,$list['title']);
            $hxinsert->bindParam(3,$list['img']);
            $hxinsert->bindParam(4,$list['price']);
            $hxinsert->bindParam(5,$list['info']);
            $hxinsert->bindParam(6,$list['page']);
            $hxinsert->bindParam(7,$list['time']);
            $hxinsert->bindParam(8,$list['xqid']);
            $hxinsert->bindParam(9,$list['detail']);
            $dbinfo = $hxinsert->execute();

            if ($dbinfo) {
                $hxcount++;
                echo '小区'.$list['xqid'].'->户型'.$list['hxid'].'插入成功<br/>';
            }else{
                var_dump($list);
            }

        }

    }
    echo '小区' . $list['xqid'] . '成功插入' . $hxcount . '条；失败'.$hxfalsecount.'条<br/>';
}

$DB = null;
