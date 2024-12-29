<?php
// db.php: Database connection file

// Database configuration
$host = 'localhost';       // 資料庫伺服器
$dbname = 'sports_reservation'; // 資料庫名稱
$username = 'root';        // 資料庫使用者名稱（預設為 root）
$password = '';            // 資料庫密碼（根據您的設定填寫）

try {
    // 建立資料庫連線 (PDO)
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // 設定 PDO 錯誤模式為例外
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 成功連線訊息 (僅供測試用，正式環境可移除)
    // echo "資料庫連線成功！";

} catch (PDOException $e) {
    // 錯誤處理
    die("資料庫連線失敗：" . $e->getMessage());
}
?>

