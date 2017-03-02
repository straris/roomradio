<!DOCTYPE html>
<html>
<body>

<?php
$accessToken=$_GET ["accessToken"];
$filename="https://graph.facebook.com/oauth/access_token?grant_type=client_credentials&client_id=771848356225352&client_secret=4951d1f62335942e3e08267577f2a5ad";
$appToken = file_get_contents($filename);
$fbResponse= file_get_contents("https://graph.facebook.com/debug_token?input_token=".$accessToken."&".$appToken);

if (strpos($fbResponse,'error') !== false) {
	echo "<label for=\"msg\"  id=\"name\">
               <a id=\"profile\" >Guest:</a>
               </label>
               <div id=\"login\">
                  <fb:login-button size=\"medium\" scope=\"public_profile,email\" onlogin=\"checkLoginState();\"> </fb:login-button>
               </div>
               <div id=\"disclaimer\">
                  To chat with your facebook name, we don't care to post anything!
               </div>";
}
else {
	echo "<label for=\"msg\"  id=\"name\" hidden>
              <a id=\"profile\" href=".$_GET['profile']." hidden>".$_GET['name']."</a></label><script>chat.setName();</script>";
}

?>

</body>
</html>