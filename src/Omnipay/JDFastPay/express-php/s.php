<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>网银在线快捷签约接入</title>
</head>
<?php
	include ("des.php");
	include("service.php");
	require_once("xml.php");
	require_once("config.php");
	require_once("md5.php");
	//收集index_v.php请求参数
	$card_bank = $_POST['card_bank'];
	$card_type = $_POST['card_type'];
	$card_no = $_POST['card_no'];
	$card_exp = $_POST['card_exp'];
	$card_cvv2 = $_POST['card_cvv2'];
	$card_name = $_POST['card_name'];
	$card_idtype = $_POST['card_idtype'];
	$card_idno = $_POST['card_idno'];
	$card_phone = $_POST['card_phone'];
	$trade_type = $_POST['trade_type'];
	$trade_id = $_POST['trade_id'];
	$trade_amount = $_POST['trade_amount'];
	$trade_currency = $_POST['trade_currency'];
	$trade_date = $_POST['trade_date'];
	$trade_time = $_POST['trade_time'];
	$trade_notice = $_POST['trade_notice'];
	$trade_note = $_POST['trade_note'];
	$trade_code = $_POST['trade_code'];

	$data_xml = s_data_xml_create($card_bank,$card_type,$card_no,
									$card_exp,$card_cvv2,$card_name,
									$card_idtype,$card_idno,$card_phone,
									$trade_type,$trade_id,$trade_amount,
									$trade_currency,$trade_date,$trade_time,
									$trade_notice,$trade_note,$trade_code);
	$service = new MotoPayService();
	//发起交易至快捷支付
	$resp = $service->trade($data_xml);
	//解析响应结果
	$service->operate($resp);
?>