insert into `users` (
    `user`,
    `pass`,
    `family_name`,
    `first_name`
)
values
(
    'test1',
    '$2y$10$eSePpwz2hteTQZNXO1BvFeI.VCSGF/YqGdpZda/sHQDQWzAJoehYi', -- パスワード test1
    'テスト',
    '花子'
),
(
    'test2',
    '$2y$10$btIzYtozzeEJ2J53ZU/Qz.YBK61RilXtGcVJkrZfz1r/fS8R72F.i', -- パスワード test2
    'テスト',
    '太郎'
);
