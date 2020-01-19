<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/SessionUtil.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/CommonUtil.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/model/TodoItemsModel.php');

// セッションスタート
SessionUtil::sessionStart();

// 既に設定済みのセッションに保存されたPOSTデータを削除
unset($_SESSION['post']);

// エラーメッセージを削除
unset($_SESSION['msg']['error']);

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
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>作業一覧</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        form {
            display: inline-block;
        }
    </style>
</head>

<body>
    <!-- ナビゲーション -->
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <span class="navbar-brand">TODOリスト</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="./">作業一覧 <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./entry.php">作業登録</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $user['family_name'] . $user['first_name'] ?>さん
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../login/logout.php">ログアウト</a>
                    </div>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" action="./" method="get">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search" value="<?= $search ?>">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">検索</button>
            </form>
        </div>
    </nav>
    <!-- ナビゲーション ここまで -->

    <!-- コンテナ -->
    <div class="container">

        <table class="table table-striped table-hover table-sm my-2">
            <thead>
                <tr>
                    <th scope="col">項目名</th>
                    <th scope="col">担当者</th>
                    <th scope="col">登録日</th>
                    <th scope="col">期限日</th>
                    <th scope="col">完了日</th>
                    <th scope="col">操作</th>
                </tr>
            </thead>

            <tbody>
                <?php
                foreach ($items as $item) {
                    if ($item['expire_date'] < date('Y-m-d') && empty($item['finished_date'])) {
                        $class = ' class="text-danger"';
                    } else {
                        $class = '';
                    }
                ?>
                    <tr<?= $class ?>>
                        <td class="align-middle">
                            <?= $item['item_name'] ?>
                        </td>
                        <td class="align-middle">
                            <?= $item['family_name'] . $item['first_name'] ?>
                        </td>
                        <td class="align-middle">
                            <?= $item['registration_date'] ?>
                        </td>
                        <td class="align-middle">
                            <?= $item['expire_date'] ?>
                        </td>
                        <td class="align-middle">
                            <?php
                            if (empty($item['finished_date'])) {
                                echo '未';
                            } else {
                                echo $item['finished_date'];
                            }
                            ?>
                        </td>
                        <td class="align-middle">
                            <form action="./complete.php" method="post" class="my-sm-1">
                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                <button class="btn btn-primary my-0" type="submit">完了</button>
                            </form>
                            <form action="edit.php" method="post" class="my-sm-1">
                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                <input class="btn btn-primary my-0" type="submit" value="修正">
                            </form>
                            <form action="delete.php" method="post" class="my-sm-1">
                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                <input class="btn btn-primary my-0" type="submit" value="削除">
                            </form>
                        </td>
                        </tr>
                    <?php
                }
                    ?>
            </tbody>
        </table>

        <?php if ($isSearch) : ?>
            <!-- 検索のとき、戻るボタンを表示する -->
            <div class="row">
                <div class="col">
                    <form>
                        <div class="goback">
                            <input class="btn btn-primary my-0" type="button" value="戻る" onclick="location.href='./';">
                        </div>
                    </form>
                </div>
            </div>
        <?php endif ?>

    </div>
    <!-- コンテナ ここまで -->

    <!-- 必要なJavascriptを読み込む -->
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>

</body>

</html>