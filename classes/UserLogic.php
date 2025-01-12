<?php
require_once 'dbconnect.php';

class UserLogic {
  /**
   * ユーザーを登録する
   * @param array $userData
   * @return bool $result
   */
  public static function createUser($userData) {
    $result = false;
    $sql = 'INSERT INTO users (name, email, password) VALUES (?, ?, ?)';

    $userArr = [];
    $userArr[] = $userData['username'];
    $userArr[] = $userData['email'];
    $userArr[] = password_hash($userData['password'],PASSWORD_DEFAULT);

    try {
      $stmt = connect()->prepare($sql);
      $result = $stmt->execute($userArr);
      return $result;
    }catch(\Exception $e){
      return $result;
    }
  }

  /**
   * ログイン処理
   * @param string $email
   * @param string $password
   * @return bool $result
   */
  public static function login($email, $password) {
    $result = false;
    $user = self::getUserByEmail($email);

    if(!$user) {
      $_SESSION['msg'] = 'emailが一致しません';
      return $result;
    }

    if(password_verify($password, $user['password'])){
      session_regenerate_id(true);
      $_SESSION['login_user']= $user;
      $result = true;
      return $result;
    }

    $_SESSION['msg'] = 'passwordが一致しません';
    return $result;
  }

  /**
   * emailからユーザーを検索して取得
   * @param string $email
   * @return array|bool $user|false
   */
  public static function getUserByEmail($email) {
    $result = false;
    $sql = 'SELECT * FROM users WHERE email = ?';

    $userArr = [];
    $userArr[] = $email;

    try {
      $stmt = connect()->prepare($sql);
      $stmt->execute($userArr);
      $user = $stmt->fetch();
      return $user;
    }catch(\Exception $e){
      return $result;
    }
  }

  /**
   * ログインチェック
   * @param void
   * @return bool $result
   */
  public static function checkLogin() {
    $result = false;

    // セッションにログインユーザーが入ってなければfalse
    if (isset($_SESSION['login_user']) && $_SESSION['login_user']['id'] > 0) {
      return $result =true;
    }

    return $result;
  }

    /**
   * ログインチェック
   * @param void
   * @return bool $result
   */
  public static function logout() {
    $_SESSION = array();
    session_destroy();
  }
}
?>