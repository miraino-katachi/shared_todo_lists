<?php

// 設定ファイルを読み込む。
require_once('../../App/config.php');

// ログインユーザー情報を削除して、ログアウト処理とする
unset($_SESSION['user']);

// 念のためにセッションに保存した他の情報も削除する
unset($_SESSION['post']);
unset($_SESSION['msg']);

// ログインページへリダイレクト
header('Location: ./');
exit;
