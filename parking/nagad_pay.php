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
<title>Pay with Nagad — UniPark</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#FFF8F0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{background:#fff;border-radius:20px;box-shadow:0 8px 40px rgba(247,148,29,.2);max-width:400px;width:100%;overflow:hidden}
.card-top{background:linear-gradient(135deg,#F7941D,#e07b0a);padding:28px 24px 24px;text-align:center}
.nagad-logo{font-size:28px;font-weight:700;color:#fff;letter-spacing:-1px}
.card-sub{color:rgba(255,255,255,.85);font-size:13px;margin-top:4px}
.card-body{padding:28px 24px}
.amount-box{background:#FFF8F0;border:2px solid #fcd49a;border-radius:12px;padding:16px;text-align:center;margin-bottom:22px}
.amount-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#F7941D;margin-bottom:4px}
.amount-value{font-size:36px;font-weight:700;color:#F7941D}
.amount-sub{font-size:12px;color:#9CA3AF;margin-top:2px}
label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6B7280;margin-bottom:6px;margin-top:16px}
input{width:100%;padding:12px 14px;border:1.5px solid #E5E7EB;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:15px;font-weight:600;outline:none;transition:border-color .15s}
input:focus{border-color:#F7941D;box-shadow:0 0 0 3px rgba(247,148,29,.1)}
.pin-note{font-size:11px;color:#9CA3AF;margin-top:6px}
.btn{width:100%;padding:14px;background:#F7941D;color:#fff;border:none;border-radius:12px;font-family:'Plus Jakarta Sans',sans-serif;font-size:15px;font-weight:700;cursor:pointer;margin-top:20px;transition:background .15s}
.btn:hover{background:#d97e12}
.btn:disabled{background:#ccc;cursor:not-allowed}
.back{display:block;text-align:center;margin-top:14px;font-size:13px;color:#9CA3AF;text-decoration:none}
.back:hover{color:#F7941D}
.sandbox-notice{background:#FFF8E1;border:1px solid #F59E0B;border-radius:8px;padding:10px 14px;font-size:11px;color:#92400E;text-align:center;margin-bottom:16px}
</style>
</head>
<body>
<div class="card">
    <div class="card-top">
        <div class="nagad-logo">নগদ Nagad</div>
        <div class="card-sub">Secure Digital Payment</div>
    </div>
    <div class="card-body">
        <div class="sandbox-notice">🔧 Sandbox / Demo Mode — No real money charged</div>
        <div class="amount-box">
            <div class="amount-label">Parking Fee</div>
            <div class="amount-value">৳<?= $amount ?></div>
            <div class="amount-sub">For <?= htmlspecialchars($hrs) ?> hour(s) · UniPark</div>
        </div>

        <label>Nagad Account Number</label>
        <input type="tel" id="phone" placeholder="01XXXXXXXXX" maxlength="11">

        <div id="pin-section" style="display:none">
            <label>Nagad PIN</label>
            <input type="password" id="pin" placeholder="Enter your PIN" maxlength="4">
            <div class="pin-note">🔒 Your PIN is encrypted and never stored.</div>
        </div>

        <button class="btn" id="pay-btn" onclick="handlePay()">Pay with Nagad ৳<?= $amount ?></button>
        <a href="calculator.php" class="back">← Back to Calculator</a>
    </div>
</div>
<script>
let step = 1;
function handlePay() {
    const btn = document.getElementById('pay-btn');
    if (step === 1) {
        const phone = document.getElementById('phone').value;
        if (!/^01[3-9]\d{8}$/.test(phone)) { alert('Enter a valid Nagad number.'); return; }
        document.getElementById('pin-section').style.display = 'block';
        btn.textContent = 'Confirm Payment';
        step = 2;
    } else {
        const pin = document.getElementById('pin').value;
        if (pin.length < 4) { alert('Enter your 4-digit PIN.'); return; }
        btn.textContent = 'Processing...';
        btn.disabled = true;
        setTimeout(() => {
            window.location.href = 'payment_success.php?method=Nagad&amount=<?= $amount ?>&txid=NG' + Date.now();
        }, 2000);
    }
}
</script>
</body>
</html>