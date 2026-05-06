<?php
include 'db.php';
if (!isset($_SESSION['uid'])) { header("Location: login.php"); exit; }
$uid = $_SESSION['uid'];
$isAdmin = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM university WHERE user_id='$uid'")) > 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reserve Slot — UniPark</title>
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

/* PAGE HEADER */
.page-hdr{margin-bottom:28px}
.page-hdr h1{font-size:22px;font-weight:700;color:#111827;letter-spacing:-.3px}
.page-hdr p{font-size:13px;color:#6B7280;margin-top:3px}

/* FORM CARD */
.form-card{background:#fff;border:1px solid #E5E7EB;border-radius:16px;overflow:hidden;max-width:640px;box-shadow:0 1px 4px rgba(0,0,0,.06)}

.form-card-header{background:linear-gradient(110deg,#1D4ED8 0%,#6366F1 100%);padding:22px 28px}
.form-card-header h2{font-size:18px;font-weight:700;color:#fff}
.form-card-header p{font-size:13px;color:rgba(255,255,255,.75);margin-top:3px}

.form-body{padding:28px}

/* FIELD GROUPS */
.field-group{margin-bottom:18px}
.field-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6B7280;margin-bottom:6px;display:block}

input[type="text"],
input[type="datetime-local"],
select{
    width:100%;
    padding:10px 13px;
    border:1px solid #D1D5DB;
    border-radius:9px;
    font-family:'Plus Jakarta Sans',sans-serif;
    font-size:14px;
    color:#111827;
    background:#fff;
    outline:none;
    transition:border-color .15s,box-shadow .15s;
    appearance:none;
}

input:focus,select:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
input[readonly]{background:#F9FAFB;color:#9CA3AF;cursor:not-allowed}

.two-col{display:grid;grid-template-columns:1fr 1fr;gap:14px}

/* SECTOR CARDS */
.sector-picker{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:4px}
.sector-opt{position:relative}
.sector-opt input[type="radio"]{position:absolute;opacity:0;width:0;height:0}
.sector-opt label{
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    padding:12px 8px;border:2px solid #E5E7EB;border-radius:11px;
    cursor:pointer;transition:all .15s;text-align:center;background:#F9FAFB;
}
.sector-opt label:hover{border-color:#93C5FD;background:#EFF6FF}
.sector-opt input:checked + label{border-color:#2563EB;background:#EFF6FF}
.sector-icon{font-size:20px;margin-bottom:4px}
.sector-name{font-size:12px;font-weight:700;color:#111827}
.sector-sub{font-size:10px;color:#6B7280;margin-top:1px}
.sector-cap{font-size:10px;font-weight:600;color:#2563EB;margin-top:3px}

/* VEHICLE TYPE */
.vtype-picker{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.vtype-opt{position:relative}
.vtype-opt input[type="radio"]{position:absolute;opacity:0;width:0;height:0}
.vtype-opt label{
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    padding:10px 6px;border:2px solid #E5E7EB;border-radius:9px;
    cursor:pointer;transition:all .15s;background:#F9FAFB;text-align:center;
}
.vtype-opt label:hover{border-color:#93C5FD;background:#EFF6FF}
.vtype-opt input:checked + label{border-color:#2563EB;background:#EFF6FF}
.vtype-icon{font-size:18px;margin-bottom:3px}
.vtype-name{font-size:12px;font-weight:600;color:#111827}

/* DIVIDER */
.divider{border:none;border-top:1px solid #F3F4F6;margin:22px 0}

/* SUBMIT */
.btn-submit{
    width:100%;padding:12px;
    background:#2563EB;color:#fff;border:none;
    border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;
    font-size:14px;font-weight:700;cursor:pointer;
    transition:background .15s;
    display:flex;align-items:center;justify-content:center;gap:8px;
}
.btn-submit:hover{background:#1D4ED8}

/* ALERT */
.alert{padding:12px 16px;border-radius:9px;font-size:13px;font-weight:500;margin-bottom:20px}
.alert-success{background:#ECFDF5;border:1px solid #A7F3D0;color:#065F46}
.alert-error  {background:#FEF2F2;border:1px solid #FECACA;color:#991B1B}
</style>
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
        <a href="reserve.php" class="active">
            <svg fill="none" viewBox="0 0 16 16"><rect x="2" y="4" width="12" height="9" rx="1.5" stroke="currentColor" stroke-width="1.4"/><path d="M5 4V3a1 1 0 011-1h4a1 1 0 011 1v1" stroke="currentColor" stroke-width="1.4"/><path d="M8 7.5v2M7 8.5h2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
            Reserve Slot
        </a>
        <a href="calculator.php">
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
        <h1>Reserve a Parking Slot</h1>
        <p>Choose your sector, vehicle type and time — we'll assign you the best available slot.</p>
    </div>

    <?php
    $msg = ''; $msgType = '';
    if (isset($_POST['book'])) {
        $sector_id = (int)$_POST['sector'];
        $type      = mysqli_real_escape_string($conn, $_POST['type']);
        $v_no      = mysqli_real_escape_string($conn, $_POST['v_no']);
        $start     = mysqli_real_escape_string($conn, $_POST['start']);
        $end       = mysqli_real_escape_string($conn, $_POST['end']);

        $find = mysqli_query($conn, "SELECT slot_id FROM slot WHERE sector_id=$sector_id AND vehical_type='$type' AND status='Available' LIMIT 1");
        if ($slot = mysqli_fetch_assoc($find)) {
            $slot_id = $slot['slot_id'];
            mysqli_query($conn, "INSERT INTO reservation (user_id, slot_id, vehical_number, start_time, end_time) VALUES ('$uid', '$slot_id', '$v_no', '$start', '$end')");
            mysqli_query($conn, "UPDATE slot SET status='Occupied' WHERE slot_id=$slot_id");
            $msg = "✅ Slot reserved successfully! Slot #$slot_id has been assigned to you.";
            $msgType = 'success';
        } else {
            $msg = "❌ No available slots in this sector for the selected vehicle type. Please try another sector.";
            $msgType = 'error';
        }
    }
    ?>

    <?php if ($msg): ?>
        <div class="alert alert-<?php echo $msgType; ?>" style="max-width:640px"><?php echo $msg; ?></div>
    <?php endif; ?>

    <div class="form-card">
        <div class="form-card-header">
            <h2>Slot Booking Form</h2>
            <p>All fields are required. Slots are allocated automatically.</p>
        </div>

        <div class="form-body">
            <form method="POST">

                <!-- User ID (readonly) -->
                <div class="field-group">
                    <label class="field-label">Your User ID</label>
                    <input type="text" name="uid" value="<?php echo $uid; ?>" readonly>
                </div>

                <!-- Vehicle Number -->
                <div class="field-group">
                    <label class="field-label">Vehicle License Plate</label>
                    <input type="text" name="v_no" placeholder="e.g. DHA-1234" required>
                </div>

                <hr class="divider">

                <!-- Sector Picker -->
                <div class="field-group">
                    <label class="field-label">Select Sector</label>
                    <div class="sector-picker">
                        <div class="sector-opt">
                            <input type="radio" name="sector" id="s1" value="1" checked>
                            <label for="s1">
                                <span class="sector-icon">🎓</span>
                                <span class="sector-name">Sector A</span>
                                <span class="sector-sub">Students</span>
                                <span class="sector-cap">100 seats</span>
                            </label>
                        </div>
                        <div class="sector-opt">
                            <input type="radio" name="sector" id="s2" value="2">
                            <label for="s2">
                                <span class="sector-icon">👨‍🏫</span>
                                <span class="sector-name">Sector B</span>
                                <span class="sector-sub">Faculty</span>
                                <span class="sector-cap">60 seats</span>
                            </label>
                        </div>
                        <div class="sector-opt">
                            <input type="radio" name="sector" id="s3" value="3">
                            <label for="s3">
                                <span class="sector-icon">🏢</span>
                                <span class="sector-name">Sector C</span>
                                <span class="sector-sub">Staff</span>
                                <span class="sector-cap">50 seats</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Type -->
                <div class="field-group">
                    <label class="field-label">Vehicle Type</label>
                    <div class="vtype-picker">
                        <div class="vtype-opt">
                            <input type="radio" name="type" id="car" value="Car" checked>
                            <label for="car">
                                <span class="vtype-icon">🚗</span>
                                <span class="vtype-name">Car</span>
                            </label>
                        </div>
                        <div class="vtype-opt">
                            <input type="radio" name="type" id="bike" value="Bike">
                            <label for="bike">
                                <span class="vtype-icon">🏍️</span>
                                <span class="vtype-name">Bike</span>
                            </label>
                        </div>
                        <div class="vtype-opt">
                            <input type="radio" name="type" id="bicycle" value="Bicycle">
                            <label for="bicycle">
                                <span class="vtype-icon">🚲</span>
                                <span class="vtype-name">Bicycle</span>
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="divider">

                <!-- Date & Time -->
                <div class="two-col">
                    <div class="field-group">
                        <label class="field-label">Start Date & Time</label>
                        <input type="datetime-local" name="start" required>
                    </div>
                    <div class="field-group">
                        <label class="field-label">End Date & Time</label>
                        <input type="datetime-local" name="end" required>
                    </div>
                </div>

                <button type="submit" name="book" class="btn-submit">
                    <svg width="16" height="16" fill="none" viewBox="0 0 16 16"><path d="M13 5L6.5 11.5 3 8" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Allocate Slot
                </button>

            </form>
        </div>
    </div>

</div>
</body>
</html>