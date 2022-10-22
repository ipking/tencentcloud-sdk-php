<?php
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Essbasic\V20210526\EssbasicClient;
use TencentCloud\Essbasic\V20210526\Models\ChannelDescribeEmployeesRequest;

include '.config.php';


try {
	// 实例化一个认证对象，入参需要传入腾讯云账户secretId，secretKey,此处还需注意密钥对的保密
	// 密钥可前往https://console.cloud.tencent.com/cam/capi网站进行获取
	$cred = new Credential($options['secret_id'], $options['secret_key']);
	// 实例化一个http选项，可选的，没有特殊需求可以跳过
	$httpProfile = new HttpProfile();
	$httpProfile->setEndpoint("essbasic.tencentcloudapi.com");
	
	// 实例化一个client选项，可选的，没有特殊需求可以跳过
	$clientProfile = new ClientProfile();
	$clientProfile->setHttpProfile($httpProfile);
	// 实例化要请求产品的client对象,clientProfile是可选的
	$client = new EssbasicClient($cred, "", $clientProfile);
	
	// 实例化一个请求对象,每个接口都会对应一个request对象
	$req = new ChannelDescribeEmployeesRequest();
	
	$params = array(
		"Limit" => 1
	);
	$req->fromJsonString(json_encode($params));
	
	// 返回的resp是一个UploadFilesResponse的实例，与请求对象对应
	$resp = $client->ChannelDescribeEmployees($req);
	
	// 输出json格式的字符串回包
	print_r($resp->toJsonString());
}
catch(TencentCloudSDKException $e) {
	echo $e;
}