<?php

/**
 * バリデーションユーティリティクラスです。
 */
class ValidationUtil {

    /**
     * 正しい日付形式の文字列かどうかを判定します。
     *
     * @param string $date 日付形式の文字列
     * @return boolean 正しいとき：true、正しくないとき：false
     */
    public static function isDate($date) {
        // strtotime()関数を使って、タイムスタンプに変換できるかどうかで正しい日付かどうかを調べます。
        // https://www.php.net/manual/ja/function.strtotime.php
        // 参照
        return strtotime($date) == false ? false : true;
    }

    /**
     * 項目名の長さ（文字数）が正しいかどうかを判定します。
     *
     * @param string $itemName 項目名
     * @return boolean 正しいとき：true、正しくないとき：false
     */
    public static function isValidItemName($itemName) {
        if (strlen($itemName) > 100) {
            return false;
        }
        return true;
    }

    /**
     * 指定IDのユーザーが存在するかどうか判定します。
     *
     * @param int $userId ユーザーID
     * @return boolean
     */
    public static function isValidUserId($userId) {
        // $userIdが数字でなかったら、falseを返却
        if (!is_numeric($userId)) {
            return false;
        }

        // $userIdが0以下はありえないので、falseを返却
        if ($userId <= 0) {
            return false;
        }

        // UserModelクラスのisExistUser()メソッドを使って、該当のユーザーを検索した結果を返却
        require_once($_SERVER['DOCUMENT_ROOT'].'/classes/model/UsersModel.php');
        $db = new UsersModel();
        return $db->isExistsUser($userId);
    }
}