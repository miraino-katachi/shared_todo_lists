<?php

namespace App\Util;

use App\Model\Base;
use App\Model\Users;

/**
 * バリデーションユーティリティクラスです。
 */
class Validation
{

    /**
     * 正しい日付形式の文字列かどうかを判定します。
     *
     * @param string $date 日付形式の文字列
     * @return boolean 正しいとき：true、正しくないとき：false
     */
    public static function isDate($date)
    {
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
    public static function isValidItemName($itemName)
    {
        if (strlen($itemName) > 100) {
            return false;
        }
        return true;
    }

    /**
     * 指定IDのユーザーが存在するかどうか判定します。
     *
     * @param int $user_id ユーザーID
     * @return boolean
     */
    public static function isValidUserId($user_id)
    {
        // $user_idが数字でなかったら、falseを返却
        if (!is_numeric($user_id)) {
            return false;
        }

        // $user_idが0以下はありえないので、falseを返却
        if ($user_id <= 0) {
            return false;
        }

        // UserクラスのisExistUser()メソッドを使って、該当のユーザーを検索した結果を返却
        $base = Base::getInstance();
        $db = new Users($base);
        return $db->isExistsUser($user_id);
    }
}
