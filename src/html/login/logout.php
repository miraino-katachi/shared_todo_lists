<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/SessionUtil.php');

    // セッションスタート
    SessionUtil::sessionStart();

    // ログインユーザー情報をクリアして、ログアウト処理とする
    $_SESSION['user'] = '';
    unset($_SESSION['user']);

    // 念のために他のセッション変数もクリアする
    $_SESSION['post'] = '';
    unset($_SESSION['post']);
    $_SESSION['msg'] = '';
    unset($_SESSION['msg']);

    // ログインページへリダイレクト
    header('Location: ./');
