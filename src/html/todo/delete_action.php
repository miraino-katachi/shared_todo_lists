<?php
    require_once('../classes/util/SessionUtil.php');
    require_once('../classes/util/CommonUtil.php');
    require_once('../classes/model/TodoItemsModel.php');
    require_once('../classes/util/ValidationUtil.php');

    // セッションスタート
    SessionUtil::sessionStart();

    if (empty($_SESSION['user'])) {
        // 未ログインのとき
        header('Location: ../login/');
    } else {
        // ログイン済みのとき
        $user = $_SESSION['user'];
    }

    try {
        $db = new TodoItemsModel();
        $db->deleteTodoItemById($_SESSION['post']['item_id']);

        unset($_SESSION['post']);

        header('Location: ./');

    } catch (Exception $e) {
        // var_dump($e);
        header('Location: ../error/error.php');
    }
