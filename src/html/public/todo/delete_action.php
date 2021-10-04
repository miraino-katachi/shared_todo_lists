<?php

// 設定ファイルを読み込む。
require_once('../../App/config.php');

// クラスを読み込む。
use App\Util\Common;
use App\Model\Base;
use App\Model\TodoItems;

// エラーメッセージを削除する
unset($_SESSION['msg']['error']);

if (empty($_SESSION['user'])) {
    // 未ログインのとき
    header('Location: ../login/');
} else {
    // ログイン済みのとき
    $user = $_SESSION['user'];
}

// サニタイズ
$post = Common::sanitaize($_POST);

// ワンタイムトークンのチェック
if (!isset($post['token']) || !Common::isValidToken($post['token'])) {
    // エラーメッセージをセッションに保存して、リダイレクトする
    $_SESSION['msg']['error']  = '不正な処理が行われました。';
    header('Location: ./delete.php?item_id='.$post['item_id']);
    exit;
}

try {
    // 削除処理
    $base = Base::getInstance();
    $db = new TodoItems($base);
    $db->deleteTodoItemById($post['item_id']);

    // セッションに保存されたPOSTデータを削除する
    unset($_SESSION['post']);

    header('Location: ./');
    exit;
} catch (Exception $e) {
    header('Location: ../error/error.php');
    exit;
}
