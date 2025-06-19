<?php
$servername = "localhost";  // 服务器地址
$username = "root";        // 默认用户名
$password = "";            // 默认密码为空
$dbname = "biblioteca";    // 你的数据库名称

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
?>
