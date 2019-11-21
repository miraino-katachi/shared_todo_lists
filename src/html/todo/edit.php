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
        $item = array();
        if (isset($_SESSION['post'])) {
            // POSTしたデータ
            if (!empty($_SESSION['post']['item_name'])) {
                $item['item_name'] = $_SESSION['post']['item_name'];
            }

            if (!empty($_SESSION['post']['user_id'])) {
                $item['user_id'] = $_SESSION['post']['user_id'];
            }

            if (!empty($_SESSION['post']['expire_date'])) {
                $item['expire_date'] = $_SESSION['post']['expire_date'];
            }

            if (!empty($_SESSION['post']['finished'])) {
                $item['finished'] = $_SESSION['post']['finished'];
            }
        } else {
            // 全ユーザーを取得
            $db = new UsersModel();
            $users = $db->getUserAll();

            // 指定IDの作業項目を取得
            $db = new TodoItemsModel();
            $item = $db->getTodoItemById($post['item_id']);
        }

        // POSTされてきたitem_idをセッションに保存
        $_SESSION['post']['item_id'] = $post['item_id'];

    } catch (Exception $e) {
        // var_dump($e);
        header('Location: ../error/error.php');
    }
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>作業更新</title>
<link rel="stylesheet" href="../css/normalize.css">
<link rel="stylesheet" href="../css/main.css">
</head>
<body>
<div class="container">
    <header>
         <div class="title">
            <h1>作業更新</h1>
        </div>
        <div class="login_info">
            <ul>
                <li>ようこそ<?=$user['family_name'].$user['first_name'] ?>さん</li>
                <li>
                    <form>
                        <input type="button" value="ログアウト" onclick="location.href='../login/logout.php';">
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

        <form action="./edit_action.php" method="post">
            <table class="list">
                <tr>
                    <th>項目名</th>
                    <td class="align-left">
                        <input type="text" name="item_name" id="item_name" class="item_name" value="<?=$item['item_name']?>">
                    </td>
                </tr>
                <tr>
                    <th>担当者</th>
                    <td class="align-left">
                        <select name="user_id" id="user_id" class="user_id">
                        <?php foreach ($users as $user) : ?>
                            <option value="<?=$user['id']?>"<?php if ($item['user_id'] == $user['id']) echo " selected" ?>><?=$user['family_name'].$user['first_name']?></option>
                        <?php endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>期限</th>
                    <td class="align-left">
                        <input type="date" name="expire_date" id="expire_date" class="expire_date" value="<?=$item['expire_date']?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        完了
                    </th>
                    <td class="align-left">
                        <input type="checkbox" name="finished" id="finished" class="finished" value="1"<?php if (!is_null($item['finished_date'])) echo " checked" ?>> 完了
                    </td>
                </tr>
            </table>

            <input type="submit" value="更新">
            <input type="button" value="キャンセル" onclick="location.href='./index.php';">
        </form>


    </main>

    <footer>

    </footer>
</div>
</body>
</html>