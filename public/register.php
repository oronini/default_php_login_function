<?php
session_start();
require_once '../classes/UserLogic.php';

$err = [];

$token = filter_input(INPUT_POST, 'csrf_token');
// トークンがない、もしくは一致しない場合、処理を中止
if(!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
  exit('不正なリクエスト');
}
unset($_SESSION['csrf_token']);

// バリデーション
if(!$username = filter_input(INPUT_POST, 'username')){
  $err[] = 'ユーザー名を入力してください。';
}
if(!$email = filter_input(INPUT_POST, 'email')){
  $err[] = 'メールアドレスを入力してください。';
}
$password = filter_input(INPUT_POST, 'password');
if(!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,30}$/',$password)){
  $err[] = 'パスワードは半角英数字をのみで8文字以上30文字以内で1つの英字と1つの数字が必須です。';
}
$password_conf = filter_input(INPUT_POST, 'password_conf');
if ($password !== $password_conf) {
  $err[] = '確認用パスワードが一致しません。';
}

if(count($err) === 0) {
  // ユーザー登録する処理
  $hasCreated = UserLogic::createUser($_POST);

  if(!$hasCreated) {
    $err[] = '登録に失敗しました。';
  }
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ユーザー登録完了画面</title>
</head>
<body>
  <?php if(count($err) > 0 ): ?>
    <?php foreach ($err as $value) : ?>
      <p><?php echo $value ?></p>
    <?php endforeach  ?>
  <?php else : ?>
    <p>ユーザー登録完了</p>
  <?php endif ?>
  <a href="./signup_form.php">戻る</a>
</body>
</html>