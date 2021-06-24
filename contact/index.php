<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Content-Type');
// クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
// XSS対策
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);
$specification = h($_POST['specification']);
$age = h($_POST['age']);
$content = h($_POST['content']);
$content = mb_convert_encoding($content,"ISO-2022-JP-ms","UTF-8");
$password = h($_POST['password']);
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
if($password === $_ENV["PASSWORD"]): ?>

    <?php
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");

    $to = "sryotapersian@gmail.com";
    $subject = 'お問い合わせが届きました。';
    $headers .= "From: radiomailer@radiomailer.site";
    $message = <<< EOM
    ラジオメーラーよりお問い合わせが届きました。

    機能：{$specification}
    年齢：{$age}

    内容：
    {$content}
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