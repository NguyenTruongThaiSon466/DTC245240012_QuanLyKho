<?php
$host = 'db'; // Tên service database trong docker-compose
$user = 'inventory_user';
$pass = 'UserPassword_Secure_123!';
$dbname = 'inventory_db';

// Kết nối Database
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Tự động tạo bảng sản phẩm nếu chưa có
$sql_create = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL
)";
$conn->query($sql_create);

// Xử lý thêm sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $stmt = $conn->prepare("INSERT INTO products (name, quantity, price) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $name, $quantity, $price);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ Thống Quản Lý Kho</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Hệ Thống Quản Lý Kho Hàng</h1>
    
    <h2>Thêm Sản Phẩm Mới</h2>
    <form method="POST">
        Tên sản phẩm: <input type="text" name="name" required><br><br>
        Số lượng: <input type="number" name="quantity" required><br><br>
        Giá: <input type="number" step="0.01" name="price" required><br><br>
        <button type="submit">Thêm</button>
    </form>

    <h2>Danh Sách Sản Phẩm</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Tên Sản Phẩm</th>
            <th>Số Lượng</th>
            <th>Giá</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM products");
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['price']}</td>
                  </tr>";
        }
        ?>
    </table>
</body>
</html>
