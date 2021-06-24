<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Content-Type');
// クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
// XSS対策
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}
$rest_json = file_get_contents("php://input"); // JSONでPOSTされたデータを取り出す
$_POST = json_decode($rest_json, true); // JSON文字列をデコード
$radioName = h($_POST['radioName']);
$fromName = h($_POST['fromName']);
$fromAddress = h($_POST['fromAddress']);
$addressForRadio = h($_POST['addressForRadio']);
$age = h($_POST['age']);
$tel = h($_POST['tel']);
$mail = h($_POST['mail']);
$corner = h($_POST['corner']);
$content = h($_POST['content']);
$content = mb_convert_encoding($content,"ISO-2022-JP-ms","UTF-8");
$password = h($_POST['password']);
if($password === 'XGb(N~/vVR%y'): ?>

    <?php
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");

    $to = "sryotapersian@gmail.com";
    $subject = $corner;
    $headers .= "From: radiomailer@radiomailer.site";
    $message = <<< EOM

    住所：{$addressForRadio}
    ラジオネーム：{$radioName}

    内容：
    {$content}


    氏名：{$fromName}
    電話番号：{$tel}
    住所（フル）：{$fromAddress}
    投稿者のメールアドレス: {$mail}
    EOM;


    if(mb_send_mail($to, $subject, $message, $headers, "-f radiomailer@radiomailer.site")) {
        echo json_encode(
            [
            "error" => false,
            ]
        ); 
    } else {
        echo json_encode(
            [
            "error" => true,
            ]
        ); 
    }
    ?>
<?php else: ?>
        <?php echo json_encode(
            [
            "error" => true,
            ]
        ); ?>
<?php endif; ?>