<?php
require_once 'db.php';

// 引入資料庫連線檔案
require_once 'db.php';

// 獲取預約ID並刪除
if (isset($_GET['id'])) {
    $reservation_id = intval($_GET['id']); // 確保ID為整數，避免SQL注入

    // SQL 刪除語句
    $sql = "DELETE FROM reservation_records WHERE id = ?";
    $stmt = $conn->prepare($sql); // 使用預處理語句增加安全性
    $stmt->bind_param("i", $reservation_id);

    if ($stmt->execute()) {
        echo "預約已刪除！<br>";
        echo "<a href='index.php'>返回首頁</a>";
    } else {
        echo "錯誤: " . $stmt->error;
    }

    $stmt->close(); // 關閉預處理語句
} else {
    echo "未提供有效的ID。";
}

$conn->close(); // 關閉資料庫連線
?>
