<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/SessionUtil.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/util/CommonUtil.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/classes/model/UsersModel.php');

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
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>作業登録</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
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
                <li class="nav-item">
                    <a class="nav-link" href="./">作業一覧</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="./entry.php">作業登録 <span class="sr-only">(current)</span></a>
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
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="search" value="">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">検索</button>
            </form>
        </div>
    </nav>
    <!-- ナビゲーション ここまで -->

    <!-- コンテナ -->
    <div class="container">
        <div class="container">
            <div class="row my-2">
                <div class="col-sm-3"></div>
                <div class="col-sm-6 alert alert-info">
                    作業を登録してください
                </div>
                <div class="col-sm-3"></div>
            </div>

            <!-- エラーメッセージ -->
            <?php if (!empty($_SESSION['msg']['error'])) : ?>
                <div class="row my-2">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6 alert alert-danger alert-dismissble fade show">
                        <?= $_SESSION['msg']['error'] ?>
                        <button class="close" data-dismiss="alert">&times;</button>
                    </div>
                    <div class="col-sm-3"></div>
                </div>
            <?php endif ?>
            <!-- エラーメッセージ ここまで -->

            <!-- 入力フォーム -->
            <div class="row my-2">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <form action="./entry_action.php" method="post">
                        <div class="form-group">
                            <label for="item_name">項目名</label>
                            <input type="text" class="form-control" id="item_name" name="item_name">
                        </div>
                        <div class="form-group">
                            <label for="user_id">担当者</label>
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="">--選択してください--</option>
                                <?php foreach ($users as $user) : ?>
                                    <option value="<?= $user['id'] ?>" <?php if ($user['id'] == $userId) echo ' selected'; ?>><?= $user['family_name'] . $user['first_name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="expire_date">期限</label>
                            <input type="date" class="form-control" id="expire_date" name="expire_date">
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="finished" name="finished">
                            <label for="finished">完了</label>
                        </div>

                        <input type="submit" value="登録" class="btn btn-primary">
                        <input type="button" value="キャンセル" class="btn btn-outline-primary" onclick="location.href='./';">
                    </form>
                </div>
                <div class="col-sm-3"></div>
            </div>
            <!-- 入力フォーム ここまで -->

        </div>
        <!-- コンテナ ここまで -->

        <!-- 必要なJavascriptを読み込む -->
        <script src="../js/jquery-3.4.1.min.js"></script>
        <script src="../js/bootstrap.bundle.min.js"></script>

</body>

</html>