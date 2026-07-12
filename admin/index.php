<?php
include("../includes/common.php");
$title='支付管理中心';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<?php
if($conf['admin_pwd']==='123456'){
	$msg[]='<li class="list-group-item list-group-item-danger"><span class="btn-sm btn-danger"><i class="fa fa-info-circle"></i> 提示</span>&nbsp;请及时修改网站默认管理员密码！</li>';
}elseif(strlen($conf['admin_pwd'])<6 || is_numeric($conf['admin_pwd']) && strlen($conf['admin_pwd'])<=10 || $conf['admin_pwd']===$conf['kfqq'] || $conf['admin_user']===$conf['admin_pwd']){
	$msg[]='<li class="list-group-item list-group-item-danger"><span class="btn-sm btn-danger"><i class="fa fa-info-circle"></i> 提示</span>&nbsp;网站管理员密码过于简单，请及时修改密码！</li>';
}
?>
<div class="container" style="padding-top:70px;">
  <div class="col-xs-12 center-block dashboard-page" style="float: none;">
    <div id="browser-notice"></div>

    <?php if(!empty($msg)){?>
    <div class="dashboard-alerts">
      <ul class="list-group"><?php foreach($msg as $x){echo $x;}?></ul>
    </div>
    <?php }?>

    <div class="dashboard-heading">
      <div>
        <h1>运营概览</h1>
        <p>关键数据每小时缓存更新，刷新可立即获取最新统计。</p>
      </div>
      <button type="button" class="btn btn-default dashboard-refresh" onclick="getData(true)" title="刷新统计">
        <i class="fa fa-refresh"></i><span>刷新数据</span>
      </button>
    </div>

    <div class="dashboard-metrics" aria-label="关键运营指标">
      <a class="dashboard-metric" href="./order.php">
        <span class="dashboard-metric-icon metric-teal"><i class="fa fa-file-text-o"></i></span>
        <span class="dashboard-metric-content"><small>订单总数</small><strong id="count1">--</strong><em>查看全部订单</em></span>
      </a>
      <a class="dashboard-metric" href="./ulist.php">
        <span class="dashboard-metric-icon metric-blue"><i class="fa fa-users"></i></span>
        <span class="dashboard-metric-content"><small>商户数量</small><strong id="count2">--</strong><em>查看商户列表</em></span>
      </a>
      <div class="dashboard-metric">
        <span class="dashboard-metric-icon metric-green"><i class="fa fa-database"></i></span>
        <span class="dashboard-metric-content"><small>商户总余额</small><strong><span id="usermoney">--</span><b>元</b></strong><em>缓存统计</em></span>
      </div>
      <div class="dashboard-metric">
        <span class="dashboard-metric-icon metric-orange"><i class="fa fa-exchange"></i></span>
        <span class="dashboard-metric-content"><small>结算总额</small><strong><span id="settlemoney">--</span><b>元</b></strong><em>缓存统计</em></span>
      </div>
      <div class="dashboard-metric">
        <span class="dashboard-metric-icon metric-red"><i class="fa fa-line-chart"></i></span>
        <span class="dashboard-metric-content"><small>今日成功率</small><strong><span id="success_rate">--</span><b>%</b></strong><em>实时订单表现</em></span>
      </div>
    </div>

    <div class="dashboard-layout">
      <section class="dashboard-main">
        <div class="panel dashboard-panel">
          <div class="panel-heading">
            <div><h3 class="panel-title">支付方式收入</h3><small>按支付方式汇总最近交易</small></div>
            <a href="javascript:getData(true)" class="admin-icon-button" title="刷新"><i class="fa fa-refresh"></i></a>
          </div>
          <div class="table-responsive">
            <table class="table table-hover"><thead><tr id="paytype_head"><th>日期</th></tr></thead><tbody id="paytype_list"></tbody></table>
          </div>
        </div>

        <div class="panel dashboard-panel">
          <div class="panel-heading">
            <div><h3 class="panel-title">支付通道收入</h3><small>用于比较各通道实际贡献</small></div>
            <a href="javascript:getData(true)" class="admin-icon-button" title="刷新"><i class="fa fa-refresh"></i></a>
          </div>
          <div class="table-responsive">
            <table class="table table-hover"><thead><tr id="channel_head"><th>日期</th></tr></thead><tbody id="channel_list"></tbody></table>
          </div>
        </div>

        <div class="panel dashboard-panel">
          <div class="panel-heading">
            <div><h3 class="panel-title">手续费利润</h3><small>已扣除通道成本的支付方式利润</small></div>
            <a href="javascript:getData(true)" class="admin-icon-button" title="刷新"><i class="fa fa-refresh"></i></a>
          </div>
          <div class="table-responsive">
            <table class="table table-hover"><thead><tr id="profit_paytype_head"><th>日期</th></tr></thead><tbody id="profit_paytype_list"></tbody></table>
          </div>
        </div>
      </section>

      <aside class="dashboard-aside">
        <div class="panel dashboard-quick-panel">
          <div class="panel-heading"><h3 class="panel-title">快捷操作</h3></div>
          <div class="dashboard-quick-actions">
            <a href="./order.php"><i class="fa fa-search"></i><span>查询订单</span></a>
            <a href="./transfer_add.php"><i class="fa fa-plus"></i><span>新增付款</span></a>
            <a href="./ulist.php"><i class="fa fa-user-plus"></i><span>商户管理</span></a>
            <a href="./pay_channel.php"><i class="fa fa-random"></i><span>支付通道</span></a>
            <a href="./risk.php"><i class="fa fa-shield"></i><span>风控记录</span></a>
            <a href="./log.php"><i class="fa fa-history"></i><span>登录日志</span></a>
          </div>
        </div>

        <div class="panel dashboard-status-panel">
          <div class="panel-heading"><h3 class="panel-title">系统状态</h3></div>
          <ul class="dashboard-status-list">
            <li><span><i class="fa fa-circle status-online"></i>管理账号</span><strong><?php echo $conf['admin_user']?></strong></li>
            <li><span><i class="fa fa-clock-o"></i>服务器时间</span><strong><?=$date?></strong></li>
            <li><span><i class="fa fa-code-fork"></i>系统版本</span><strong><?php echo VERSION?></strong></li>
          </ul>
          <a class="dashboard-account-link" href="./set.php?mod=account"><i class="fa fa-key"></i> 修改账号与密码</a>
        </div>
      </aside>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
	getData();
});
function getData(getnew){
	getnew = getnew || false;
	$.ajax({
		type : "GET",
		url : "ajax.php?act=getcount"+(getnew?'&getnew=1':''),
		dataType : 'json',
		async: true,
		beforeSend: function() {
			$('.dashboard-refresh .fa, .dashboard-panel .fa-refresh').addClass('fa-spin');
		},
		success : function(data) {
			$('#count1').html(data.count1);
			$('#count2').html(data.count2);
			$('#usermoney').html(data.usermoney);
			$('#settlemoney').html(data.settlemoney);
			$('#success_rate').html(data.success_rate);

			$("#paytype_head").html('<th>日期</th>');
			$("#paytype_list").empty();
			var paytype=new Array();
			$.each(data.paytype, function(k, v){
				paytype.push(k);
				$("#paytype_head").append('<th>'+v+'</th>');
			});
			$("#paytype_head").append('<th>总计</th>');
			var order = '';
			$.each(paytype, function(k, v){
				if(typeof data.order_today.paytype[v] != "undefined")order+='<td>'+data.order_today.paytype[v]+'</td>';
				else order+='<td>0</td>';
			});
			$("#paytype_list").append('<tr><td>今日</td>'+order+'<td>'+data.order_today.all+'</td></tr>');
			$.each(data.order, function(k, v){
				var order = '';
				$.each(paytype, function(key, value){
					if(typeof v.paytype[value] != "undefined")order+='<td>'+v.paytype[value]+'</td>';
					else order+='<td>0</td>';
				});
				$("#paytype_list").append('<tr><td>'+k+'</td>'+order+'<td>'+v.all+'</td></tr>');
			});

			$("#channel_head").html('<th>日期</th>');
			$("#channel_list").empty();
			var channel=new Array();
			$.each(data.channel, function(k, v){
				channel.push(k);
				$("#channel_head").append('<th>'+v+'</th>');
			});
			$("#channel_head").append('<th>总计</th>');
			var order = '';
			$.each(channel, function(k, v){
				if(typeof data.order_today.channel[v] != "undefined")order+='<td>'+data.order_today.channel[v]+'</td>';
				else order+='<td>0</td>';
			});
			$("#channel_list").append('<tr><td>今日</td>'+order+'<td>'+data.order_today.all+'</td></tr>');
			$.each(data.order, function(k, v){
				var order = '';
				$.each(channel, function(key, value){
					if(typeof v.channel[value] != "undefined")order+='<td>'+v.channel[value]+'</td>';
					else order+='<td>0</td>';
				});
				$("#channel_list").append('<tr><td>'+k+'</td>'+order+'<td>'+v.all+'</td></tr>');
			});

			$("#profit_paytype_head").html('<th>日期</th>');
			$("#profit_paytype_list").empty();
			var paytype=new Array();
			$.each(data.paytype, function(k, v){
				paytype.push(k);
				$("#profit_paytype_head").append('<th>'+v+'</th>');
			});
			$("#profit_paytype_head").append('<th>总计</th>');
			var order = '';
			$.each(paytype, function(k, v){
				if(typeof data.order_today.profit_paytype[v] != "undefined")order+='<td>'+data.order_today.profit_paytype[v]+'</td>';
				else order+='<td>0</td>';
			});
			$("#profit_paytype_list").append('<tr><td>今日</td>'+order+'<td>'+data.order_today.profit_all+'</td></tr>');
			$.each(data.order, function(k, v){
				var order = '';
				$.each(paytype, function(key, value){
					if(typeof v.profit_paytype[value] != "undefined")order+='<td>'+v.profit_paytype[value]+'</td>';
					else order+='<td>0</td>';
				});
				$("#profit_paytype_list").append('<tr><td>'+k+'</td>'+order+'<td>'+v.profit_all+'</td></tr>');
			});
		},
		error: function() {
			if (window.layer) {
				layer.msg('统计数据加载失败，请稍后重试', {icon: 2});
			} else {
				window.alert('统计数据加载失败，请稍后重试');
			}
		},
		complete: function() {
			$('.dashboard-refresh .fa, .dashboard-panel .fa-refresh').removeClass('fa-spin');
		}
	});
}
</script>
<script>
function speedModeNotice(){
	var ua = window.navigator.userAgent;
	if(ua.indexOf('Windows NT')>-1 && ua.indexOf('Trident/')>-1){
		var html = "<div class=\"panel panel-default\"><div class=\"panel-body\">当前浏览器是兼容模式，为确保后台功能正常使用，请切换到<b style='color:#51b72f'>极速模式</b>！<br>操作方法：点击浏览器地址栏右侧的IE符号<b style='color:#51b72f;'><i class='fa fa-internet-explorer fa-fw'></i></b>→选择“<b style='color:#51b72f;'><i class='fa fa-flash fa-fw'></i></b><b style='color:#51b72f;'>极速模式</b>”</div></div>";
		$("#browser-notice").html(html)
	}
}
speedModeNotice();
</script>
