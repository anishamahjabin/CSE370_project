<?php
include 'db.php';
if (!isset($_SESSION['uid'])) { header("Location: login.php"); exit; }
$amount = isset($_GET['amount']) ? (int)$_GET['amount'] : 0;
$hrs    = isset($_GET['hrs'])    ? $_GET['hrs']          : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pay with bKash — UniPark</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#fff0f5;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{background:#fff;border-radius:20px;box-shadow:0 8px 40px rgba(209,32,83,.15);max-width:400px;width:100%;overflow:hidden}
.card-top{background:#D12053;padding:28px 24px 24px;text-align:center}
.bkash-logo{font-size:28px;font-weight:700;color:#fff;letter-spacing:-1px}
.bkash-logo span{font-weight:300}
.card-sub{color:rgba(255,255,255,.8);font-size:13px;margin-top:4px}
.card-body{padding:28px 24px}
.amount-box{background:#fff0f5;border:2px solid #f9c0d0;border-radius:12px;padding:16px;text-align:center;margin-bottom:22px}
.amount-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#D12053;margin-bottom:4px}
.amount-value{font-size:36px;font-weight:700;color:#D12053}
.amount-sub{font-size:12px;color:#9CA3AF;margin-top:2px}
label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6B7280;margin-bottom:6px;margin-top:16px}
input{width:100%;padding:12px 14px;border:1.5px solid #E5E7EB;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:15px;font-weight:600;outline:none;transition:border-color .15s}
input:focus{border-color:#D12053;box-shadow:0 0 0 3px rgba(209,32,83,.1)}
.otp-note{font-size:11px;color:#9CA3AF;margin-top:6px}
.btn{width:100%;padding:14px;background:#D12053;color:#fff;border:none;border-radius:12px;font-family:'Plus Jakarta Sans',sans-serif;font-size:15px;font-weight:700;cursor:pointer;margin-top:20px;transition:background .15s}
.btn:hover{background:#b01a46}
.btn:disabled{background:#ccc;cursor:not-allowed}
.back{display:block;text-align:center;margin-top:14px;font-size:13px;color:#9CA3AF;text-decoration:none}
.back:hover{color:#D12053}
.spinner{display:none;border:3px solid rgba(255,255,255,.3);border-top:3px solid #fff;border-radius:50%;width:18px;height:18px;animation:spin .7s linear infinite;margin:0 auto}
@keyframes spin{to{transform:rotate(360deg)}}
/* SANDBOX NOTICE */
.sandbox-notice{background:#FFF8E1;border:1px solid #F59E0B;border-radius:8px;padding:10px 14px;font-size:11px;color:#92400E;text-align:center;margin-bottom:16px}
</style>
</head>
<body>
<div class="card">
    <div class="card-top">
        <div class="bkash-logo">b<span>K</span>ash</div>
        <div class="card-sub">Secure Mobile Payment</div>
    </div>
    <div class="card-body">
        <div class="sandbox-notice">🔧 Sandbox / Demo Mode — No real money charged</div>
        <div class="amount-box">
            <div class="amount-label">Parking Fee</div>
            <div class="amount-value">৳<?= $amount ?></div>
            <div class="amount-sub">For <?= htmlspecialchars($hrs) ?> hour(s) · UniPark</div>
        </div>

        <label>bKash Account Number</label>
        <input type="tel" id="phone" placeholder="01XXXXXXXXX" maxlength="11">

        <div id="otp-section" style="display:none">
            <label>OTP (Verification Code)</label>
            <input type="text" id="otp" placeholder="Enter 6-digit OTP" maxlength="6">
            <div class="otp-note">📱 An OTP has been sent to your bKash number.</div>
        </div>

        <button class="btn" id="pay-btn" onclick="handlePay()">Confirm &amp; Pay ৳<?= $amount ?></button>
        <a href="calculator.php" class="back">← Back to Calculator</a>
    </div>
</div>

<script>
let step = 1;
function handlePay() {
    const btn = document.getElementById('pay-btn');
    if (step === 1) {
        const phone = document.getElementById('phone').value;
        if (!/^01[3-9]\d{8}$/.test(phone)) { alert('Enter a valid bKash number.'); return; }
        document.getElementById('otp-section').style.display = 'block';
        btn.textContent = 'Verify & Complete Payment';
        step = 2;
    } else {
        const otp = document.getElementById('otp').value;
        if (otp.length !== 6) { alert('Enter the 6-digit OTP.'); return; }
        btn.textContent = 'Processing...';
        btn.disabled = true;
        // Simulate sandbox payment success
        setTimeout(() => {
            window.location.href = 'payment_success.php?method=bKash&amount=<?= $amount ?>&txid=BK' + Date.now();
        }, 2000);
    }
}
</script>
</body>
</html>