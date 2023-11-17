<?php
require_once '../env.php'; //環境変数読み込み
require_once './settings.php'; //ルートディレクトリ読み込み
require_once DIR_ROOT . '/php/myAutoLoad.php'; //自動読み込み
require_once DIR_ROOT . '/php/functions/authAPIforUse.php'; //APIが有効かどうか自動判定

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

const VAPID_SUBJECT = 'nuxt.enoki.xyz';
const PUBLIC_KEY = VUE_APP_WebPush_PublicKey;
const PRIVATE_KEY = VUE_APP_WebPush_PrivateKey;

if (
  !isset($_POST['message'])
  || !isset($_SERVER['HTTP_ENDPOINT'])
  || !isset($_SERVER['HTTP_PUBLICKEY'])
  || !isset($_SERVER['HTTP_AUTHTOKEN'])
) {
  echo json_encode([
    'status' => 'invalid',
    'reason' => 'invalid authentication information',
    'errCode' => 10
  ]);
  exit;
}

$message = $_POST['message'];
$endPoint = $_SERVER['HTTP_ENDPOINT'];
$publicKey = $_SERVER['HTTP_PUBLICKEY'];
$authToken = $_SERVER['HTTP_AUTHTOKEN'];

if (isset($_POST['icon'])) {
  $icon = $_POST['icon'];
} else {
  $icon = null;
}
if (isset($_POST['title'])) {
  $title = $_POST['title'];
} else {
  $title = '通知確認テスト';
}

$res = sendPush($endPoint, $publicKey, $authToken, $title, $message, $icon);

if ($res) {
  echo json_encode([
    'status' => 'ok',
    'reason' => 'Thank you!'
  ]);
} else {
  echo json_encode([
    'status' => 'ng',
    'reason' => $res,
    'errCode' => 10
  ]);
  //この場合は無効なトークンを持っている場合が多い
  //リセットした方がいい
}
