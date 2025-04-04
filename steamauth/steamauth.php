<?php
require('sessionname.php');
ob_start();
if(!isset($_SESSION)){
    session_start();
}
require ('openid.php');

function logoutbutton() {
    echo "<form action=\"steamauth/logout.php\" method=\"post\"><input value=\"Logout\" type=\"submit\" /></form>"; //logout button
}

function steamlogin()
{
try {

    require("settings.php");

    $openid = new LightOpenID($steamauth['domainname']);

    $button['small'] = "small";
    $button['large_no'] = "large_noborder";
    $button['large'] = "large_border";
    $button = $button[$steamauth['buttonstyle']];

    if(!$openid->mode) {
        if(isset($_GET['login'])) {
            $openid->identity = 'https://steamcommunity.com/openid';
            header('Location: ' . $openid->authUrl());
        }
	    echo "<form action=\"?login\" method=\"post\"> <input type=\"image\" src=\"http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_".$button.".png\"></form>";
	}
     elseif($openid->mode == 'cancel') {
        echo 'User has canceled authentication!';
    } else {
        if($openid->validate()) {
                $id = $openid->identity;
                $ptn = "/^https:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
                preg_match($ptn, $id, $matches);

                if(!isset($_SESSION)){
            	    session_start();
            	}
                $_SESSION['steamid'] = $matches[1];
				if (!headers_sent()) {
					header('Location: '.$steamauth['loginpage']);
					exit;
				} else {
					?>
					<script type="text/javascript">
						window.location.href="<?=$steamauth['loginpage']?>";
					</script>
					<noscript>
						<meta http-equiv="refresh" content="0;url=<?=$steamauth['loginpage']?>" />
					</noscript>
					<?php
					exit;
				}
        } else {
                echo "User is not logged in.\n";
        }

    }
} catch(ErrorException $e) {
    echo $e->getMessage();
}
}

?>
