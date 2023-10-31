<?php
require_once './settings.php'; //env読み込み用、これを読み込まないと動かないもの多い
require_once './functions/database.php';
require_once './functions/authAPI.php';
require_once './functions/authAPIforUse.php'; //APIが有効かどうか自動判定

if (
  !isset($_GET['id']) ||
  !isset($_GET['password'])
) {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'invalid GET params',
    'errCode' => 10
  ]);
  exit;
}

$id = $_GET['id'];
$password = $_GET['password'];
$token = createUserToken($id, $password);
if ($token) {
  echo json_encode([
    'status' => 'ok',
    'reason' => 'Thank you!',
    'token' => $token
  ]);
} else {
  echo json_encode([
    'status' => 'ng',
    'reason' => 'Unknown user',
    'errCode' => 20
  ]);
}
