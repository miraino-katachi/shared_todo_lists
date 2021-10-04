<?php

namespace App\Model;

/**
 * 基本モデルクラスです。
 */
class Base
{
    /** @var \PDO PDOクラスインスタンス */
    private static $pdo;

    /**
     * PDOクラスのインスタンスを生成して返却します。
     *
     * @return \PDO PDOクラスのインスタンス
     */
    public static function getInstance()
    {
        // インスタンスが生成されていなかったら、新しく生成します。
        // すでに生成済みであれば、生成済みのインスタンスを返却します。
        if (!isset(self::$pdo)) {
            // namespaceを設定しているので、PHPの組み込みクラスは「\」をつけて呼び出す必要があります。
            self::$pdo = new \PDO(DSN, DB_USER, DB_PASS);
            // エラーモードを例外にします。
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            // カラム名をキーとした連想配列としてレコードを取得します。
            self::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        }
        return self::$pdo;
    }

    /**
     * トランザクションを開始します。
     */
    public static function begin()
    {
        self::$pdo->beginTransaction();
    }

    /**
     * トランザクションをコミットします。
     */
    public static function commit()
    {
        self::$pdo->commit();
    }

    /**
     * トランザクションをロールバックします。
     */
    public static function rollback()
    {
        self::$pdo->rollback();
    }
}
