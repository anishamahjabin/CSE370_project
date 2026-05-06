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
<title>Search Vehicle — UniPark</title>
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

/* SEARCH CARD */
.search-card{background:#fff;border:1px solid #E5E7EB;border-radius:16px;overflow:hidden;max-width:640px;box-shadow:0 1px 4px rgba(0,0,0,.06);margin-bottom:20px}

.search-card-header{background:linear-gradient(110deg,#1D4ED8 0%,#6366F1 100%);padding:22px 28px}
.search-card-header h2{font-size:18px;font-weight:700;color:#fff}
.search-card-header p{font-size:13px;color:rgba(255,255,255,.75);margin-top:3px}

.search-body{padding:24px 28px}

/* SEARCH BAR */
.search-bar{display:flex;gap:10px}
.search-bar input{
    flex:1;padding:11px 16px;
    border:1.5px solid #D1D5DB;border-radius:10px;
    font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;color:#111827;
    outline:none;transition:border-color .15s,box-shadow .15s;background:#F9FAFB;
}
.search-bar input:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.1);background:#fff}
.search-bar input::placeholder{color:#9CA3AF}
.btn-search{
    background:#2563EB;color:#fff;border:none;
    padding:11px 22px;border-radius:10px;
    font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:700;
    cursor:pointer;white-space:nowrap;transition:background .15s;
    display:flex;align-items:center;gap:7px;
}
.btn-search:hover{background:#1D4ED8}
.btn-search svg{width:15px;height:15px}

/* SEARCH HINT */
.search-hint{font-size:12px;color:#9CA3AF;margin-top:10px}
.search-hint span{display:inline-block;background:#F3F4F6;border:1px solid #E5E7EB;border-radius:5px;padding:2px 8px;font-size:11px;font-weight:600;color:#6B7280;margin-right:5px}

/* RESULT CARD */
.result-card{background:#fff;border:1px solid #E5E7EB;border-radius:14px;overflow:hidden;max-width:640px;box-shadow:0 1px 4px rgba(0,0,0,.06)}

.result-found-header{background:#ECFDF5;border-bottom:1px solid #A7F3D0;padding:16px 22px;display:flex;align-items:center;gap:10px}
.result-found-header .check{width:32px;height:32px;background:#10B981;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.result-found-header h3{font-size:15px;font-weight:700;color:#065F46}
.result-found-header p{font-size:12px;color:#047857;margin-top:1px}

.result-body{padding:22px}
.result-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.result-field{background:#F9FAFB;border:1px solid #F3F4F6;border-radius:10px;padding:12px 14px}
.result-field-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9CA3AF;margin-bottom:4px}
.result-field-value{font-size:15px;font-weight:700;color:#111827}

/* SECTOR BADGE in result */
.sector-tag{display:inline-flex;align-items:center;gap:5px;background:#EFF6FF;color:#2563EB;border:1px solid #BFDBFE;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:700}

/* NOT FOUND */
.not-found{background:#FEF2F2;border:1px solid #FECACA;border-radius:12px;padding:18px 22px;max-width:640px;display:flex;align-items:center;gap:14px}
.not-found .x-icon{width:32px;height:32px;background:#EF4444;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.not-found h3{font-size:14px;font-weight:700;color:#991B1B}
.not-found p{font-size:12px;color:#B91C1C;margin-top:2px}
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
        <a href="reserve.php">
            <svg fill="none" viewBox="0 0 16 16"><rect x="2" y="4" width="12" height="9" rx="1.5" stroke="currentColor" stroke-width="1.4"/><path d="M5 4V3a1 1 0 011-1h4a1 1 0 011 1v1" stroke="currentColor" stroke-width="1.4"/><path d="M8 7.5v2M7 8.5h2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
            Reserve Slot
        </a>
        <a href="calculator.php">
            <svg fill="none" viewBox="0 0 16 16"><rect x="2" y="2" width="12" height="12" rx="1.5" stroke="currentColor" stroke-width="1.4"/><path d="M5 5h2M9 5h2M5 8h2M9 8h2M5 11h2M9 11h2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
            Calculator
        </a>
        <a href="search.php" class="active">
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
        <h1>Vehicle Finder</h1>
        <p>Search by vehicle plate number or user ID to locate a parked vehicle.</p>
    </div>

    <!-- Search Card -->
    <div class="search-card">
        <div class="search-card-header">
            <h2>🔍 Search Parking Records</h2>
            <p>Enter a vehicle plate or user ID to find their current parking location.</p>
        </div>
        <div class="search-body">
            <form method="POST">
                <div class="search-bar">
                    <input type="text" name="search_term"
                        placeholder="e.g. DHA-1234 or User ID 11001"
                        value="<?php echo isset($_POST['search_term']) ? htmlspecialchars($_POST['search_term']) : ''; ?>"
                        required>
                    <button type="submit" name="search" class="btn-search">
                        <svg fill="none" viewBox="0 0 16 16"><circle cx="7" cy="7" r="4" stroke="white" stroke-width="1.6"/><path d="M10 10l3 3" stroke="white" stroke-width="1.6" stroke-linecap="round"/></svg>
                        Search
                    </button>
                </div>
                <div class="search-hint">
                    Search by: <span>Vehicle Plate</span> or <span>User ID</span>
                </div>
            </form>
        </div>
    </div>

    <?php
    if (isset($_POST['search'])) {
        $term = mysqli_real_escape_string($conn, $_POST['search_term']);
        $sql  = "SELECT u.name, u.user_id, r.vehical_number, r.start_time, r.end_time,
                        s.name as slot_name, sec.sector_name
                 FROM user u
                 JOIN reservation r ON u.user_id = r.user_id
                 JOIN slot s ON r.slot_id = s.slot_id
                 JOIN sector sec ON s.sector_id = sec.sector_id
                 WHERE r.vehical_number = '$term' OR u.user_id = '$term'
                 ORDER BY r.start_time DESC LIMIT 1";

        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            echo '
            <div class="result-card">
                <div class="result-found-header">
                    <div class="check">
                        <svg width="16" height="16" fill="none" viewBox="0 0 16 16"><path d="M3 8l4 4 6-6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div>
                        <h3>Vehicle Located!</h3>
                        <p>Active parking record found for your search.</p>
                    </div>
                </div>
                <div class="result-body">
                    <div class="result-grid">
                        <div class="result-field">
                            <div class="result-field-label">Owner Name</div>
                            <div class="result-field-value">'.htmlspecialchars($row['name']).'</div>
                        </div>
                        <div class="result-field">
                            <div class="result-field-label">User ID</div>
                            <div class="result-field-value">'.htmlspecialchars($row['user_id']).'</div>
                        </div>
                        <div class="result-field">
                            <div class="result-field-label">Vehicle Plate</div>
                            <div class="result-field-value">'.htmlspecialchars($row['vehical_number']).'</div>
                        </div>
                        <div class="result-field">
                            <div class="result-field-label">Slot Number</div>
                            <div class="result-field-value">'.htmlspecialchars($row['slot_name']).'</div>
                        </div>
                        <div class="result-field" style="grid-column:1/-1">
                            <div class="result-field-label">Sector / Location</div>
                            <div class="result-field-value">
                                <span class="sector-tag">📍 '.htmlspecialchars($row['sector_name']).'</span>
                            </div>
                        </div>
                        <div class="result-field">
                            <div class="result-field-label">Check-in Time</div>
                            <div class="result-field-value" style="font-size:13px">'.htmlspecialchars($row['start_time']).'</div>
                        </div>
                        <div class="result-field">
                            <div class="result-field-label">Check-out Time</div>
                            <div class="result-field-value" style="font-size:13px">'.htmlspecialchars($row['end_time']).'</div>
                        </div>
                    </div>
                </div>
            </div>';
        } else {
            echo '
            <div class="not-found">
                <div class="x-icon">
                    <svg width="16" height="16" fill="none" viewBox="0 0 16 16"><path d="M4 4l8 8M12 4l-8 8" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
                </div>
                <div>
                    <h3>No Record Found</h3>
                    <p>No active parking record matches "<strong>'.htmlspecialchars($term).'</strong>". Check the plate or ID and try again.</p>
                </div>
            </div>';
        }
    }
    ?>

</div>
</body>
</html>