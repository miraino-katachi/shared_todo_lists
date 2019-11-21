<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/SessionUtil.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/util/CommonUtil.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/classes/model/TodoItemsModel.php');

    // セッションスタート
    SessionUtil::sessionStart();

    // 既に設定済みのセッションに保存されたPOSTデータを削除
    unset($_SESSION['post']);

    if (empty($_SESSION['user'])) {
        // 未ログインのとき
        header('Location: ../login/');
    } else {
        // ログイン済みのとき
        $user = $_SESSION['user'];
    }

    try {
        // 通常の一覧表示か、検索結果かを保存するフラグ
        $isSearch = false;

        $db = new TodoItemsModel();

        // 検索キーワード
        $search = "";

        if (isset($_GET['search'])) {
            // GETに項目があるときは、検索
            $get = CommonUtil::sanitaize($_GET);
            $search = $get['search'];
            $isSearch = true;
            $items = $db->getTodoItemBySearch($search);
        } else {
            // GETに項目がないときは、作業項目を全件取得
            $items = $db->getTodoItemAll();
        }

    } catch (Exception $e) {
        // var_dump($e);
        header('Location: ../error/error.php');
    }

    // 奇数行・偶数行の判定用カウンタ
    $line = 0;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>作業一覧</title>
<link rel="stylesheet" href="../css/normalize.css">
<link rel="stylesheet" href="../css/main.css">
</head>
<body>
<div class="container">
    <header>
        <div class="title">
            <h1>作業一覧</h1>
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
        <div class="main-header">
            <form action="./" method="get">
                <div class="entry">
                    <input type="button" name="entry-button" id="entry-button" class="entry-button" value="作業登録" onclick="location.href='./entry.php'">
                </div>
                <div class="search">
                    <input type="text" name="search" id="search" value="<?=$search?>">
                    <input type="submit" value="🔍検索">
                </div>
            </form>
        </div>

        <table class="list">
            <tr>
                <th>項目名</th>
                <th>担当者</th>
                <th>登録日</th>
                <th>期限日</th>
                <th>完了日</th>
                <th>操作</th>
            </tr>
<?php
    foreach ($items as $item) {
        if ($line % 2 == 0) {
            $class = "even";
        } else {
            $class = "odd";
        }
        if ($item['expire_date'] < date('Y-m-d') && empty($item['finished_date'])) {
            $class=" warning";
        }
?>
            <tr class="<?=$class?>">
                <td class="align-left">
                    <?=$item['item_name']?>
                </td>
                <td class="align-left">
                    <?=$item['family_name'].$item['first_name']?>
                </td>
                <td>
                    <?=$item['registration_date']?>
                </td>
                <td>
                    <?=$item['expire_date']?>
                </td>
                <td>
<?php
        if (empty($item['finished_date'])) {
            echo '未';
        } else {
            echo $item['finished_date'];
        }
?>
                </td>
                <td>
                    <form action="./complete.php" method="post">
                        <input type="hidden" name="item_id" value="<?=$item['id']?>">
                        <input type="submit" value="完了">
                    </form>
                    <form action="edit.php" method="post">
                        <input type="hidden" name="item_id" value="<?=$item['id']?>">
                        <input type="submit" value="更新">
                    </form>
                    <form action="delete.php" method="post">
                        <input type="hidden" name="item_id" value="<?=$item['id']?>">
                        <input type="submit" value="削除">
                    </form>
                </td>
            </tr>
<?php
        $line++;
    }
?>
        </table>
        <?php if ($isSearch): ?>
        <div class="main-footer">
            <form>
                <div class="goback">
                    <input type="button" value="戻る" onclick="location.href='./';">
                </div>
            </form>
        </div>
        <?php endif ?>
    </main>

    <footer>

    </footer>
</div>
</body>
</html>