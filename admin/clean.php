<?php
/**
 * 系统数据清理
**/
include("../includes/common.php");
$title='系统数据清理';
include './head.php';
if($islogin==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
<?php
$mod=isset($_GET['mod'])?$_GET['mod']:null;
if($mod=='cleancache'){
$CACHE->clear();
if(function_exists("opcache_reset"))@opcache_reset();
showmsg('清理系统设置缓存成功！',1);
}elseif($mod=='cleanorder'){
$DB->exec("DELETE FROM `pre_order` WHERE addtime<'".date("Y-m-d H:i:s",strtotime("-30 days"))."'");
$DB->exec("OPTIMIZE TABLE `pre_order`");
showmsg('删除30天前订单记录成功！',1);
}elseif($mod=='cleansettle'){
$DB->exec("DELETE FROM `pre_settle` WHERE addtime<'".date("Y-m-d H:i:s",strtotime("-30 days"))."'");
$DB->exec("OPTIMIZE TABLE `pre_settle`");
showmsg('删除30天前结算记录成功！',1);
}elseif($mod=='cleanrecord'){
$DB->exec("DELETE FROM `pre_record` WHERE date<'".date("Y-m-d H:i:s",strtotime("-30 days"))."'");
$DB->exec("OPTIMIZE TABLE `pre_record`");
showmsg('删除30天前资金明细成功！',1);
}elseif($mod=='cleanorderi' && $_POST['do']=='submit'){
$days = intval($_POST['days']);
if($days<=0)showmsg('请确保每项不能为空',3);
$DB->exec("DELETE FROM `pre_order` WHERE addtime<'".date("Y-m-d H:i:s",strtotime("-{$days} days"))."'");
$DB->exec("OPTIMIZE TABLE `pre_order`");
showmsg('删除订单记录成功！',1);
}elseif($mod=='cleansettlei' && $_POST['do']=='submit'){
$days = intval($_POST['days']);
if($days<=0)showmsg('请确保每项不能为空',3);
$DB->exec("DELETE FROM `pre_settle` WHERE addtime<'".date("Y-m-d H:i:s",strtotime("-{$days} days"))."'");
$DB->exec("OPTIMIZE TABLE `pre_settle`");
showmsg('删除结算记录成功！',1);
}elseif($mod=='cleanrecordi' && $_POST['do']=='submit'){
$days = intval($_POST['days']);
if($days<=0)showmsg('请确保每项不能为空',3);
$DB->exec("DELETE FROM `pre_record` WHERE date<'".date("Y-m-d H:i:s",strtotime("-{$days} days"))."'");
$DB->exec("OPTIMIZE TABLE `pre_record`");
showmsg('删除资金明细成功！',1);
}elseif($mod=='cleantransferi' && $_POST['do']=='submit'){
$days = intval($_POST['days']);
if($days<=0)showmsg('请确保每项不能为空',3);
$DB->exec("DELETE FROM `pre_transfer` WHERE paytime<'".date("Y-m-d H:i:s",strtotime("-{$days} days"))."'");
$DB->exec("OPTIMIZE TABLE `pre_transfer`");
showmsg('删除付款记录成功！',1);
}elseif($mod=='cleanpsorderi' && $_POST['do']=='submit'){
$days = intval($_POST['days']);
if($days<=0)showmsg('请确保每项不能为空',3);
$DB->exec("DELETE FROM `pre_psorder` WHERE addtime<'".date("Y-m-d H:i:s",strtotime("-{$days} days"))."'");
$DB->exec("OPTIMIZE TABLE `pre_psorder`");
showmsg('删除分账记录成功！',1);
}else{
?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title">系统数据清理</h3></div>
<div class="panel-body">
<div class="clean-action-list">
  <a href="./clean.php?mod=cleancache" class="btn btn-default"><i class="fa fa-refresh"></i> 清理设置缓存</a>
  <a href="./clean.php?mod=cleanorder" onclick="return confirm('你确实要删除30天前的订单记录吗？');" class="btn btn-default"><i class="fa fa-trash-o"></i> 删除30天前订单记录</a>
  <a href="./clean.php?mod=cleansettle" onclick="return confirm('你确实要删除30天前的结算记录吗？');" class="btn btn-default"><i class="fa fa-trash-o"></i> 删除30天前结算记录</a>
  <a href="./clean.php?mod=cleanrecord" onclick="return confirm('你确实要删除30天前的资金明细吗？');" class="btn btn-default"><i class="fa fa-trash-o"></i> 删除30天前资金明细</a>
</div>
<h4>自定义清理</h4>
<?php
$clean_items = [
  ['action'=>'cleanorderi', 'name'=>'订单记录', 'description'=>'天前的订单记录'],
  ['action'=>'cleansettlei', 'name'=>'结算记录', 'description'=>'天前的结算记录'],
  ['action'=>'cleanrecordi', 'name'=>'资金明细', 'description'=>'天前的资金明细'],
  ['action'=>'cleantransferi', 'name'=>'付款记录', 'description'=>'天前的付款记录'],
  ['action'=>'cleanpsorderi', 'name'=>'分账记录', 'description'=>'天前的分账记录'],
];
foreach($clean_items as $item){
?>
<form class="clean-custom-row" action="./clean.php?mod=<?php echo $item['action']?>" method="post" role="form">
  <input type="hidden" name="do" value="submit"/>
  <label><?php echo $item['name']?></label>
  <div class="clean-days-input"><input type="number" min="1" name="days" value="" placeholder="天数" required/><span><?php echo $item['description']?></span></div>
  <button type="submit" name="submit" class="btn btn-sm btn-danger" onclick="return confirm('删除后无法恢复，确定继续吗？');"><i class="fa fa-trash-o"></i> 立即删除</button>
</form>
<?php }?>
</div>
<div class="panel-footer">
<span class="glyphicon glyphicon-info-sign"></span>
定期清理数据有助于提升网站访问速度
</div>
</div>
<?php }?>
 </div>
</div>
