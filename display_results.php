<?php
// Bật gỡ lỗi để kiểm tra lỗi
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Kiểm tra nếu có dữ liệu POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $investment = filter_input(INPUT_POST, 'investment', FILTER_VALIDATE_FLOAT);
    $interest_rate = filter_input(INPUT_POST, 'interest_rate', FILTER_VALIDATE_FLOAT);
    $years = filter_input(INPUT_POST, 'years', FILTER_VALIDATE_INT);

    // Khởi tạo thông báo lỗi
    $error_message = '';

    // Xác thực investment
    if ($investment === FALSE) {
        $error_message = 'Investment must be a valid number.';
    } else if ($investment <= 0) {
        $error_message = 'Investment must be greater than zero.';
    // Xác thực interest rate
    } else if ($interest_rate === FALSE) {
        $error_message = 'Interest rate must be a valid number.';
    } else if ($interest_rate <= 0) {
        $error_message = 'Interest rate must be greater than zero.';
    } else if ($interest_rate > 15) {
        $error_message = 'Interest rate must be less than or equal to 15.';
    // Xác thực years
    } else if ($years === FALSE) {
        $error_message = 'Years must be a valid whole number.';
    } else if ($years <= 0) {
        $error_message = 'Years must be greater than zero.';
    } else if ($years > 30) {
        $error_message = 'Years must be less than 31.';
    }

    // Nếu không có lỗi, thực hiện tính toán
    if (empty($error_message)) {
        $future_value = $investment;
        for ($i = 1; $i <= $years; $i++) {
            $future_value += $future_value * $interest_rate * 0.01;
        }

        // Định dạng dữ liệu
        $investment_f = '$' . number_format($investment, 2);
        $yearly_rate_f = $interest_rate . '%';
        $future_value_f = '$' . number_format($future_value, 2);
    }
} else {
    // Nếu không phải POST, đặt giá trị mặc định hoặc thông báo
    $error_message = 'Please submit the form with valid data.';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Future Value Calculator</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <main>
        <h1>Future Value Calculator</h1>

        <?php if (!empty($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php else: ?>
            <label>Investment Amount:</label>
            <span><?php echo isset($investment_f) ? $investment_f : ''; ?></span><br>

            <label>Yearly Interest Rate:</label>
            <span><?php echo isset($yearly_rate_f) ? $yearly_rate_f : ''; ?></span><br>

            <label>Number of Years:</label>
            <span><?php echo isset($years) ? $years : ''; ?></span><br>

            <label>Future Value:</label>
            <span><?php echo isset($future_value_f) ? $future_value_f : ''; ?></span><br>
        <?php endif; ?>
    </main>
    <p>This calculation was done on <?php echo date('m/d/Y'); ?> at <?php echo date('h:i A T'); ?>.</p>

    <!-- Hiển thị form lại nếu có lỗi hoặc khi truy cập trực tiếp -->
    <?php if (!empty($error_message) || !isset($future_value_f)): ?>
        <?php include 'index.html'; ?>
    <?php endif; ?>
</body>
</html>