<?php

/**
 * 共通関数クラスです。
 */
class CommonUtil
{
    /**
     * POSTされたデータをサニタイズします。
     *
     * @param array $before サニタイズ前のPOST配列
     * @return array サニタイズ後のPOST配列
     */
    public static function sanitaize($before) {
        $after = array();
        foreach ($before as $k => $v) {
            $after[$k] = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
        }
        return $after;
    }
}