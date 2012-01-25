<?
session_start();

require("config.php");
require("database.php");
require("user.php");
require("users.php");
require("order.php");
require("orders.php");
require("notify.php");
require("address.php");
require("bitcoin.inc.php");


if ($_SERVER['REMOTE_ADDR'] == $_SESSION["AUTH_FROM_IP"] && $_SESSION["AUTH_USER_NAME"] != "")
    define("USERNAME", $_SESSION["AUTH_USER_NAME"]);

function checklogin() {
    if ($_SERVER['REMOTE_ADDR'] != $_SESSION["AUTH_FROM_IP"] || $_SESSION["AUTH_USER_NAME"] == "") {
        header("Location: /logout.php");
        die();
    }
}

function checkOrderAccess($order) {
    $user = Users::getActiveUser();
    if ($user->userid != $order->userid) {
        header("Location: /logout.php");
        die();
    }
}

function httpPost($url, $params) {
    $options = "";
    foreach ($params as $key=>$val) {
        $options .= "&".$key."=".urlencode($val);
    }

    $curl_handle=curl_init();
    curl_setopt($curl_handle,CURLOPT_URL,$url);
    curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,10);
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle,CURLOPT_POST,TRUE);
    curl_setopt($curl_handle,CURLOPT_POSTFIELDS,$options);
    curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER, TRUE);

    $result = curl_exec($curl_handle);
    curl_close($curl_handle);

    return $result;
}

function topbar($page, $memberarea=false) {
    if ($memberarea) {
        if ($page == "orders") $ca11=' class="active" ';
        else if ($page == "profile") $ca12=' class="active" ';
        else if ($page == "api") $ca13=' class="active" ';
        else if ($page == "contact") $ca14=' class="active" ';
        else $ca10=' class="active" ';


        echo '
    <div class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" href="/">BitPing.Net</a>
          <ul class="nav">
            <li'.$ca10.'><a href="/member_start.php">Start</a></li>
            <li'.$ca11.'><a href="/member_orders.php">Orders</a></li>
	          <li'.$ca12.'><a href="/member_profile.php">Profile</a></li>
	          <li'.$ca13.'><a href="/member_api.php">API</a></li>
            <li'.$ca14.'><a href="/member_contact.php">Contact</a></li>
	          <li><a href="/logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
';
    } else {
        if ($page == "faq") $ca2=' class="active" ';
        else if ($page == "register") $ca3=' class="active" ';
        else if ($page == "contact") $ca4=' class="active" ';
        else if ($page == "legal") $ca5=' class="active" ';
        else $ca1=' class="active" ';

        echo '
    <div class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" href="/">BitPing.Net</a>
          <ul class="nav">
            <li'.$ca1.'><a href="/">Home</a></li>
<!--            <li'.$ca2.'><a href="/faq.php">FAQ</a></li> -->
	    <li'.$ca3.'><a href="/newuser.php">Register</a></li>
            <li'.$ca4.'><a href="/contact.php">Contact</a></li>
	    <li'.$ca5.'><a href="/legal.php">Legal</a></li>
          </ul>
          <form action="login.php" method="post" class="pull-right">
            <input class="input-small" type="text" name="user" placeholder="Username">
            <input class="input-small" type="password" name="pass" placeholder="Password">
            <button class="btn" type="submit">Sign in</button>
          </form>
        </div>
      </div>
    </div>
';
    }
}
?>
