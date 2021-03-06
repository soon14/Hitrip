<?php
/**
 * @author Fm453(方少)
 * @DACMS https://api.hiluker.com
 * @site https://www.hiluker.com
 * @url http://s.we7.cc/index.php?c=home&a=author&do=index&uid=662
 * @email fm453@lukegzs.com
 * @QQ 393213759
 * @wechat 393213759
*/

/*
 * @remark 会员中心
 */
defined('IN_IA') or exit('Access Denied');
global $_GPC;
global $_W;
global $_FM;
fm_load()->fm_func('share');//链接分享管理
fm_load()->fm_func('view'); 	//浏览量处理
fm_load()->fm_func('wechat');//微信定义管理

//加载风格模板及资源路径
$appstyle = empty($settings['appstyle']) ? FM_APPSTYLE : $settings['appstyle'];
$appsrc =MODULE_URL.'template/mobile/'.$appstyle.'453/';
$htmlsrc =MODULE_URL.'template/mobile/'.$appstyle;
$fm453resource = FM_RESOURCE;
//入口判断
$do= $_GPC['do'];
$ac=$_GPC['ac'];
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'index';
if($operation=='login') {
	checkauth();
}
//开始操作管理
$shopname=$settings['brands']['shopname'];
$shopname = !empty($shopname) ? $shopname :FM_NAME_CN;
$pagename = '会员中心';

$uniacid=$_W['uniacid'];
$plattype=$settings['plattype'];
$platids = fmFunc_getPlatids();
$platid=$_W['uniacid'];
$oauthid=$platids['oauthid'];
$fendianids=$platids['fendianids'];
$supplydianids=$platids['supplydianids'];
$blackids=$platids['blackids'];

//平台模式处理
require_once FM_PUBLIC.'plat.php';
$supplydians=explode(',',$supplydianids);//字符串转数组
$supplydians=array_filter($supplydians);//数组去空

//按平台模式前置筛选条件
$condition=' WHERE ';
$params=array();
require_once FM_PUBLIC.'forsearch.php';

$share_user=$_GPC['share_user'];
$shareid = intval($_GPC['shareid']);
$lastid = intval($_GPC['lastid']);
$currentid = intval($_W['member']['uid']);
$fromplatid = intval($_GPC['fromplatid']);
$from_user = $_W['openid'];
$url_condition="";
$direct_url = fm_murl($do,$ac,$operation,array());

//自定义微信分享内容
$_share = array();
$_share['title'] = $pagename.'|'.$_W['account']['name'];
$_share['link'] = fm_murl($do,$ac,$operation,array('isshare'=>1));
$_share['link'] = $_share['link'].$url_condition;
$_share['imgUrl'] = tomedia($settings['brands']['logo']);
$_share['desc'] = $_share['desc'] = $settings['brands']['share_des'];

$resultmember = fmMod_member_query($currentid);
$FM_member=$resultmember['data'];
if(!$FM_member['nickname']) {
	if($FM_member['realname']) {
		$FM_member['nickname']=$FM_member['realname'];
	}else{
		$FM_member['nickname']=$FM_member['realname']="未填写";
	}
}
$FM_vipcard_open=FALSE;
$FM_vipcard_get = FALSE;
//判断公众号是否开启会员卡功能
		$card_setting = pdo_fetch("SELECT * FROM ".tablename('mc_card')." WHERE uniacid = '{$_W['uniacid']}'");
		$card_status =  $card_setting['status'];
		if($card_status) {
			$FM_vipcard_open=TRUE;
		}
		//查看会员是否开启会员卡功能
		$membercard_setting  = pdo_get('mc_card_members', array('uniacid' => $_W['uniacid'], 'uid' => $_W['member']['uid']));
		$membercard_status = $membercard_setting['status'];
		$pricefield = (!empty($membercard_status) && $card_status == 1) ? "mprice" : "cprice";
		if (!empty($card_status) && !empty($membercard_status)) {
			$FM_vipcard_get=TRUE;
		} else {
			$FM_vipcard_get= false;
		}

//会员自定义设置
$mine_settings=$_FM['member']['settings'];

$mine_settings['onoffs']['notify']= !isset($mine_settings['onoffs']['notify']) ? 1 : $mine_settings['onoffs']['notify'];
$mine_settings['onoffs']['noDisturb'] = !isset($mine_settings['onoffs']['noDisturb']) ? 2 : $mine_settings['onoffs']['noDisturb'];
$mine_settings['onoffs']['tip_qiandao'] = !isset($mine_settings['onoffs']['tip_qiandao']) ? 1 : $mine_settings['onoffs']['tip_qiandao'];

//其他数据
$_FM['settings']['brands']['officialweb'] = !isset($_FM['settings']['brands']['officialweb']) ? $_FM['settings']['brands']['officialweb'] : fm_murl('vsite','index','index',array());

if($operation=="index"){
	include $this->template($appstyle.$do.'/453');
}elseif($operation=="ajax"){
	/*
	*	会员个性设置数据保存
	*/
	$setfor = $_POST['setfor'];
	$key = $_POST['key'];
	$value = $_POST['value'];

	$data=array();
	$data['status'] = 127;
	$data['title'] = $key;
	$data['value'] = $value;

	$result = array();
	$result = fmMod_member_settings_save($currentid,$_W['openid'],$data,$setfor, $_W['uniacid']);
	exit(json_encode($result));
}
