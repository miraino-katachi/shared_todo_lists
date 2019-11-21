<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/SessionUtil.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/CommonUtil.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/model/TodoItemsModel.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/ValidationUtil.php');

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

    // POSTされてきた値をセッション変数に保存する
    $_SESSION['post']['item_name'] = $post['item_name'];
    $_SESSION['post']['user_id'] = $post['user_id'];
    $_SESSION['post']['expire_date'] = $post['expire_date'];
    $_SESSION['post']['finished'] = !empty($post['finished']) ? $post['finished'] : null;

    // バリデーション
    if (!ValidationUtil::isValidItemName($post['item_name'])) {
        $_SESSION['msg']['error'] = "項目名を正しく入力してください。（項目名は100文字以下にしてください）";
        header('Location: ./entry.php');
        exit;
    }

    if (empty($post['user_id'])) {
        $_SESSION['msg']['error'] = "担当者を選択してください。";
        header('Location: ./entry.php');
        exit;
    }

    if (!ValidationUtil::isValidUserId($post['user_id'])) {
        $_SESSION['msg']['error'] = "指定の担当者は存在しません。";
        header('Location: ./entry.php');
        exit;
    }

    if (!ValidationUtil::isDate($post['expire_date'])) {
        $_SESSION['msg']['error'] = "期限日の日付が正しくありません。";
        header('Location: ./entry.php');
        exit;
    }

    if (!empty($post['finished']) && $post['finished'] != 1 ) {
        $_SESSION['msg']['error'] = "完了のチェックボックスの値が正しくありません。";
        header('location ./entry.php');
        exit;
    }

    // バリデーションを通過したら、処理を行う。
    // セッション変数に保存したエラーメッセージをクリアする
    $_SESSION['msg']['error'] = '';

    // データベースに登録する内容を連想配列にする。
    $data = array(
        'user_id' => $post['user_id'],
        'item_name' => $post['item_name'],
        'registration_date' => date('Y-m-d'),
        'expire_date' => $post['expire_date'],
        'finished_date' => isset($post['finished']) && $post['finished'] == 1 ? date('Y-m-d') : null,
    );

    try {
        $db = new TodoItemsModel();
        $db->registerTodoItem($data);

        // セッションに保存したPOSTデータを削除
        unset($_SESSION['post']);

        header('Location: ./');

    } catch (Exception $e) {
        // var_dump($e);
        header('Location: ../error/error.php');
    }
