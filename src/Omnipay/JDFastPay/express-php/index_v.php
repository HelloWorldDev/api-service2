<?php
/* *
 * 功能：网银在线快捷支付签约接口接入页面
 * 版本：0.1
 * 日期：2014-04-02
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
	<title>网银在线签约接入</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
*{
	margin:0;
	padding:0;
}
ul,ol{
	list-style:none;
}
.title{
    color: #ADADAD;
    font-size: 14px;
    font-weight: bold;
    padding: 8px 16px 5px 10px;
}
.hidden{
	display:none;
}

.new-btn-login-sp{
	border:1px solid #D74C00;
	padding:1px;
	display:inline-block;
}

.new-btn-login{
    background-color:#AB4400;
    border: medium none;
}
.new-btn-login{
    background-position: 0 -198px;
    width: 82px;
	color: #FFFFFF;
    font-weight: bold;
    height: 28px;
    line-height: 28px;
    padding: 0 10px 3px;
}
.new-btn-login:hover{
	background-position: 0 -167px;
	width: 82px;
	color: #FFFFFF;
    font-weight: bold;
    height: 28px;
    line-height: 28px;
    padding: 0 10px 3px;
}
.bank-list{
	overflow:hidden;
	margin-top:5px;
}
.bank-list li{
	float:left;
	width:153px;
	margin-bottom:5px;
}

#main{
	width:750px;
	margin:0 auto;
	font-size:14px;
	font-family:'宋体';
}
#logo{
	background-color: transparent;
    border: medium none;
	background-position:0 0;
	width:166px;
	height:35px;
    float:left;
}
.red-star{
	color:#f00;
	width:10px;
	display:inline-block;
}
.null-star{
	color:#fff;
}
.content{
	margin-top:5px;
}

.content dt{
	width:160px;
	display:inline-block;
	text-align:right;
	float:left;

}
.content dd{
	margin-left:100px;
	margin-bottom:5px;
}
#foot{
	margin-top:10px;
}
.foot-ul li {
	text-align:center;
}
.note-help {
    color: #999999;
    font-size: 12px;
    line-height: 130%;
    padding-left: 3px;
}

.cashier-nav {
    font-size: 14px;
    margin: 15px 0 10px;
    text-align: left;
    height:30px;
    border-bottom:solid 2px #CFD2D7;
}
.cashier-nav ol li {
    float: left;
}
.cashier-nav li.current {
    color: #AB4400;
    font-weight: bold;
}
.cashier-nav li.last {
    clear:right;
}
.alipay_link {
    text-align:right;
}
.alipay_link a:link{
    text-decoration:none;
    color:#8D8D8D;
}
.alipay_link a:visited{
    text-decoration:none;
    color:#8D8D8D;
}
</style>
</head>
<body text=#000000 bgColor=#ffffff leftMargin=0 topMargin=4>
	<div id="main">
		<div id="head">
            <dl class="alipay_link">
                <a target="_blank" href="http://www.chinabank.com.cn/"><span>网银在线首页</span></a>|
                <a target="_blank" href="http://www.chinabank.com.cn/gateway/help.jsp"><span>帮助中心</span></a>
            </dl>
            <span class="title">网银在线签约</span>
		</div>
        <div class="cashier-nav">
            <ol>
				<li class="current">1、确认信息 →</li>
				<li>2、点击确认 →</li>
				<li class="last">3、显示返回结果</li>
            </ol>
        </div>
        <form name="payment" action="v.php" method="post" target="_blank">
            <div id="body" style="clear:left">
                <dl class="content">
                    <dt>银行编码：</dt>
                    <dd>
                        <span class="null-star">*</span>
                        <input size="30" name="card_bank" value="ABC"/>
                        <span>必填</span>
                    </dd>
                    <dt>卡类型：</dt>
                    <dd>
                        <span class="null-star">*</span>
                        <input size="30" name="card_type" value="C"/>
                        <span>必填 （信用卡：C借记卡：D）</span>
                    </dd>
                   	<dt>卡号：</dt>
                    <dd>
                        <span class="null-star">*</span>
                        <input size="30" name="card_no" value="6228360065319445"/>
                        <span>必填</span>
                    </dd>
                    <dt>信用卡有效期：</dt>
                    <dd>
                        <span class="null-star">*</span>
                        <input size="30" name="card_exp" value="1604"/>
                    </dd>
                    <dt>信用卡校验码：</dt>
                    <dd>
                        <span class="null-star">*</span>
                        <input size="30" name="card_cvv2" value="488"/>
                    </dd>
                    <dt>持卡人姓名：</dt>
                    <dd>
                        <span class="null-star">*</span>
                        <input size="30" name="card_name" value="车文涛"/>
                        <span>必填</span>
                    </dd>
                    <dt>持卡人证件类型：</dt>
                    <dd>
                        <span class="null-star">*</span>
                        <input size="30" name="card_idtype" value="I"/>
                        <span>必填(I:身份证)</span>
                    </dd>
                    <dt>持卡人证件号码：</dt>
                    <dd>
                        <span class="null-star">*</span>
                        <input size="30" name="card_idno" value="610427198801053914"/>
                        <span>必填</span>
                    </dd>
                     <dt>持卡人电话号码：</dt>
                    <dd>
                        <span class="null-star">*</span>
                        <input size="30" name="card_phone" value="18801056161"/>
                        <span>必填</span>
                    </dd>
                    <dt>交易类型：</dt>
                    <dd>
                        <span class="null-star">*</span>
                        <input size="30" name="trade_type" value="V"/>
                        <span>必填 （V：签约）</span>
                    </dd>
                    <dt>交易ID：</dt>
                    <dd>
                        <span class="null-star">*</span>
                       <input size="30" name="trade_id" value="12345670123"/>
                        <span>必填</span>
                    </dd>
                     <dt>金额：</dt>
                    <dd>
                        <span class="null-star">*</span>
                        <input size="30" name="trade_amount" value="1"/>
                        <span>必填</span>
                    </dd>
                    <dt>货币类型：</dt>
                    <dd>
                        <span class="null-star">*</span>
                        <input size="30" name="trade_currency" value="CNY"/>
                        <span>必填</span>
                    </dd>
					<dt></dt>
                    <dd>
                        <span class="new-btn-login-sp">
                            <button class="new-btn-login" type="submit" style="text-align:center;">签约</button>
                        </span>
                    </dd>
                </dl>
            </div>
		</form>
        <div id="foot">
			<ul class="foot-ul">
				<li><font class="note-help">表中的数据非真实数据，请商户填写自己的测试数据 </font></li>
				<li>
					网银在线版权所有
				</li>
			</ul>
		</div>
	</div>
</body>
</html>