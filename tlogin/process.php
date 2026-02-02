<?php session_start(); ?>
<?php
function _get_var($id, $dval)
{
  $rval = (isset($_POST[$id])) ? $_POST[$id] : $dval;
  return($rval);
}

$uname = _get_var('username', '');
$pass  = _get_var('txt_pass', '');

if ($uname == 'admin' && $pass == 'admin99') {
  echo "Login successful.";
  exit(0);
}

$now  = time();
$susp_secs = 10;

/* ✅ add this line */
$_SESSION['SUSP_END_TS'] = $now + $susp_secs;

/* ✅ message with countdown span */
$msg = "Account suspended for <span id='countdown_sec' class='count-num'>$susp_secs</span> seconds. Too many invalid login attempts.";

$_SESSION['SESSION_ERR']      = $msg;
$_SESSION['SESSION_SUSP_SEC'] = $susp_secs;

session_write_close();
header("location:login.php");
exit(0);
?>