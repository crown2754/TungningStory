<?php
// 這是最標準的產出方式，產出的格式完全符合你的範例
$target_password = 'gkGary@1234';
$options = ['cost' => 12];
$hash = password_hash($target_password, PASSWORD_BCRYPT, $options);

echo $hash;
?>