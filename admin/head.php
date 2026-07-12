<?php
@header('Content-Type: text/html; charset=UTF-8');

$admin_cdnpublic = 0;
if($admin_cdnpublic==1){
	$cdnpublic = '//lib.baomitu.com/';
}elseif($admin_cdnpublic==2){
	$cdnpublic = 'https://s4.zstatic.net/ajax/libs/';
}elseif($admin_cdnpublic==4){
	$cdnpublic = 'https://cdnjs.snrat.com/ajax/libs/';
}else{
	$cdnpublic = '/assets/vendor/';
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="renderer" content="webkit">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title ?></title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="../assets/css/bootstrap-table.css?v=1" rel="stylesheet"/>
  <link href="<?php echo $cdnpublic?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link href="../assets/css/admin-ui.css?v=1" rel="stylesheet"/>
  <script src="<?php echo $cdnpublic?>modernizr/2.8.3/modernizr.min.js"></script>
  <script src="<?php echo $cdnpublic?>jquery/3.4.1/jquery.min.js"></script>
  <script src="<?php echo $cdnpublic?>twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <!--[if lt IE 9]>
    <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="admin-body<?php echo $islogin==1?' admin-has-shell':''?>">
<?php if($islogin==1){?>
  <div class="admin-overlay" data-sidebar-close></div>
  <aside class="admin-sidebar" id="admin-sidebar" aria-label="后台主导航">
    <a class="admin-brand" href="./">
      <span class="admin-brand-mark"><i class="fa fa-credit-card"></i></span>
      <span><strong>支付管理中心</strong><small>EPAY ADMIN</small></span>
    </a>
    <nav class="admin-menu">
      <a class="admin-menu-item <?php echo checkIfActive('index,')?>" href="./"><i class="fa fa-dashboard"></i><span>工作台</span></a>

      <div class="admin-menu-group <?php echo checkIfActive('order,export,ps_receiver,ps_order,buyerstat')?>">
        <button type="button" class="admin-menu-toggle" aria-expanded="false"><i class="fa fa-list-alt"></i><span>订单与分账</span><i class="fa fa-angle-down admin-menu-arrow"></i></button>
        <div class="admin-submenu">
          <a href="./order.php">订单管理</a><a href="./export.php">导出订单</a><a href="./buyerstat.php">支付用户统计</a><a href="./ps_receiver.php">分账规则</a><a href="./ps_order.php">分账记录</a>
        </div>
      </div>

      <div class="admin-menu-group <?php echo checkIfActive('settle,settle_batch,slist,transfer,transfer_add,transfer_export,transfer_red,transfer_batch,transfer_stat')?>">
        <button type="button" class="admin-menu-toggle" aria-expanded="false"><i class="fa fa-exchange"></i><span>结算与付款</span><i class="fa fa-angle-down admin-menu-arrow"></i></button>
        <div class="admin-submenu">
          <a href="./slist.php">结算管理</a><a href="./settle.php">批量结算</a><a href="./transfer.php">付款记录</a><a href="./transfer_add.php">新增付款</a><a href="./transfer_red.php">创建红包</a><a href="./transfer_stat.php">付款统计</a><a href="./transfer_export.php">导出付款记录</a>
          <?php if(class_exists('\\lib\\AlipaySATF\\AlipaySATF')){?><a href="./satf_transfer.php">安全发转账记录</a><?php }?>
        </div>
      </div>

      <div class="admin-menu-group <?php echo checkIfActive('ulist,glist,gedit,group,record,record_export,uset,domain,ustat,invitecode,uexport')?>">
        <button type="button" class="admin-menu-toggle" aria-expanded="false"><i class="fa fa-users"></i><span>商户管理</span><i class="fa fa-angle-down admin-menu-arrow"></i></button>
        <div class="admin-submenu">
          <a href="./ulist.php">用户列表</a><a href="./glist.php">用户组设置</a><a href="./group.php">用户组购买</a><a href="./record.php">资金明细</a><a href="./ustat.php">支付统计</a>
          <?php if($conf['pay_domain_forbid']==1 || $conf['pay_domain_open']==1){?><a href="./domain.php">授权域名</a><?php }?>
          <?php if($conf['reg_open']==2){?><a href="./invitecode.php">邀请码管理</a><?php }?>
        </div>
      </div>

      <div class="admin-menu-group <?php echo checkIfActive('pay_channel,pay_roll,pay_type,pay_plugin,pay_weixin,pay_wework,plugin_page,loader_helper,applyments_channel,applyments_merchant,applyments_form')?>">
        <button type="button" class="admin-menu-toggle" aria-expanded="false"><i class="fa fa-credit-card"></i><span>支付通道</span><i class="fa fa-angle-down admin-menu-arrow"></i></button>
        <div class="admin-submenu">
          <a href="./pay_channel.php">支付通道</a><a href="./pay_type.php">支付方式</a><a href="./pay_plugin.php">支付插件</a><a href="./pay_roll.php">通道轮询</a><a href="./pay_weixin.php">公众号小程序</a><a href="./pay_wework.php">企业微信</a>
          <?php if(class_exists('\\lib\\Applyments\\CommUtil')){?><a href="./applyments_channel.php">进件渠道管理</a><a href="./applyments_merchant.php">进件商户管理</a><?php }?>
        </div>
      </div>

      <div class="admin-menu-group <?php echo checkIfActive('risk,blacklist,complain,complain_info')?>">
        <button type="button" class="admin-menu-toggle" aria-expanded="false"><i class="fa fa-shield"></i><span>风控中心</span><i class="fa fa-angle-down admin-menu-arrow"></i></button>
        <div class="admin-submenu">
          <a href="./risk.php">风控记录</a><a href="./blacklist.php">黑名单管理</a>
          <?php if(class_exists('\\lib\\Complain\\CommUtil')){?><a href="./complain.php">支付交易投诉</a><?php }?>
          <?php if(class_exists('\\lib\\WxMchRisk')){?><a href="./mchrisk.php">渠道商户违规记录</a><?php }?>
        </div>
      </div>

      <div class="admin-menu-group <?php echo checkIfActive('set,gonggao,set_wxkf')?>">
        <button type="button" class="admin-menu-toggle" aria-expanded="false"><i class="fa fa-cog"></i><span>系统设置</span><i class="fa fa-angle-down admin-menu-arrow"></i></button>
        <div class="admin-submenu">
          <a href="./set.php?mod=site">网站信息</a><a href="./set.php?mod=pay">支付配置</a><a href="./set.php?mod=risk">风控配置</a><a href="./set.php?mod=settle">结算规则</a><a href="./set.php?mod=transfer">转账付款</a><a href="./set.php?mod=oauth">快捷登录</a><a href="./set.php?mod=notice">消息提醒</a><a href="./set.php?mod=certificate">实名认证</a><a href="./gonggao.php">网站公告</a><a href="./set.php?mod=template">首页模板</a><a href="./set.php?mod=mail">邮箱与短信</a><a href="./set.php?mod=upimg">Logo 上传</a><a href="./set.php?mod=iptype">IP 地址</a><a href="./set.php?mod=proxy">中转代理</a><a href="./set.php?mod=cron">计划任务</a><a href="./set_wxkf.php">微信客服支付</a><a href="./set.php?mod=account">账号与密码</a>
        </div>
      </div>

      <div class="admin-menu-group <?php echo checkIfActive('clean,log,gettoken')?>">
        <button type="button" class="admin-menu-toggle" aria-expanded="false"><i class="fa fa-wrench"></i><span>日志与维护</span><i class="fa fa-angle-down admin-menu-arrow"></i></button>
        <div class="admin-submenu"><a href="./log.php">登录日志</a><a href="./clean.php">数据清理</a><a href="./gettoken.php">获取用户标识</a></div>
      </div>
    </nav>
  </aside>

  <header class="admin-topbar">
    <button type="button" class="admin-icon-button admin-sidebar-button" data-sidebar-open title="打开导航" aria-controls="admin-sidebar" aria-expanded="false"><i class="fa fa-bars"></i></button>
    <div class="admin-page-title"><span><?php echo $title ?></span><small><?php echo date('Y-m-d')?></small></div>
    <div class="admin-topbar-actions">
      <a class="admin-icon-button" href="../" target="_blank" title="打开站点"><i class="fa fa-external-link"></i></a>
      <div class="dropdown">
        <button type="button" class="admin-account-button dropdown-toggle" data-toggle="dropdown"><span class="admin-avatar"><i class="fa fa-user"></i></span><span><?php echo $conf['admin_user']?></span><i class="fa fa-angle-down"></i></button>
        <ul class="dropdown-menu dropdown-menu-right">
          <li><a href="./set.php?mod=account"><i class="fa fa-key"></i> 修改密码</a></li>
          <li class="divider"></li>
          <li><a href="./login.php?logout" onclick="return confirm('是否确定退出登录？')"><i class="fa fa-power-off"></i> 退出登录</a></li>
        </ul>
      </div>
    </div>
  </header>
  <script src="../assets/js/admin-ui.js?v=1"></script>
<?php }?>
