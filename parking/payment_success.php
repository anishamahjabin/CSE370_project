<?php
include 'db.php';
if (!isset($_SESSION['uid'])) { header("Location: login.php"); exit; }
$method = htmlspecialchars($_GET['method'] ?? 'Unknown');
$amount = (int)($_GET['amount'] ?? 0);
$txid   = htmlspecialchars($_GET['txid'] ?? 'N/A');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Success — UniPark</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#ECFDF5;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{background:#fff;border-radius:20px;box-shadow:0 8px 40px rgba(16,185,129,.15);max-width:420px;width:100%;padding:40px 32px;text-align:center}
.check{width:72px;height:72px;background:#D1FAE5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:36px}
h1{font-size:22px;font-weight:700;color:#111827;margin-bottom:8px}
p{font-size:14px;color:#6B7280;margin-bottom:24px}
.info-box{background:#F9FAFB;border:1px solid #E5E7EB;border-radius:12px;padding:16px;text-align:left;margin-bottom:24px}
.info-row{display:flex;justify-content:space-between;font-size:13px;padding:6px 0;border-bottom:1px solid #F3F4F6}
.info-row:last-child{border-bottom:none;font-weight:700;color:#10B981}
.info-row span:first-child{color:#6B7280}
.btn{display:block;background:#2563EB;color:#fff;padding:14px;border-radius:12px;text-decoration:none;font-weight:700;font-size:14px}
.btn:hover{background:#1d4ed8}
</style>
</head>
<body>
<div class="card">
    <div class="check">✅</div>
    <h1>Payment Successful!</h1>
    <p>Your parking fee has been paid via <?= $method ?>.</p>
    <div class="info-box">
        <div class="info-row"><span>Method</span><span><?= $method ?></span></div>
        <div class="info-row"><span>Transaction ID</span><span><?= $txid ?></span></div>
        <div class="info-row"><span>Amount Paid</span><span>৳<?= $amount ?> BDT</span></div>
        <div class="info-row"><span>Status</span><span>✔ Confirmed</span></div>
    </div>
    <a href="dashboard.php" class="btn">Back to Dashboard</a>
</div>
</body>
</html>