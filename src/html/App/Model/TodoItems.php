<?php

namespace App\Model;

/**
 * 作業項目モデルクラスです。
 */
class TodoItems
{
    /** @var \PDO $pdo PDOクラスインスタンス */
    private $pdo;

    /**
     * コンストラクタです。
     *
     * @param \PDO $pdo \PDOクラスインスタンス
     */
    public function __construct($pdo) {
        // 引数に指定されたPDOクラスのインスタンスをプロパティに代入します。
        // クラスのインスタンスは別の変数に代入されても同じものとして扱われます。（複製されるわけではありません）
        $this->pdo = $pdo;
    }

    /**
     * 作業項目を全件取得します。（削除済みの作業項目は含みません）
     *
     * @return array 作業項目の配列
     */
    public function getTodoItemAll() {
        $sql = '';
        $sql .= 'select ';
        $sql .= 't.id,';
        $sql .= 't.user_id,';
        $sql .= 'u.family_name,';
        $sql .= 'u.first_name,';
        $sql .= 't.item_name,';
        $sql .= 't.registration_date,';
        $sql .= 't.expire_date,';
        $sql .= 't.finished_date ';
        $sql .= 'from todo_items t ';
        $sql .= 'inner join users u on t.user_id=u.id ';
        $sql .= 'where t.is_deleted=0 ';        // 論理削除されている作業項目は表示対象外
        $sql .= 'order by t.expire_date asc';   // 期限日の順番に並べる

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $ret = $stmt->fetchAll();

        return $ret;
    }

    /**
     * 作業項目を検索条件で抽出して取得します。（削除済みの作業項目は含みません）
     *
     * @param mixed $search 検索キーワード
     * @return array 作業項目の配列
     */
    public function getTodoItemBySearch($search) {
        $sql = '';
        $sql .= 'select ';
        $sql .= 't.id,';
        $sql .= 't.user_id,';
        $sql .= 'u.family_name,';
        $sql .= 'u.first_name,';
        $sql .= 't.item_name,';
        $sql .= 't.registration_date,';
        $sql .= 't.expire_date,';
        $sql .= 't.finished_date ';
        $sql .= 'from todo_items t ';
        $sql .= 'inner join users u on t.user_id=u.id ';
        $sql .= 'where t.is_deleted=0 ';    // 論理削除されている作業項目は表示対象外
        $sql .= "and (";
        $sql .= "t.item_name like :item_name ";
        $sql .= "or u.family_name like :family_name ";
        $sql .= "or u.first_name like :first_name ";
        $sql .= "or t.registration_date=:registration_date ";
        $sql .= "or t.expire_date=:expire_date ";
        $sql .= "or t.finished_date=:finished_date";
        $sql .= ") ";
        $sql .= 'order by t.expire_date asc';

        // bindParam()の第2引数には値を直接入れることができないので
        // 下記のようにして、検索ワードを変数に入れる。
        $likeWord = "%$search%";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':item_name', $likeWord, \PDO::PARAM_STR);
        $stmt->bindParam(':family_name', $likeWord, \PDO::PARAM_STR);
        $stmt->bindParam(':first_name', $likeWord, \PDO::PARAM_STR);
        $stmt->bindParam(':registration_date', $search, \PDO::PARAM_STR);
        $stmt->bindParam(':expire_date', $search, \PDO::PARAM_STR);
        $stmt->bindParam(':finished_date', $search, \PDO::PARAM_STR);
        $stmt->execute();
        $ret = $stmt->fetchAll();

        return $ret;
    }

    /**
     * 指定IDの作業項目を1件取得します。（削除済みの作業項目は含みません）
     * @param int $id 作業項目のID番号
     * @return array 作業項目の配列
     */
    public function getTodoItemById($id) {
        // $idが数字でなかったら、falseを返却する。
        if (!is_numeric($id)) {
            return false;
        }

        // $idが0以下はありえないので、falseを返却
        if ($id <= 0) {
            return false;
        }

        $sql = '';
        $sql .= 'select ';
        $sql .= 't.id,';
        $sql .= 't.user_id,';
        $sql .= 'u.family_name,';
        $sql .= 'u.first_name,';
        $sql .= 't.item_name,';
        $sql .= 't.registration_date,';
        $sql .= 't.expire_date,';
        $sql .= 't.finished_date ';
        $sql .= 'from todo_items t ';
        $sql .= 'inner join users u on t.user_id=u.id ';
        $sql .= 'where t.id=:id ';
        $sql .= 'and t.is_deleted=0';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetch();

        return $ret;
    }

    /**
     * 作業項目を1件登録します。
     *
     * @param array $data 作業項目の連想配列
     * @return bool 成功した場合:TRUE、失敗した場合:FALSE
     */
    public function registerTodoItem($data) {
        // テーブルの構造でデフォルト値が設定されているカラムをinsert文で指定する必要はありません（特に理由がない限り）。
        $sql = '';
        $sql .= 'insert into todo_items (';
        $sql .= 'user_id,';
        $sql .= 'item_name,';
        $sql .= 'registration_date,';
        $sql .= 'expire_date,';
        $sql .= 'finished_date';
        $sql .= ') values (';
        $sql .= ':user_id,';
        $sql .= ':item_name,';
        $sql .= ':registration_date,';
        $sql .= ':expire_date,';
        $sql .= ':finished_date';
        $sql .= ')';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $data['user_id'], \PDO::PARAM_INT);
        $stmt->bindParam(':item_name', $data['item_name'], \PDO::PARAM_STR);
        $stmt->bindParam(':registration_date', $data['registration_date'], \PDO::PARAM_STR);
        $stmt->bindParam(':expire_date', $data['expire_date'], \PDO::PARAM_STR);
        $stmt->bindParam(':finished_date', $data['finished_date'], \PDO::PARAM_STR);
        $ret = $stmt->execute();

        return $ret;
    }

    /**
     * 指定IDの1件の作業項目を更新ます。
     *
     * @param array $data 更新する作業項目の連想配列
     * @return bool 成功した場合:TRUE、失敗した場合:FALSE
     */
    public function updateTodoItemById($data) {
        // $data['id']が存在しなかったら、falseを返却
        if (!isset($data['id'])) {
            return false;
        }

        // $data['id']が数字でなかったら、falseを返却する。
        if (!is_numeric($data['id'])) {
            return false;
        }

        // $data['id']が0以下はありえないので、falseを返却
        if ($data['id'] <= 0) {
            return false;
        }

        $sql = '';
        $sql .= 'update todo_items set ';
        $sql .= 'user_id=:user_id,';
        $sql .= 'item_name=:item_name,';
        $sql .= 'registration_date=:registration_date,';
        $sql .= 'expire_date=:expire_date,';
        $sql .= 'finished_date=:finished_date,';
        $sql .= 'is_deleted=:is_deleted ';  // 現状の仕様では「削除フラグ」をアップデートする必要はないが、今後の仕様追加のために実装しておく。
        $sql .= 'where id=:id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $data['user_id'], \PDO::PARAM_INT);
        $stmt->bindParam(':item_name', $data['item_name'], \PDO::PARAM_STR);
        $stmt->bindParam(':registration_date', $data['registration_date'], \PDO::PARAM_STR);
        $stmt->bindParam(':expire_date', $data['expire_date'], \PDO::PARAM_STR);
        $stmt->bindParam(':finished_date', $data['finished_date'], \PDO::PARAM_STR);
        $stmt->bindParam(':is_deleted', $data['is_deleted'], \PDO::PARAM_INT);
        $stmt->bindParam(':id', $data['id'], \PDO::PARAM_INT);
        $ret = $stmt->execute();

        return $ret;
    }

    /**
     * 指定IDの1件の作業項目を完了にします。
     *
     * @param int $id 作業項目ID
     * @param string $date 完了日（NULLの場合は今日の日付）
     * @return bool 成功した場合:TRUE、失敗した場合:FALSE
     */
    public function makeTodoItemComplete($id, $date = null) {
        // $idが数字でなかったら、falseを返却する。
        if (!is_numeric($id)) {
            return false;
        }

        // $idが0以下はありえないので、falseを返却
        if ($id <= 0) {
            return false;
        }

        // $dateがnullだったら、今日の日付を設定する。
        // date()については、
        // https://www.php.net/manual/ja/function.date.php
        // を参照
        if (is_null($date)) {
            $date = date('Y-m-d');
        }

        $sql = '';
        $sql .= 'update todo_items set ';
        $sql .= 'finished_date=:finished_date ';
        $sql .= 'where id=:id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':finished_date', $date, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $ret = $stmt->execute();

        return $ret;
    }

    /**
     * 指定IDの1件の作業項目を論理削除します。
     *
     * @param int $id 作業項目ID
     * @return bool 成功した場合:TRUE、失敗した場合:FALSE
     */
    public function deleteTodoItemById($id) {
        // $idが数字でなかったら、falseを返却する。
        if (!is_numeric($id)) {
            return false;
        }

        // $idが0以下はありえないので、falseを返却
        if ($id <= 0) {
            return false;
        }

        $sql = '';
        $sql .= 'update todo_items set ';
        $sql .= 'is_deleted=1 ';
        $sql .= 'where id=:id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $ret = $stmt->execute();

        return $ret;
    }
}
