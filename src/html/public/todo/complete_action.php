<?php

// 設定ファイルを読み込む。
require_once('../../App/config.php');

// クラスを読み込む。
use App\Util\Common;
use App\Model\Base;
use App\Model\TodoItems;

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
    $_SESSION['msg']['err']  = '不正な処理が行われました。';
    header('Location: ./');
    exit;
}

try {
    // 完了処理
    $base = Base::getInstance();
    $db = new TodoItems($base);
    $db->makeTodoItemComplete($post['item_id']);
    header('Location: ./');
} catch (Exception $e) {
    header('Location: ../error/error.php');
    exit;
}
