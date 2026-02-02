<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd"> <?php ob_start(); ?> <?php session_start(); ?> <?php require_once('bstamp.php');?> <html xmlns="http://www.w3.org/1999/xhtml"> <head>
<?php
header('Content-Type:text/html; charset=UTF-8'); header("Cache-Control: no-cache, no-store, must-revalidate"); header("Pragma: no-cache");
?>
<title> Login </title>


<link rel="stylesheet" href="./login.css">
<script langudage="Javascript">
var g_timeout=0;
var g_timer=null;
var g_login_suspended = 0;

// function fn_timeout()
// {
//   var tmout = document.getElementById('span_err');
//   if (g_timeout <= 0) {
//     clearInterval(g_timer);
//     freeze_input(0);
//     return;
//   }
//   tmout.innerHTML = g_timeout--;
// }

function fn_timeout()
{
  var tmout = document.getElementById('span_err');          // old place
  var msgCounter = document.getElementById('countdown_sec'); // inside message

  if (g_timeout <= 0) {
    clearInterval(g_timer);
    freeze_input(0);
    return;
  }

  if (tmout) tmout.innerHTML = g_timeout;
  if (msgCounter) msgCounter.innerHTML = g_timeout;

  g_timeout--;
}
function init()
{
  var tmout = document.getElementById('span_err');
  var tmout_val = tmout.innerHTML;
  if (tmout_val == '' || isNaN(tmout_val) || tmout_val == 0) {
    return;
  }
  g_timeout = tmout_val;
  g_timer = setInterval("fn_timeout()", 1000);
  freeze_input(1);
  tmout.innerHTML = tmout_val;
}

function freeze_input(f)
{
  var form  = document.getElementById('login_form');
  if (!form) return false;

  var user = form.username;
  var pass = form.txt_pass;

  if (f) {
    user.readOnly  = true;
    pass.readOnly  = true;
    // user.className = 'submit_blur';
    // pass.className = 'submit_blur';
    user.blur();
    pass.blur();
    g_login_suspended = 1;
  } else {
    window.location.reload();
  }
}


function login()
{
  if(g_login_suspended) {
     alert("Login suspended. Please try after " + g_timeout + " seconds");
     return false;
  }

  var form  = document.getElementById('login_form');
  if (!form) return false;

  var user = form.username;
  var pass = form.txt_pass;
  var msg  = document.getElementById('err_msg');
  var span_user = document.getElementById('span_user');
  var span_pass = document.getElementById('span_pass');

  // ✅ reset to normal
  span_user.className = 'fieldset';
  span_pass.className = 'fieldset';
  msg.innerHTML  = "";

  if ((user.value == '') || (pass.value == '')) {
    if (pass.value == '') {
       pass.focus();
       pass.select();
       span_pass.className = 'fieldset invalid';
    } 
    if (user.value == '') { 
       user.focus();
       user.select();
       span_user.className = 'fieldset invalid';
    }
    return false;
  }

  user.readOnly  = true;
  pass.readOnly  = true;
  // user.className = 'submit_blur';
  // pass.className = 'submit_blur';


  user.blur();
  pass.blur();

  return true;
}

function p_pwd_change(uri)
{
  top.location = "/webui/password_change.php";
}

function togglePassword(){
  var pass = document.getElementById("txt_pass");
  var open = document.getElementById("eye_open");
  var closed = document.getElementById("eye_closed");

  if (!pass) return;

  if (pass.type === "password") {
    pass.type = "text";
    open.style.display = "none";
    closed.style.display = "inline";
  } else {
    pass.type = "password";
    open.style.display = "inline";
    closed.style.display = "none";
  }
}
</script>

</head>

<body onload="init()">

<?php if(file_exists("/var/run/wp_auth_login.cfg")): ?>
  <div style="text-align:right; padding:12px 20px;">
    <a href="javascript:p_pwd_change();">Update HTTP/S Proxy User Password</a>
  </div>
<?php endif; ?>

<?php
$default_warn_msg = "This computer system is for authorized users only. All activity is logged and regularly monitored by systems personnel. Unauthorized or improper use of this system may result in civil and criminal penalties and administrative or disciplinary action, as appropriate. Anyone using this system consents to these terms.";
$warn_msg = '';
$custom_warn_msg_file="/etc/login_warn.txt";

if (file_exists($custom_warn_msg_file)) {
  $warn_msg = file_get_contents($custom_warn_msg_file);
} else {
  $warn_msg = $default_warn_msg;
}
?>

<div class="auth-page">
  <div class="auth-card">

    <!-- LEFT -->
    <div class="auth-left">
      <div class="auth-brand">
        <img src="images/LOGO1.jpeg" alt="Qsyntra" class="brand-logo" />
      </div>

      <h1 class="auth-title">Hey! good to be back.</h1>
      <p class="auth-subtitle">Intelligent Networking. Secure Access.</p>

      <div class="auth-illustration"></div>
    </div>

    <!-- RIGHT -->
    <div class="auth-right">

      <div class="auth-form-title">Sign in to your account</div>

      <div id="div_error_area" class="error-area">
        <span name="err_msg" id="err_msg">
          <?php if(isset($_SESSION['SESSION_ERR'])){ echo $_SESSION['SESSION_ERR'];} unset($_SESSION['SESSION_ERR']); ?>
        </span>
      </div>

      <form id="login_form" name="login_form"
            action="process.php"
            method="post"
            autocomplete="off"
            onSubmit="return login()"
            class="auth-form">

        <!-- ✅ Username with legend -->
        <fieldset id="span_user" class="fieldset">
          <legend class="legend">Username</legend>
          <div class="field-inner">
            <img src="images/user_nme_ico.png" alt="" class="field-icon"/>
            <input id="username" name="username" type="text" class="field-input"
                   onpaste="return false" ondrop="return false" ondrag="return false" oncopy="return false" autocomplete="off"/>
          </div>
        </fieldset>

        <!-- ✅ Password with legend -->
        <fieldset id="span_pass" class="fieldset">
          <legend class="legend">Password</legend>
          <div class="field-inner">
            <img src="images/pwd_ico.png" alt="" class="field-icon"/>

            <input id="txt_pass" name="txt_pass" type="password" class="field-input"
                   onpaste="return false" ondrop="return false" ondrag="return false" oncopy="return false" autocomplete="off"/>

            <button type="button" class="pwd-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
              <span id="eye_open">👁</span>
              <span id="eye_closed" style="display:none;">👁</span>
            </button>
          </div>
        </fieldset>

        <button id="submit" name="submit" type="submit" class="btn-primary">Sign in</button>
      </form>

      <!-- timeout -->
      <div class="login_tmout">
        <span name="span_err" id="span_err">
          <?php if(isset($_SESSION['SESSION_SUSP_SEC'])){ echo $_SESSION['SESSION_SUSP_SEC'];} unset($_SESSION['SESSION_SUSP_SEC']); ?>
        </span>
      </div>

      <!-- WARNING BELOW BUTTON -->
      <?php if ($display_warn != 0): ?>
        <div class="warn_msg warn_inside">
          <span class="warn-label">WARNING!</span> <?php echo $warn_msg;?>
        </div>
      <?php endif; ?>

    </div>

  </div>
</div>

<?php
$bottom_logo=(dirname(__FILE__)."/html/topbar/bottom_logo.php");
if (file_exists($bottom_logo)) {
  include_once($bottom_logo);
}
?>

</body>
</html>

<?php ob_flush();?>


