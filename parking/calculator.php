<?php
include 'db.php';
if (!isset($_SESSION['uid'])) { header("Location: login.php"); exit; }
$uid = $_SESSION['uid'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fee Calculator — UniPark</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F1F3F7;display:flex;min-height:100vh;font-size:14px;color:#111827}

/* SIDEBAR */
.sidebar{width:240px;min-height:100vh;background:#fff;border-right:1px solid #E5E7EB;display:flex;flex-direction:column;position:fixed;top:0;left:0;overflow-y:auto}
.sb-top{padding:22px 20px 16px;border-bottom:1px solid #E5E7EB}
.sb-brand{font-size:18px;font-weight:700;color:#111827;letter-spacing:-.3px}
.sb-brand span{color:#2563EB}
.sb-brand small{display:block;font-size:10px;font-weight:600;letter-spacing:.09em;text-transform:uppercase;color:#9CA3AF;margin-top:2px}
.sb-user{margin:12px 12px 0;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:11px 13px}
.sb-user-role{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9CA3AF;margin-bottom:3px}
.sb-user-name{font-size:13px;font-weight:700;color:#111827}
.sb-user-id{font-size:11px;color:#9CA3AF;margin-top:1px}
.sb-nav{padding:12px;flex:1;display:flex;flex-direction:column;gap:2px}
.sb-nav a{display:flex;align-items:center;gap:10px;padding:9px 12px;border-radius:9px;text-decoration:none;font-size:13px;font-weight:500;color:#6B7280;transition:background .12s,color .12s}
.sb-nav a:hover{background:#F3F4F6;color:#111827}
.sb-nav a.active{background:#EFF6FF;color:#2563EB;font-weight:600}
.sb-nav a.logout{color:#DC2626;margin-top:6px}
.sb-nav a.logout:hover{background:#FEF2F2}
.sb-nav svg{width:15px;height:15px;flex-shrink:0}
.sb-footer{padding:12px 16px;border-top:1px solid #E5E7EB;font-size:11px;color:#9CA3AF;line-height:1.8}
.dot{display:inline-block;width:7px;height:7px;background:#10B981;border-radius:50%;margin-right:4px;vertical-align:middle}

/* MAIN */
.main{margin-left:240px;padding:32px 36px;width:100%}
.page-hdr{margin-bottom:28px}
.page-hdr h1{font-size:22px;font-weight:700;color:#111827;letter-spacing:-.3px}
.page-hdr p{font-size:13px;color:#6B7280;margin-top:3px}

/* LAYOUT */
.calc-layout{display:grid;grid-template-columns:1fr 1fr;gap:18px;max-width:860px}

/* CARD */
.card{background:#fff;border:1px solid #E5E7EB;border-radius:16px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.06)}
.card-header{padding:20px 24px;border-bottom:1px solid #E5E7EB}
.card-header h2{font-size:15px;font-weight:700;color:#111827}
.card-header p{font-size:12px;color:#6B7280;margin-top:2px}
.card-body{padding:22px 24px}

/* RATE BOXES */
.rate-boxes{display:flex;flex-direction:column;gap:10px;margin-bottom:20px}
.rate-box{display:flex;align-items:center;gap:12px;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:12px 14px}
.rate-icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
.rate-icon.blue{background:#EFF6FF}
.rate-icon.indigo{background:#EEF2FF}
.rate-label{font-size:12px;color:#6B7280}
.rate-value{font-size:14px;font-weight:700;color:#111827;margin-top:1px}

/* INPUT */
.field-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6B7280;margin-bottom:6px;display:block}
.hour-input-wrap{position:relative}
.hour-input-wrap input{
    width:100%;padding:11px 46px 11px 14px;
    border:1.5px solid #D1D5DB;border-radius:10px;
    font-family:'Plus Jakarta Sans',sans-serif;font-size:15px;font-weight:600;color:#111827;
    outline:none;transition:border-color .15s,box-shadow .15s;background:#F9FAFB;
}
.hour-input-wrap input:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.1);background:#fff}
.hour-suffix{position:absolute;right:14px;top:50%;transform:translateY(-50%);font-size:13px;font-weight:600;color:#9CA3AF}

/* FEE DISPLAY */
.fee-display{background:linear-gradient(135deg,#1D4ED8 0%,#6366F1 100%);border-radius:12px;padding:22px;text-align:center;margin:18px 0}
.fee-display .fee-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.65);margin-bottom:6px}
.fee-display .fee-amount{font-size:42px;font-weight:700;color:#fff;line-height:1}
.fee-display .fee-currency{font-size:18px;font-weight:600;color:rgba(255,255,255,.8);margin-left:4px}
.fee-display .fee-sub{font-size:12px;color:rgba(255,255,255,.6);margin-top:6px}

/* BREAKDOWN */
.breakdown{background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:14px;margin-bottom:4px}
.breakdown-row{display:flex;justify-content:space-between;font-size:13px;color:#6B7280;padding:4px 0;border-bottom:1px solid #F3F4F6}
.breakdown-row:last-child{border-bottom:none;font-weight:700;color:#111827;padding-top:8px}
.breakdown-row span:last-child{font-weight:600;color:#2563EB}
.breakdown-row:last-child span:last-child{color:#111827}

/* PAYMENT */
.pay-title{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6B7280;margin-bottom:10px}
.pay-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px}
.pay-btn{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;padding:14px 10px;border:2px solid #E5E7EB;border-radius:11px;background:#F9FAFB;cursor:pointer;transition:all .15s;font-family:'Plus Jakarta Sans',sans-serif}
.pay-btn:hover{border-color:#2563EB;background:#EFF6FF;transform:translateY(-1px)}
.pay-btn .pay-icon{font-size:22px}
.pay-btn .pay-name{font-size:12px;font-weight:700;color:#111827}
.pay-btn .pay-sub{font-size:10px;color:#9CA3AF}
.pay-btn.bkash:hover{border-color:#D12053;background:#FFF0F5}
.pay-btn.nagad:hover{border-color:#F7941D;background:#FFFBF0}
.pay-btn.cash{grid-column:1/-1}
.pay-btn.cash:hover{border-color:#10B981;background:#ECFDF5}
.pay-note{font-size:11px;color:#9CA3AF;text-align:center;margin-top:8px}

.placeholder-msg{text-align:center;padding:40px 20px;color:#9CA3AF}
.placeholder-msg .p-icon{font-size:40px;margin-bottom:10px}
.placeholder-msg .p-title{font-size:13px;font-weight:600;color:#6B7280}
.placeholder-msg .p-sub{font-size:12px;margin-top:4px}

.hidden{display:none}
</style>

<script>
function calcFee() {
    const h = parseFloat(document.getElementById('hrs').value);

    const paySection     = document.getElementById('pay-section');
    const breakSection   = document.getElementById('break-section');
    const placeholder    = document.getElementById('pay-placeholder');

    if (!h || h <= 0) {
        document.getElementById('fee-amount').innerText = '0';
        document.getElementById('fee-sub').innerText    = 'Enter hours above to calculate';
        breakSection.classList.add('hidden');
        paySection.classList.add('hidden');
        placeholder.style.display = 'block';
        return;
    }

    // Fee logic: 50 BDT for 1st hour, 20 BDT per extra hour
    const extraHrs = h > 1 ? Math.ceil(h - 1) : 0;
    const fee      = 50 + (extraHrs * 20);

    // Update fee display
    document.getElementById('fee-amount').innerText = fee;
    document.getElementById('fee-sub').innerText    = 'Total for ' + h + ' hour' + (h !== 1 ? 's' : '');

    // Update breakdown
    document.getElementById('br-base').innerText  = '50 BDT';
    document.getElementById('br-extra').innerText = extraHrs > 0 ? (extraHrs + ' hr × 20 = ' + (extraHrs * 20) + ' BDT') : '— (within 1st hour)';
    document.getElementById('br-total').innerText = fee + ' BDT';

    // Show sections, hide placeholder
    breakSection.classList.remove('hidden');
    paySection.classList.remove('hidden');
    placeholder.style.display = 'none';
}

function pay(method) {
    const fee = document.getElementById('fee-amount').innerText;
    const hrs = document.getElementById('hrs').value;
    if (method === 'bKash') {
        window.location.href = 'bkash_pay.php?amount=' + fee + '&hrs=' + hrs;
    } else if (method === 'Nagad') {
        window.location.href = 'nagad_pay.php?amount=' + fee + '&hrs=' + hrs;
    } else {
        alert('Please pay Cash at Gate.\nAmount: ' + fee + ' BDT');
    }
}
</script>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sb-top">
        <div class="sb-brand">Uni<span>Park</span><small>Campus Parking System</small></div>
    </div>
    <div class="sb-user">
        <div class="sb-user-role">Logged in as</div>
        <div class="sb-user-name"><?php echo htmlspecialchars($_SESSION['name']); ?></div>
        <div class="sb-user-id">ID: <?php echo $uid; ?></div>
    </div>
    <div class="sb-nav">
        <a href="dashboard.php">
            <svg fill="none" viewBox="0 0 16 16"><rect x="1" y="1" width="6" height="6" rx="1.2" fill="currentColor"/><rect x="9" y="1" width="6" height="6" rx="1.2" fill="currentColor" opacity=".3"/><rect x="1" y="9" width="6" height="6" rx="1.2" fill="currentColor" opacity=".3"/><rect x="9" y="9" width="6" height="6" rx="1.2" fill="currentColor" opacity=".3"/></svg>
            Dashboard
        </a>
        <a href="reserve.php">
            <svg fill="none" viewBox="0 0 16 16"><rect x="2" y="4" width="12" height="9" rx="1.5" stroke="currentColor" stroke-width="1.4"/><path d="M5 4V3a1 1 0 011-1h4a1 1 0 011 1v1" stroke="currentColor" stroke-width="1.4"/><path d="M8 7.5v2M7 8.5h2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
            Reserve Slot
        </a>
        <a href="calculator.php" class="active">
            <svg fill="none" viewBox="0 0 16 16"><rect x="2" y="2" width="12" height="12" rx="1.5" stroke="currentColor" stroke-width="1.4"/><path d="M5 5h2M9 5h2M5 8h2M9 8h2M5 11h2M9 11h2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
            Calculator
        </a>
        <a href="search.php">
            <svg fill="none" viewBox="0 0 16 16"><circle cx="7" cy="7" r="4" stroke="currentColor" stroke-width="1.4"/><path d="M10 10l3 3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
            Search Vehicle
        </a>
        <a href="logout.php" class="logout">
            <svg fill="none" viewBox="0 0 16 16"><path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h3M11 11l3-3-3-3M14 8H6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Logout
        </a>
    </div>
    <div class="sb-footer">
        <div><span class="dot"></span>System Online</div>
    </div>
</div>

<!-- MAIN -->
<div class="main">

    <div class="page-hdr">
        <h1>Fee Calculator & Payment</h1>
        <p>Estimate your parking fee and choose a payment method.</p>
    </div>

    <div class="calc-layout">

        <!-- LEFT: Calculator -->
        <div class="card">
            <div class="card-header">
                <h2>🧮 Parking Fee Calculator</h2>
                <p>Enter your duration to get an instant estimate.</p>
            </div>
            <div class="card-body">

                <div class="rate-boxes">
                    <div class="rate-box">
                        <div class="rate-icon blue">🕐</div>
                        <div>
                            <div class="rate-label">First Hour (Flat Rate)</div>
                            <div class="rate-value">50 BDT</div>
                        </div>
                    </div>
                    <div class="rate-box">
                        <div class="rate-icon indigo">⏱️</div>
                        <div>
                            <div class="rate-label">Every Additional Hour</div>
                            <div class="rate-value">20 BDT / hr</div>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom:16px">
                    <label class="field-label">Parking Duration</label>
                    <div class="hour-input-wrap">
                        <input type="number" id="hrs" min="0.5" step="0.5" placeholder="e.g. 3" oninput="calcFee()">
                        <span class="hour-suffix">hrs</span>
                    </div>
                </div>

                <div class="fee-display">
                    <div class="fee-label">Estimated Total</div>
                    <div>
                        <span class="fee-amount" id="fee-amount">0</span>
                        <span class="fee-currency">BDT</span>
                    </div>
                    <div class="fee-sub" id="fee-sub">Enter hours above to calculate</div>
                </div>

                <div id="break-section" class="hidden">
                    <div class="breakdown">
                        <div class="breakdown-row"><span>Base rate (1st hour)</span><span id="br-base">—</span></div>
                        <div class="breakdown-row"><span>Additional hours</span><span id="br-extra">—</span></div>
                        <div class="breakdown-row"><span>Total</span><span id="br-total">—</span></div>
                    </div>
                </div>

            </div>
        </div>

        <!-- RIGHT: Payment -->
        <div class="card">
            <div class="card-header">
                <h2>💳 Payment Method</h2>
                <p>Choose how you'd like to pay your parking fee.</p>
            </div>
            <div class="card-body">

                <div id="pay-section" class="hidden">
                    <div class="pay-title">Select Payment Gateway</div>
                    <div class="pay-grid">
                        <button class="pay-btn bkash" onclick="pay('bKash')">
                            <span class="pay-icon">📱</span>
                            <span class="pay-name">bKash</span>
                            <span class="pay-sub">Mobile Banking</span>
                        </button>
                        <button class="pay-btn nagad" onclick="pay('Nagad')">
                            <span class="pay-icon">📲</span>
                            <span class="pay-name">Nagad</span>
                            <span class="pay-sub">Mobile Banking</span>
                        </button>
                        <button class="pay-btn cash" onclick="pay('Cash at Gate')">
                            <span class="pay-icon">🏦</span>
                            <span class="pay-name">Cash at Gate</span>
                            <span class="pay-sub">Pay on arrival / exit</span>
                        </button>
                    </div>
                    <div class="pay-note">You will be redirected to the payment gateway to confirm.</div>
                </div>

                <div id="pay-placeholder" class="placeholder-msg">
                    <div class="p-icon">💳</div>
                    <div class="p-title">Payment options will appear</div>
                    <div class="p-sub">once you enter your parking duration.</div>
                </div>

            </div>
        </div>

    </div>
</div>

</body>
</html>