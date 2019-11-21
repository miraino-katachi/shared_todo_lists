CREATE TABLE `todo_items` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT 'ユーザーID',
  `item_name` varchar(100) DEFAULT NULL COMMENT '項目名',
  `registration_date` date DEFAULT NULL COMMENT '登録日',
  `expire_date` date DEFAULT NULL COMMENT '期限日',
  `finished_date` date DEFAULT NULL COMMENT '完了日',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  `create_date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登録日時',
  `update_date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='作業項目';

ALTER TABLE `todo_items`
  ADD KEY `IX_todo_items_user_id` (`user_id`);
