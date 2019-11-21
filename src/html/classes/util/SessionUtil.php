<?php

/**
 * セッション関連ユーティリティクラスです。
 */
class SessionUtil
{
    /**
     * セッションスタートします。
     *
     * @return void
     */
    public static function sessionStart() {
        session_start();
        session_regenerate_id(true);
    }


}