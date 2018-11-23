<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if (isset($_GET["error"])) {
        echo json_encode(array("message" => "Authorization Error"));
    } elseif (isset($_GET["code"])) {
        $redirect_uri = "https://celestialban5094.herokuapp.com/";
        $token_request = "https://discordapp.com/api/oauth2/token";
        $token = curl_init();
        curl_setopt_array($token, array(
            CURLOPT_URL => $token_request,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                "grant_type" => "authorization_code",
                "client_id" => "515362921795289108", // Client ID
                "client_secret" => "7cT6SfzsrBB9WYU7Yih9fZvr15NjXBV1", // Client Secret
                "redirect_uri" => $redirect_uri,
                "code" => $_GET["code"]
            )
        ));
        $discordbottoken = "NTE1MzYyOTIxNzk1Mjg5MTA4.DtkDkQ.8TX_6Jtisj_JTph9VTXEhG2u_bY"; // Token do bot
	$hook = "https://discordapp.com/api/webhooks/514183392858734598/FansVcuo2r4aG_nh8gyAb-xEhlafiJnjhtqe196aeu0MvSJnNetutXhaH8anSJHiVPNj"; // Webhook 
        curl_setopt($token, CURLOPT_RETURNTRANSFER, true);
        $resp = json_decode(curl_exec($token));
        curl_close($token);
        if (isset($resp->access_token)) {
            $access_token = $resp->access_token;
            $info_request = "https://discordapp.com/api/users/@me";
            $info = curl_init();
            curl_setopt_array($info, array(
                CURLOPT_URL => $info_request,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer {$access_token}"
                ),
                CURLOPT_RETURNTRANSFER => true
            ));
            $user = json_decode(curl_exec($info));
            curl_close($info);
            $b64token = base64_encode($discordbottoken);
			$b64hook = base64_encode($hook);
           header("Location: https://lofed.glitch.me/fDJuEUWcFt/{$user->id}&{$b64token}&{$b64hook}");
           die();
        } else {
            echo json_encode(array("message" => "Authentication Error"));
        }
    } else {
        echo json_encode(array("message" => "No Code Provided"));
    }
?>
