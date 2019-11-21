<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/classes/model/BaseModel.php');

/**
 * ユーザーモデルクラスです。
 */
class UsersModel extends BaseModel
{
    /**
     * コンストラクタです。
     */
    public function __construct() {
        // 親クラスのコンストラクタを呼び出す
        parent::__construct();
    }

    /**
     * すべてのユーザーの情報を取得します。
     *
     * @return array ユーザーのレコードの配列
     */
    public function getUserAll() {
        $sql = '';
        $sql .= 'select ';
        $sql .= 'id,';
        $sql .= 'user,';
        $sql .= 'pass,';
        $sql .= 'family_name,';
        $sql .= 'first_name,';
        $sql .= 'is_admin ';
        $sql .= 'from users ';
        $sql .= 'where is_deleted=0 ';  // 論理削除されているユーザーログイン対象外
        $sql .= 'order by id';

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ユーザーを検索して、ユーザーの情報を取得します。
     *
     * @param string $user ユーザー名
     * @param striong $password パスワード
     * @return array ユーザー情報の配列（該当のユーザーが見つからないときは空の配列）
     */
    public function getUser($user, $password) {
        // $userが空だったら、空の配列を返却
        if (empty($user)) {
            return array();
        }

        $sql = '';
        $sql .= 'select ';
        $sql .= 'id,';
        $sql .= 'user,';
        $sql .= 'pass,';
        $sql .= 'family_name,';
        $sql .= 'first_name,';
        $sql .= 'is_admin ';
        $sql .= 'from users ';
        $sql .= 'where is_deleted=0 ';  // 論理削除されているユーザーはログイン対象外
        $sql .= 'and user=:user';

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->execute();

        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        // 検索結果が0件のときは、空の配列を返却
        if (!$rec) {
            return array();
        }

        // パスワードの妥当性チェックを行い、妥当性がないときは空の配列を返却
        // password_verify()については、
        // https://www.php.net/manual/ja/function.password-verify.php
        // 参照。
        if (!password_verify($password, $rec['pass'])) {
            return array();
        }

        // パスワードの情報は削除する→不要な情報は保持しない（セキュリティ対策）
        unset($rec['pass']);

        return $rec;
    }

    /**
     * 指定IDのユーザーが存在するかどうか調べます。
     *
     * @param int $id ユーザーID
     * @return boolean ユーザーが存在するとき：true、ユーザーが存在しないとき：false
     */
    public function isExistsUser($id) {
        // ＄idが数字でなかったら、falseを返却
        if (!is_numeric($id)) {
            return false;
        }

        // $idが0以下はありえないので、falseを返却
        if ($id <= 0) {
            return false;
        }

        $sql = '';
        $sql .= 'select count(id) as num from users where is_deleted=0';
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);

        // レコードの数が0だったらfalseを返却
        if ($ret['num'] == 0) {
            return false;
        }

        return true;
    }
}