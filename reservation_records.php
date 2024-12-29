
<?php
require_once 'db.php';

// 連接資料庫//預約
$servername = "localhost";
$username = "root";  // 請根據你的資料庫帳號設定
$password = "";      // 請根據你的資料庫密碼設定
$dbname = "sports_reservation";

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 刪除預約紀錄
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM reservation_records WHERE id = $delete_id";

    if ($conn->query($delete_sql) === TRUE) {
        echo "預約紀錄已刪除！<br>";
    } else {
        echo "錯誤: " . $conn->error;
    }
}

// 獲取所有預約紀錄
$sql = "SELECT * FROM reservation_records";
$result = $conn->query($sql);

$conn->close();
?>

<!-- 記錄頁面 -->
<h2 class="page-title">所有預約紀錄</h2>

<?php if ($result->num_rows > 0): ?>
    <table class="reservation-table">
        <thead>
            <tr>
                <th>學生姓名</th>
                <th>學號</th>
                <th>班級</th>
                <th>預約時間</th>
                <th>退還時間</th>
                <th>運動器材</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['student_id']); ?></td>
                    <td><?= htmlspecialchars($row['class']); ?></td>
                    <td><?= htmlspecialchars($row['reservation_time']); ?></td>
                    <td><?= htmlspecialchars($row['return_date']); ?></td>
                    <td><?= htmlspecialchars($row['equipment']); ?></td>
                    <td>
                        <a href="edit_reservation.php?id=<?= $row['id']; ?>" class="edit-btn">修改</a>
                        <a href="?delete_id=<?= $row['id']; ?>" class="delete-btn" onclick="return confirm('確定要刪除這個預約嗎？')">刪除</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>目前沒有預約紀錄。</p>
<?php endif; ?>

<!-- CSS 排版 -->
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fb;
        margin: 0;
        padding: 20px;
    }

    .page-title {
        text-align: center;
        color: #333;
        font-size: 28px;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .reservation-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .reservation-table th, .reservation-table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
        font-size: 16px;
    }

    .reservation-table th {
        background-color: #f4f7fb;
        color: #333;
    }

    .reservation-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .edit-btn, .delete-btn {
        padding: 8px 15px;
        margin: 5px;
        text-decoration: none;
        color: white;
        font-weight: bold;
        border-radius: 4px;
    }

    .edit-btn {
        background-color: #4CAF50;
    }

    .delete-btn {
        background-color: #f44336;
    }

    .edit-btn:hover {
        background-color: #45a049;
    }

    .delete-btn:hover {
        background-color: #e53935;
    }
</style>
