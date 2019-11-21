<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/SessionUtil.php');

    // セッションスタート
    SessionUtil::sessionStart();

    // セッション変数に保存したPOSTデータ
    $user = "";
    if (!empty($_SESSION['post']['user'])) {
        $user = $_SESSION['post']['user'];
    }

    // セッション変数に保存したPOSTデータを削除
    unset($_SESSION['post']);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>ログイン</title>
<link rel="stylesheet" href="../css/normalize.css">
<link rel="stylesheet" href="../css/main.css">
</head>
<body>
<div class="container">
    <header>
        <h1>ログイン</h1>
    </header>

    <main>
    <?php if (!empty($_SESSION['msg']['error'])) : ?>
        <p class="error">
            <?=$_SESSION['msg']['error']?>
        </p>
    <?php endif ?>
        <form action="./login.php" method="post">
            <table class="login">
                <tr>
                    <th class="login_field">
                        ユーザー名
                    </th>
                    <td class="login_field">
                        <input type="text" name="user" id="user" class="login_user" value="<?=$user?>">
                    </td>
                </tr>
                <tr>
                    <th class="login_field">
                        パスワード
                    </th>
                    <td class="login_field">
                        <input type="password" name="password" id="password" class="login_pass">
                    </td>
                </tr>
            </table>
            <input type="submit" value="ログイン" id="login">
        </form>


    </main>

    <footer>

    </footer>
</div>
</body>
</html>