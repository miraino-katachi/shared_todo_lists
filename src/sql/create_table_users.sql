CREATE TABLE `users` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT 'ID',
  `user` varchar(50) NOT NULL COMMENT 'ログインユーザー名',
  `pass` varchar(255) NOT NULL COMMENT 'ログインパスワード',
  `family_name` varchar(50) NOT NULL COMMENT 'ユーザー姓',
  `first_name` varchar(50) NOT NULL COMMENT 'ユーザー名',
  `is_admin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '管理者権限',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  `create_date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登録日時',
  `update_date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='ユーザー';
