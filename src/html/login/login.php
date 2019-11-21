<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/SessionUtil.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/CommonUtil.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/model/UsersModel.php');

    // セッションスタート
    SessionUtil::sessionStart();

    // サニタイズ
    $post = CommonUtil::sanitaize($_POST);

    try {
        // ユーザーの検索、ユーザー情報の取得
        $db = new UsersModel();
        $user = $db->getUser($post['user'], $post['password']);

        if (empty($user)) {
            // ユーザーの情報が取得できなかったとき
            // エラーメッセージをセッション変数に保存→ログインページに表示させる。
            $_SESSION['msg']['error'] = "ユーザー名またはパスワードが違います。";

            // POSTされてきたユーザー名をセッション変数に保存→ログインページのユーザー名のテキストボックスに表示させる。
            $_SESSION['post']['user'] = $post['user'];

            // ログインページへリダイレクト
            header('Location: ./');
        } else {
            // ユーザーの情報が取得できたとき
            // ユーザーの情報をセッション変数に保存
            $_SESSION['user'] = $user;

            // セッション変数に保存されているエラーメッセージをクリア
            $_SESSION['msg']['error'] = "";
            unset($_SESSION['msg']['error']);

            // セッション変数に保存されているPOSTされてきたデータをクリア
            $_SESSION['post'] = "";
            unset($_SESSION['post']);

            // 作業一覧ページを表示
            header('Location: ../todo/');
        }

    } catch (Exception $e) {
        // var_dump($e);exit;
        header('Location: ../error/error.php');
    }
