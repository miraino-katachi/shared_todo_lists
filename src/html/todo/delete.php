<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/SessionUtil.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/CommonUtil.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/model/TodoItemsModel.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/model/UsersModel.php');

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
        // 指定IDの作業項目を取得
        $db = new TodoItemsModel();
        $item = $db->getTodoItemById($post['item_id']);

    } catch (Exception $e) {
        // var_dump($e);
        header('Location: ../error/error.php');
    }

    // POSTされてきたitem_idをセッションに保存
    $_SESSION['post']['item_id'] = $post['item_id'];

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>削除確認</title>
<link rel="stylesheet" href="../css/normalize.css">
<link rel="stylesheet" href="../css/main.css">
</head>
<body>
<div class="container">
    <header>
         <div class="title">
            <h1>削除確認</h1>
        </div>
        <div class="login_info">
            <ul>
            <li>ようこそ<?=$user['family_name'].$user['first_name'] ?>さん</li>
                <li>
                    <form>
                        <input type="button" value="ログアウト" onclick="location.href='../login/index.html';">
                    </form>
                </li>
            </ul>
        </div>
    </header>

    <main>
    <?php if (!empty($_SESSION['msg']['error'])): ?>
        <p class="error">
            <?=$_SESSION['msg']['error']?>
        </p>
    <?php endif ?>

        <p>
            下記の項目を削除します。よろしいですか？
        </p>
        <form action="./delete_action.php" method="post">
            <table class="list">
                <tr>
                    <th>項目名</th>
                    <td class="align-left">
                        <?=$item['item_name']?>
                    </td>
                </tr>
                <tr>
                    <th>担当者</th>
                    <td class="align-left">
                    <?=$user['family_name'].$user['first_name']?>
                    </td>
                </tr>
                <tr>
                    <th>期限</th>
                    <td class="align-left">
                        <?=$item['expire_date']?>
                    </td>
                </tr>
                <tr>
                    <th>
                        完了
                    </th>
                    <td class="align-left">
                        <?php if (empty($item['finished_date'])): ?>
                        未完了
                        <?php else: ?>
                        完了
                        <?php endif ?>
                    </td>
                </tr>
            </table>

            <input type="submit" value="削除">
            <input type="button" value="キャンセル" onclick="location.href='./index.php';">
        </form>


    </main>

    <footer>

    </footer>
</div>
</body>
</html>