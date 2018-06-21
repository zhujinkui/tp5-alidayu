<?php
// 短信发送类
// +----------------------------------------------------------------------
// | PHP version 5.4+
// +----------------------------------------------------------------------
// | Copyright (c) 2012-2014 http://www.17php.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhujinkui <developer@zhujinkui.com> 群：167030116
// +----------------------------------------------------------------------

namespace think;
use think\TopClient;
use think\AlibabaAliqinFcSmsNumSendRequest;
use think\Config;

class Alidayu
{
	/**
	 * @var array 配置信息
	 */
	protected $configs = [
		// app_key
		'app_key'    => '换成自己的appkey',
		// secret_key
		'secret_key' => '换成自己的secretKey',
		// sign_name
		'sign_name'  => '换成自己的签名'
	];

	/**
	 * 构造函数
	 * @access protected
	 */
	public function __construct()
	{
	    //判断是否有设置配置项.此配置项为数组，做一个兼容
	    if (Config::has('alidayu')) {
	    	$this->configs = array_merge($this->configs, Config::get('alidayu'));
	    } else {
	    	$this->configs;
	    }
	}

	public function sendSms($mobile = '', $sms_template = '', $sms_param = '')
	{

		if (empty($mobile) || empty($sms_template) || !is_array($sms_param)) {
			return false;
		}

		if (!defined("TOP_SDK_WORK_DIR")) {
			define("TOP_SDK_WORK_DIR", "/tmp/");
		}

		date_default_timezone_set('Asia/Shanghai');
		$sms_param = json_encode($sms_param, JSON_UNESCAPED_UNICODE);
		$c = new TopClient;
		$c->appkey = $this->configs['app_key'];
		$c->secretKey = $this->configs['secret_key'];
		$req = new AlibabaAliqinFcSmsNumSendRequest;
		$req->setExtend("123456");
		$req->setSmsType("normal");
		$req->setSmsFreeSignName($this->configs['sign_name']);
		$req->setSmsParam($sms_param);
		$req->setRecNum($mobile);
		$req->setSmsTemplateCode($sms_template);

		return $resp = $c->execute($req);
	}
}