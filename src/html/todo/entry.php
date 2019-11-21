<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/SessionUtil.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/CommonUtil.php');
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

    // 担当者（ユーザー）のレコードを取得
    $db = new UsersModel();
    $users = $db->getUserAll();

    // セッションに保存したPOSTデータ
    $itemName = '';
    if (!empty($_SESSION['post']['item_name'])) {
        $itemName =  $_SESSION['post']['item_name'];
    }

    $userId = 0;
    if (!empty($_SESSION['post']['user_id'])) {
        $userId = $_SESSION['post']['user_id'];
    }

    $expireDate = date('Y-m-d');
    if (!empty($_SESSION['post']['expire_date'])) {
        $expireDate = $_SESSION['post']['expire_date'];
    }

    $finished = '';
    if (!empty($_SESSION['post']['finished'])) {
        $finished = " checked";
    }

    // セッションに保存したPOSTデータを削除
    unset($_SESSION['post']);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>作業登録</title>
<link rel="stylesheet" href="../css/normalize.css">
<link rel="stylesheet" href="../css/main.css">
</head>
<body>
<div class="container">
    <header>
         <div class="title">
            <h1>作業登録</h1>
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

        <form action="./entry_action.php" method="post">
            <table class="list">
                <tr>
                    <th>項目名</th>
                    <td class="align-left">
                        <input type="text" name="item_name" id="item_name" class="item_name" value="<?=$itemName?>">
                    </td>
                </tr>
                <tr>
                    <th>担当者</th>
                    <td class="align-left">
                        <select name="user_id" id="user_id" class="user_id">
                            <option value="">--選択してください--</option>
                            <?php foreach ($users as $user): ?>
                            <option value="<?=$user['id']?>"<?php if ($user['id'] == $userId) echo ' selected'; ?>><?=$user['family_name'].$user['first_name']?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>期限</th>
                    <td class="align-left">
                        <input type="date" name="expire_date" id="expire_date" class="expire_date" value="<?=$expireDate?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        完了
                    </th>
                    <td class="align-left">
                        <input type="checkbox" name="finished" id="finished" class="finished" value="1"<?=$finished?>> 完了
                    </td>
                </tr>
            </table>

            <input type="submit" value="登録">
            <input type="button" value="キャンセル" onclick="location.href='./';">
        </form>


    </main>

    <footer>

    </footer>
</div>
</body>
</html>