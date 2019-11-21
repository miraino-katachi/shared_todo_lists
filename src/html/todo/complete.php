<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/SessionUtil.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/CommonUtil.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/model/TodoItemsModel.php');

    // セッションスタート
    SessionUtil::sessionStart();

    if (empty($_SESSION['user'])) {
        // 未ログインのとき
        header('Location: ../login/');
    } else {
        // ログイン済みのとき
        $user = $_SESSION['user'];
    }

    // サニタイズ
    $post = CommonUtil::sanitaize($_POST);

    try {
        $db = new TodoItemsModel();
        $db->makeTodoItemComplete($post['item_id']);
        header('Location: ./');

    } catch (Exception $e) {
        // var_dump($e);
        header('Location: ../error/error.php');
    }
