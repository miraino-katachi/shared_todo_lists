<?php

// 設定ファイルを読み込む。
require_once('../../App/config.php');

// クラスを読み込む。
use App\Util\Common;
use App\Model\Base;
use App\Model\Users;

// サニタイズ
$post = Common::sanitaize($_POST);

// ワンタイムトークンのチェック
if (!isset($post['token']) || !Common::isValidToken($post['token'])) {
    // エラーメッセージをセッションに保存して、リダイレクトする
    $_SESSION['msg']['error']  = '不正な処理が行われました。';
    header('Location: ./');
    exit;
}

try {
    // ユーザーの検索、ユーザー情報の取得
    $base = Base::getInstance();
    $db = new Users($base);
    $user = $db->getUser($post['user'], $post['password']);

    if (empty($user)) {
        // ユーザーの情報が取得できなかったとき
        // エラーメッセージをセッション変数に保存→ログインページに表示させる。
        $_SESSION['msg']['error'] = "ユーザー名またはパスワードが違います。";

        // POSTされてきたユーザー名をセッション変数に保存→ログインページのユーザー名のテキストボックスに表示させる。
        $_SESSION['post']['user'] = $post['user'];

        // ログインページへリダイレクト
        header('Location: ./');
        exit;
    } else {
        // ユーザーの情報が取得できたとき
        // ユーザーの情報をセッション変数に保存
        $_SESSION['user'] = $user;

        // セッション変数に保存されているエラーメッセージを削除
        unset($_SESSION['msg']['error']);

        // セッション変数に保存されているPOSTされてきたデータを削除
        unset($_SESSION['post']);

        // 作業一覧ページを表示
        header('Location: ../todo/');
        exit;
    }
} catch (Exception $e) {
    header('Location: ../error/error.php');
    exit;
}
