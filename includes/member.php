<?php
$clientip=real_ip($conf['ip_type']?$conf['ip_type']:0);

if(isset($_COOKIE["admin_token"]))
{
	$token=authcode(daddslashes($_COOKIE['admin_token']), 'DECODE', SYS_KEY);
	$parts = explode("\t", $token);
	if(count($parts) === 3) list($user, $sid, $expiretime) = $parts;
	$session=md5($conf['admin_user'].$conf['admin_pwd'].$password_hash);
	if(isset($sid, $expiretime) && hash_equals($session, $sid) && $expiretime>time()) {
		$islogin=1;
	}
}
if(isset($_COOKIE["user_token"]))
{
	$token=authcode(daddslashes($_COOKIE['user_token']), 'DECODE', SYS_KEY);
	$parts = explode("\t", $token);
	if(count($parts) !== 3) return;
	list($uid, $sid, $expiretime) = $parts;
	$uid = intval($uid);
	$userrow=$DB->getRow("SELECT * FROM pre_user WHERE uid=:uid limit 1", [':uid'=>$uid]);
	$session=$userrow ? md5($userrow['uid'].$userrow['key'].$password_hash) : '';
	if($userrow && hash_equals($session, $sid) && $expiretime>time()) {
		$islogin2=1;
	}
}
?>
