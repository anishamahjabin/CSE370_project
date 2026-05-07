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
.search-card{background:#fff;border:1px solid #E5E7EB;border-radius:16px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.06);margin-bottom:20px}
.search-card-header{background:linear-gradient(110deg,#1D4ED8 0%,#6366F1 100%);padding:22px 28px}
.search-card-header h2{font-size:18px;font-weight:700;color:#fff}
.search-card-header p{font-size:13px;color:rgba(255,255,255,.75);margin-top:3px}
.search-body{padding:24px 28px}
.search-bar{display:flex;gap:10px}
.search-bar input{flex:1;padding:11px 16px;border:1.5px solid #D1D5DB;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;color:#111827;outline:none;transition:border-color .15s,box-shadow .15s;background:#F9FAFB}
.search-bar input:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.1);background:#fff}
.search-bar input::placeholder{color:#9CA3AF}
.btn-search{background:#2563EB;color:#fff;border:none;padding:11px 22px;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:700;cursor:pointer;white-space:nowrap;transition:background .15s;display:flex;align-items:center;gap:7px}
.btn-search:hover{background:#1D4ED8}
.btn-search svg{width:15px;height:15px}
.search-hint{font-size:12px;color:#9CA3AF;margin-top:10px}
.search-hint span{display:inline-block;background:#F3F4F6;border:1px solid #E5E7EB;border-radius:5px;padding:2px 8px;font-size:11px;font-weight:600;color:#6B7280;margin-right:5px}

/* RESULTS FOUND HEADER */
.results-found-header{background:#ECFDF5;border:1px solid #A7F3D0;border-radius:12px 12px 0 0;padding:16px 22px;display:flex;align-items:center;justify-content:space-between}
.results-found-left{display:flex;align-items:center;gap:10px}
.check{width:32px;height:32px;background:#10B981;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.results-found-header h3{font-size:15px;font-weight:700;color:#065F46}
.results-found-header p{font-size:12px;color:#047857;margin-top:1px}
.results-count-badge{background:#10B981;color:#fff;font-size:12px;font-weight:700;padding:4px 14px;border-radius:20px;white-space:nowrap}

/* TABLE */
.table-wrap{background:#fff;border:1px solid #A7F3D0;border-top:none;border-radius:0 0 12px 12px;overflow-x:auto;margin-bottom:20px}
table{width:100%;border-collapse:collapse}
thead tr{background:#F0FDF4}
thead th{padding:11px 16px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#047857;text-align:left;border-bottom:1px solid #D1FAE5;white-space:nowrap}
tbody tr{border-bottom:1px solid #F3F4F6;transition:background .1s}
tbody tr:last-child{border-bottom:none}
tbody tr:hover{background:#F9FAFB}
tbody td{padding:13px 16px;font-size:13px;color:#111827;vertical-align:middle;white-space:nowrap}
.row-num{display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;background:#F3F4F6;border-radius:50%;font-size:11px;font-weight:700;color:#6B7280}
.plate-val{font-weight:700;font-size:13px;letter-spacing:.03em}
.owner-sub{font-size:11px;color:#9CA3AF;margin-top:1px}
.sector-tag{display:inline-flex;align-items:center;gap:4px;background:#EFF6FF;color:#2563EB;border:1px solid #BFDBFE;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:700}
.status-active{display:inline-flex;align-items:center;gap:4px;background:#ECFDF5;color:#059669;border:1px solid #A7F3D0;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.status-expired{display:inline-flex;align-items:center;gap:4px;background:#F9FAFB;color:#6B7280;border:1px solid #E5E7EB;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700}
.time-val{font-size:12px;color:#374151}

/* NOT FOUND */
.not-found{background:#FEF2F2;border:1px solid #FECACA;border-radius:12px;padding:18px 22px;display:flex;align-items:center;gap:14px}
.x-icon{width:32px;height:32px;background:#EF4444;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0}
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
        <p>Search by vehicle plate number or user ID to view all parking records.</p>
    </div>

    <!-- Search Card -->
    <div class="search-card">
        <div class="search-card-header">
            <h2>🔍 Search Parking Records</h2>
            <p>Enter a vehicle plate or user ID to find all matching reservations.</p>
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

        // ✅ FIXED: Removed LIMIT 1 so ALL records are returned.
        // Added booking_status column to show Active vs Expired per row.
        $sql = "SELECT u.name, u.user_id, r.vehical_number, r.start_time, r.end_time,
                       s.name as slot_name, sec.sector_name,
                       CASE WHEN r.end_time >= NOW() THEN 'Active' ELSE 'Expired' END AS booking_status
                FROM user u
                JOIN reservation r ON u.user_id = r.user_id
                JOIN slot s ON r.slot_id = s.slot_id
                JOIN sector sec ON s.sector_id = sec.sector_id
                WHERE r.vehical_number = '$term' OR u.user_id = '$term'
                ORDER BY r.start_time DESC";

        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $total = mysqli_num_rows($res);
            ?>

            <!-- Results header -->
            <div class="results-found-header">
                <div class="results-found-left">
                    <div class="check">
                        <svg width="16" height="16" fill="none" viewBox="0 0 16 16">
                            <path d="M3 8l4 4 6-6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <h3>Records Found!</h3>
                        <p>Showing all parking records matching your search — newest first.</p>
                    </div>
                </div>
                <span class="results-count-badge"><?php echo $total; ?> record<?php echo $total > 1 ? 's' : ''; ?></span>
            </div>

            <!-- Results table -->
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Owner</th>
                            <th>Vehicle Plate</th>
                            <th>Slot</th>
                            <th>Sector / Location</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($res)):
                        $isActive = $row['booking_status'] === 'Active';
                    ?>
                        <tr>
                            <td><span class="row-num"><?php echo $i; ?></span></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                                <div class="owner-sub">ID: <?php echo htmlspecialchars($row['user_id']); ?></div>
                            </td>
                            <td><span class="plate-val"><?php echo htmlspecialchars($row['vehical_number']); ?></span></td>
                            <td><?php echo htmlspecialchars($row['slot_name']); ?></td>
                            <td><span class="sector-tag">📍 <?php echo htmlspecialchars($row['sector_name']); ?></span></td>
                            <td><span class="time-val"><?php echo htmlspecialchars($row['start_time']); ?></span></td>
                            <td><span class="time-val"><?php echo htmlspecialchars($row['end_time']); ?></span></td>
                            <td>
                                <?php if ($isActive): ?>
                                    <span class="status-active">🟢 Active</span>
                                <?php else: ?>
                                    <span class="status-expired">⚪ Expired</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php $i++; endwhile; ?>
                    </tbody>
                </table>
            </div>

            <?php
        } else {
            echo '
            <div class="not-found">
                <div class="x-icon">
                    <svg width="16" height="16" fill="none" viewBox="0 0 16 16">
                        <path d="M4 4l8 8M12 4l-8 8" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <h3>No Record Found</h3>
                    <p>No parking records match "<strong>' . htmlspecialchars($term) . '</strong>". Check the plate or ID and try again.</p>
                </div>
            </div>';
        }
    }
    ?>

</div>
</body>
</html>
