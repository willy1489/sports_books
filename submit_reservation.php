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

// 當表單提交後插入資料
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

    // SQL 插入語句
    $sql = "INSERT INTO reservation_records (name, student_id, class, reservation_time, return_date, equipment) 
            VALUES ('$name', '$student_id', '$class', '$reservation_time', '$return_date', '$equipment_list')";

    if ($conn->query($sql) === TRUE) {
        echo "預約成功！<br>";

        // 顯示預約紀錄
        $last_id = $conn->insert_id;
        $show_sql = "SELECT * FROM reservation_records WHERE id = $last_id";
        $result = $conn->query($show_sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "預約ID: " . $row["id"]. "<br>";
                echo "學生姓名: " . $row["name"]. "<br>";
                echo "學號: " . $row["student_id"]. "<br>";
                echo "班級: " . $row["class"]. "<br>";
                echo "預約時間: " . $row["reservation_time"]. "<br>";
                echo "退還時間: " . $row["return_date"]. "<br>";
                echo "運動器材: " . $row["equipment"]. "<br>";
                echo "<a href='delete_reservation.php?id=" . $row["id"] . "'>刪除預約</a><br>";
                echo "<a href='edit_reservation.php?id=" . $row["id"] . "'>修改預約</a><br>";
            }
        } else {
            echo "沒有找到預約紀錄";
        }
    } else {
        echo "錯誤: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>



