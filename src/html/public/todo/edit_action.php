<?php

require_once('../../App/config.php');

// クラスを読み込む。
use App\Util\Common;
use App\Util\Validation;
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

// リダイレクト先のURL
$url = './edit.php?item_id=' . $post['id'];

// ワンタイムトークンのチェック
if (!isset($post['token']) || !Common::isValidToken($post['token'])) {
    // エラーメッセージをセッションに保存して、リダイレクトする
    $_SESSION['msg']['error']  = '不正な処理が行われました。';
    header("Location: ${url}");
    exit;
}

// POSTされてきた値をセッション変数に保存する
$_SESSION['post'] = $post;
$_SESSION['post']['finished'] = !empty($post['finished']) ? $post['finished'] : null;

// バリデーション
if ($post['item_name'] == '') {
    $_SESSION['msg']['error'] = "項目名を入力してください。";
    header("Location: ${url}");
    exit;
}

if (!Validation::isValidItemName($post['item_name'])) {
    $_SESSION['msg']['error'] = "項目名は100文字以下にしてください。";
    header("Location: ${url}");
    exit;
}

if (empty($post['user_id'])) {
    $_SESSION['msg']['error'] = "担当者を選択してください。";
    header("Location: ${url}");
    exit;
}

if (!Validation::isValidUserId($post['user_id'])) {
    $_SESSION['msg']['error'] = "指定の担当者は存在しません。";
    header("Location: ${url}");
    exit;
}

if (!Validation::isDate($post['expire_date'])) {
    $_SESSION['msg']['error'] = "期限日の日付が正しくありません。";
    header("Location: ${url}");
    exit;
}

if (!empty($post['finished']) && $post['finished'] != 1) {
    $_SESSION['msg']['error'] = "完了のチェックボックスの値が正しくありません。";
    header("Location: ${url}");
    exit;
}

// バリデーションを通過したら、処理を行う。
// セッション変数に保存したエラーメッセージをクリアする
$_SESSION['msg']['error'] = '';

// データベースに登録する内容を連想配列にする。
$data = array(
    'id' => $post['id'],
    'user_id' => $post['user_id'],
    'item_name' => $post['item_name'],
    'expire_date' => $post['expire_date'],
    'finished_date' => isset($post['finished']) && $post['finished'] == 1 ? date('Y-m-d') : null,
    'is_deleted' => 0,
);

try {
    $base = Base::getInstance();
    $db = new TodoItems($base);
    $db->updateTodoItemById($data);

    // セッションに保存されたPOSTデータを削除する
    unset($_SESSION['post']);

    header('Location: ./');
    exit;
} catch (Exception $e) {
    header('Location: ../error/error.php');
    exit;
}
