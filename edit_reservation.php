<?php
require_once 'db.php';

// 連接資料庫
$servername = "localhost";
$username = "root";  // 請根據你的資料庫帳號設定
$password = "";      // 請根據你的資料庫密碼設定
$dbname = "sports_reservation";

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 獲取預約ID並顯示預約資訊
if (isset($_GET['id'])) {
    $reservation_id = $_GET['id'];
    $sql = "SELECT * FROM reservation_records WHERE id = $reservation_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "預約紀錄未找到";
        exit;
    }
}

// 如果表單提交，更新資料
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $student_id = $_POST['student_id'];
    $class = $_POST['class'];
    $reservation_time = $_POST['reservation_time'];
    $return_date = $_POST['return_date'];

    // 處理設備數量（如果數量為 0，則不儲存）
    $equipment = [];
    foreach ($_POST['equipment'] as $key => $value) {
        if ($value > 0) {
            $equipment[] = "$key: $value";
        }
    }
    $equipment_list = implode(", ", $equipment); // 將選擇的運動器材及數量轉為字串

    // SQL 更新語句
    $update_sql = "UPDATE reservation_records 
                   SET name='$name', student_id='$student_id', class='$class', reservation_time='$reservation_time', 
                   return_date='$return_date', equipment='$equipment_list'
                   WHERE id=$reservation_id";
    
    if ($conn->query($update_sql) === TRUE) {
        echo "預約已更新！<br>";
        echo "<a href='index.php'></a>";
    } else {
        echo "錯誤: " . $conn->error;
    }
}

$conn->close();
?>

<!-- 表單用於修改預約 -->
<h2 class="form-title">修改預約</h2>
<form method="post" action="" class="reservation-form">
    <label for="name">學生姓名</label>
    <input type="text" id="name" name="name" value="<?= htmlspecialchars($row['name']); ?>" required>

    <label for="student_id">學號</label>
    <input type="text" id="student_id" name="student_id" value="<?= htmlspecialchars($row['student_id']); ?>" required>

    <label for="class">班級</label>
    <input type="text" id="class" name="class" value="<?= htmlspecialchars($row['class']); ?>" required>

    <label for="reservation_time">預約時間</label>
    <input type="datetime-local" id="reservation_time" name="reservation_time" value="<?= htmlspecialchars($row['reservation_time']); ?>" required>

    <label for="return_date">退還時間</label>
    <input type="datetime-local" id="return_date" name="return_date" value="<?= htmlspecialchars($row['return_date']); ?>" required>

    <label for="equipment">選擇運動器材及數量</label>
    <div class="equipment-container">
        <!-- 足球 -->
        <div class="equipment-item">
            <label for="soccer">足球</label>
            <input type="number" id="soccer" name="equipment[soccer]" value="<?= strpos($row['equipment'], '足球') !== false ? explode(': ', explode('足球', $row['equipment'])[1])[0] : 0; ?>" min="0">
        </div>

        <!-- 籃球 -->
        <div class="equipment-item">
            <label for="basketball">籃球</label>
            <input type="number" id="basketball" name="equipment[basketball]" value="<?= strpos($row['equipment'], '籃球') !== false ? explode(': ', explode('籃球', $row['equipment'])[1])[0] : 0; ?>" min="0">
        </div>

        <!-- 羽毛球 -->
        <div class="equipment-item">
            <label for="badminton">羽毛球</label>
            <input type="number" id="badminton" name="equipment[badminton]" value="<?= strpos($row['equipment'], '羽毛球') !== false ? explode(': ', explode('羽毛球', $row['equipment'])[1])[0] : 0; ?>" min="0">
        </div>

        <!-- 網球 -->
        <div class="equipment-item">
            <label for="tennis">網球</label>
            <input type="number" id="tennis" name="equipment[tennis]" value="<?= strpos($row['equipment'], '網球') !== false ? explode(': ', explode('網球', $row['equipment'])[1])[0] : 0; ?>" min="0">
        </div>

        <!-- 乒乓球 -->
        <div class="equipment-item">
            <label for="pingpong">乒乓球</label>
            <input type="number" id="pingpong" name="equipment[pingpong]" value="<?= strpos($row['equipment'], '乒乓球') !== false ? explode(': ', explode('乒乓球', $row['equipment'])[1])[0] : 0; ?>" min="0">
        </div>
    </div>
    
    <button type="submit" class="submit-btn">更新預約</button>
</form>

<!-- CSS for styling -->
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fb;
        margin: 0;
        padding: 20px;
    }

    .form-title {
        text-align: center;
        color: #333;
        font-size: 28px;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .reservation-form {
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    .reservation-form label {
        display: block;
        font-size: 16px;
        color: #333;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .reservation-form input[type="text"],
    .reservation-form input[type="datetime-local"],
    .reservation-form input[type="number"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
        background-color: #f9f9f9;
    }

    .equipment-container {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .equipment-item {
        flex: 1;
        min-width: 200px;
    }

    .reservation-form button[type="submit"] {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 14px 20px;
        font-size: 18px;
        cursor: pointer;
        border-radius: 4px;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    .reservation-form button[type="submit"]:hover {
        background-color: #45a049;
    }

    a {
        display: inline-block;
        margin-top: 20px;
        color: #4CAF50;
        font-size: 16px;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
