<?php
/**
 * 登录
**/
$verifycode = 1;//验证码开关
$login_limit_count = 5;//登录失败次数
$login_limit_file = '@login.lock';

if(!function_exists("imagecreate") || !file_exists('code.php'))$verifycode=0;
include("../includes/common.php");

if(isset($_GET['act']) && $_GET['act']=='login'){
  if(!checkRefererHost())exit('{"code":403}');
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $code = trim($_POST['code']);
  $enc_type = isset($_POST['enc']) ? $_POST['enc'] : '0';
  if(empty($username) || empty($password)){
    exit(json_encode(['code'=>-1,'msg'=>'用户名或密码不能为空']));
  }
  if($verifycode==1 && (!$code || strtolower($code) != $_SESSION['vc_code'])){
    exit(json_encode(['code'=>-1,'msg'=>'验证码错误']));
  }
  $errcount = $DB->getColumn("SELECT count(*) FROM `pre_log` WHERE `ip`=:ip AND `date`>DATE_SUB(NOW(),INTERVAL 1 DAY) AND `uid`=0 AND `type`='登录失败'", [':ip'=>$clientip]);
  if($errcount >= $login_limit_count && file_exists($login_limit_file)){
    exit(json_encode(['code'=>-1,'msg'=>'多次登录失败，暂时禁止登录。可删除@login.lock文件解除限制']));
  }
  if($enc_type == '1'){
    $plain = '';
    $private_key = base64ToPem($conf['private_key'], 'PRIVATE KEY');
    $pkey = openssl_pkey_get_private($private_key);
    if(!openssl_private_decrypt(base64_decode($password), $plain, $pkey, OPENSSL_PKCS1_PADDING)){
      exit(json_encode(['code'=>-1,'msg'=>'密码解密失败']));
    }
    $password = $plain;
  }
  if($username == $conf['admin_user'] && $password == $conf['admin_pwd']){
    $DB->insert('log', ['uid'=>0, 'type'=>'登录后台', 'date'=>'NOW()', 'ip'=>$clientip]);
		$session=md5($username.$password.$password_hash);
		$expiretime=time() + 2592000;
		$token=authcode("{$username}\t{$session}\t{$expiretime}", 'ENCODE', SYS_KEY);
		setcookie("admin_token", $token, $expiretime, null, null, null, true);
    unset($_SESSION['vc_code']);
    exit(json_encode(['code'=>0]));
  }else{
    $DB->insert('log', ['uid'=>0, 'type'=>'登录失败', 'date'=>'NOW()', 'ip'=>$clientip]);
    unset($_SESSION['vc_code']);
    $errcount++;
    $retry_times = $login_limit_count - $errcount;
    if($retry_times < 0) $retry_times = 0;
    if($retry_times <= 0){
      file_put_contents($login_limit_file, '1');
      exit(json_encode(['code'=>-1,'msg'=>'多次登录失败，暂时禁止登录。可删除@login.lock文件解除限制','vcode'=>1]));
    }else{
      exit(json_encode(['code'=>-1,'msg'=>'用户名或密码错误，你还可以尝试'.$retry_times.'次','vcode'=>1]));
    }
  }
}elseif(isset($_GET['logout'])){
	if(!checkRefererHost())exit();
	setcookie("admin_token", "", time() - 2592000);
	exit("<script language='javascript'>window.location.href='./login.php';</script>");
}elseif($islogin==1){
	exit("<script language='javascript'>alert('您已登录！');window.location.href='./';</script>");
}
$title='用户登录';
include './head.php';
?>
  <main class="admin-login-shell">
    <section class="admin-login-brand">
      <div class="admin-login-brand-inner">
        <span class="admin-login-logo"><i class="fa fa-credit-card"></i></span>
        <h1>支付管理中心</h1>
        <p>EPAY ADMIN CONSOLE</p>
        <div class="admin-login-status"><i class="fa fa-circle"></i><span>管理服务正常</span></div>
      </div>
    </section>
    <section class="admin-login-main">
      <div class="admin-login-form">
        <div class="admin-login-heading">
          <span class="admin-login-mobile-logo"><i class="fa fa-credit-card"></i></span>
          <h2>管理员登录</h2>
          <p>使用管理账号进入控制台</p>
        </div>
        <form role="form" onsubmit="return submitlogin()">
          <div class="admin-login-field">
            <label for="admin-user">用户名</label>
            <div class="admin-login-input">
              <i class="fa fa-user"></i>
              <input id="admin-user" type="text" name="user" value="" class="form-control" placeholder="请输入用户名" autocomplete="username" required/>
            </div>
          </div>
          <div class="admin-login-field">
            <label for="admin-pass">密码</label>
            <div class="admin-login-input">
              <i class="fa fa-lock"></i>
              <input id="admin-pass" type="password" name="pass" class="form-control" placeholder="请输入密码" autocomplete="current-password" required/>
              <button type="button" class="admin-password-toggle" onclick="togglePassword(this)" title="显示密码"><i class="fa fa-eye"></i></button>
            </div>
          </div>
		  <?php if($verifycode==1){?>
          <div class="admin-login-field">
            <label for="admin-code">验证码</label>
            <div class="admin-login-input admin-login-captcha">
              <i class="fa fa-shield"></i>
              <input id="admin-code" type="text" class="form-control" name="code" placeholder="请输入验证码" autocomplete="off" required>
              <button type="button" class="admin-captcha-image" onclick="refreshVerifyCode()" title="更换验证码"><img id="verifycode" src="./code.php?r=<?php echo time();?>" alt="验证码"></button>
            </div>
          </div>
		  <?php }?>
          <button type="submit" class="btn btn-primary btn-block admin-login-submit"><span>登录控制台</span><i class="fa fa-arrow-right"></i></button>
        </form>
        <a class="admin-login-home" href="../"><i class="fa fa-arrow-left"></i> 返回网站首页</a>
      </div>
    </section>
  </main>
<script src="<?php echo $cdnpublic?>layer/3.1.1/layer.js"></script>
<script src="<?php echo $cdnpublic?>jsencrypt/3.5.4/jsencrypt.min.js"></script>
<script>
const PUBLIC_KEY_PEM = `<?php echo base64ToPem($conf['public_key'], 'PUBLIC KEY')?>`;
function togglePassword(button){
  var input = document.getElementById('admin-pass');
  var icon = button.querySelector('i');
  var visible = input.type === 'text';
  input.type = visible ? 'password' : 'text';
  icon.className = visible ? 'fa fa-eye' : 'fa fa-eye-slash';
  button.title = visible ? '显示密码' : '隐藏密码';
}
function refreshVerifyCode(){
  var image = document.getElementById('verifycode');
  if(image) image.src = './code.php?r=' + Math.random();
}
function submitlogin(){
  var enc_type = '0';
  var user = $("input[name='user']").val();
  var pass = $("input[name='pass']").val();
  var code = $("input[name='code']").val();
  if(user=='' || pass==''){layer.alert('用户名或密码不能为空！');return false;}
  if(PUBLIC_KEY_PEM != ''){
    const enc = new JSEncrypt();
    enc.setPublicKey(PUBLIC_KEY_PEM);
    pass = enc.encrypt(pass);
    if(pass) enc_type = '1';
  }
  var ii = layer.load(2);
  $.ajax({
    type : 'POST',
    url : '?act=login',
    data: {username:user, password:pass, code:code, enc:enc_type},
    dataType : 'json',
    success : function(data) {
      layer.close(ii);
      if(data.code == 0){
        layer.msg('登录成功，正在跳转', {icon: 1,shade: 0.01,time: 15000});
        window.location.href='./';
      }else{
        if(data.vcode==1){
          $("#verifycode").attr('src', './code.php?r='+Math.random())
        }
        layer.alert(data.msg, {icon: 2});
      }
    },
    error:function(data){
      layer.close(ii);
      layer.msg('服务器错误');
    }
  });
  return false;
}
</script>
</body>
</html>
