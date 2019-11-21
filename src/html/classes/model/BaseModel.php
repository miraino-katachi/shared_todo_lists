<?php

/**
 * 基本モデルクラスです。
 */
class BaseModel {

    // クラス定数にデータベースへの接続情報を登録することで、
    // データベースに接続する必要があるPHPファイルで、個別に設定する必要がなくなります。

    /** @var string データベース接続ユーザー名 */
    protected const DB_USER = "root";

    /** @var string データベース接続パスワード */
    protected const DB_PASS = "root";

    /** @var string データベースホスト名 */
    protected const DB_HOST = "localhost";

    /** @var string データベース名 */
    protected const DB_NAME = "todo";

    /** @var object PDOインスタンス */
    protected $dbh;

    /**
     * コンストラクタ
     */
    public function __construct() {
        $dsn = 'mysql:dbname='.self::DB_NAME.';host='.self::DB_HOST.';charset=utf8';
        $this->dbh = new PDO($dsn, self::DB_USER, self::DB_PASS);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // データベースへの接続が失敗した場合、PDOExceptionがthrowされます。
        // https://www.php.net/manual/ja/pdo.construct.php
        // Databaseクラスのインスタンスを生成するときに、例外の内容を取得できます。
        //
        // 下記のコード参照
        // try {
        //     $db = new database();
        // } catch (Exception $e) {
        //     var_dump($e);    // 例外の内容をデバッグするときは、var_dump()関数を利用するといいでしょう。
        // }
        //
        // または
        // try {
        //     $db = new database();
        // } catch (PDOException $e) {
        //     var_dump($e);
        // } catch (Exception $e) {
        //     var_dump($e);
        // }
    }

    /**
     * トランザクションを開始します。
     */
    public function begin() {
        $this->dbh->beginTransaction();
    }

    /**
     * トランザクションをコミットします。
     */
    public function commit() {
        $this->dbh->commit();
    }

    /**
     * トランザクションをロールバックします。
     */
    public function rollback() {
        $this->dbh->rollback();
    }
}