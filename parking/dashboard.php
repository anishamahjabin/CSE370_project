<?php
include 'db.php';
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit;
}
$uid     = $_SESSION['uid'];
$isAdmin = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM university WHERE user_id='$uid'")) > 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard — UniPark</title>
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
.main{margin-left:240px;padding:28px 32px;width:100%}

/* PAGE HEADER */
.page-hdr{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:22px}
.page-hdr h1{font-size:22px;font-weight:700;color:#111827;letter-spacing:-.3px}
.page-hdr p{font-size:13px;color:#6B7280;margin-top:2px}
.btn-book{background:#2563EB;color:#fff;border:none;padding:9px 18px;border-radius:9px;font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-block;transition:background .15s}
.btn-book:hover{background:#1D4ED8}

/* STATS ROW */
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px}
.stat{background:#fff;border:1px solid #E5E7EB;border-radius:14px;padding:16px 18px;position:relative;overflow:hidden}
.stat::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;border-radius:14px 14px 0 0}
.stat.s-total::before{background:#6366F1}
.stat.s-avail::before{background:#10B981}
.stat.s-occ::before{background:#EF4444}
.stat.s-booked::before{background:#F59E0B}
.stat-lbl{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9CA3AF;margin-bottom:7px}
.stat-val{font-size:30px;font-weight:700;line-height:1;margin-bottom:3px}
.s-total  .stat-val{color:#4F46E5}
.s-avail  .stat-val{color:#059669}
.s-occ    .stat-val{color:#DC2626}
.s-booked .stat-val{color:#D97706}
.stat-sub{font-size:12px;color:#6B7280}
.stat-bar{height:4px;background:#F3F4F6;border-radius:2px;margin-top:12px;overflow:hidden}
.stat-bar div{height:100%;border-radius:2px;transition:width .5s ease}
.s-total  .stat-bar div{background:#6366F1}
.s-avail  .stat-bar div{background:#10B981}
.s-occ    .stat-bar div{background:#EF4444}
.s-booked .stat-bar div{background:#F59E0B}

/* BANNER */
.banner{background:linear-gradient(110deg,#1D4ED8 0%,#6366F1 100%);border-radius:14px;padding:18px 26px;display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
.banner h2{font-size:19px;font-weight:700;color:#fff}
.banner p{font-size:13px;color:rgba(255,255,255,.7);margin-top:2px}
.btn-white{background:#fff;color:#2563EB;border:none;padding:9px 20px;border-radius:9px;font-family:'Plus Jakarta Sans',sans-serif;font-size:13px;font-weight:700;cursor:pointer;white-space:nowrap;transition:opacity .15s}
.btn-white:hover{opacity:.88}

/* SECTION LABEL */
.sec-lbl{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:#9CA3AF;margin-bottom:10px}

/* SECTOR CARDS */
.sectors{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-bottom:18px}
.sec-card{background:#fff;border:1px solid #E5E7EB;border-radius:14px;padding:18px 20px}
.sec-card.locked{opacity:.4;pointer-events:none}

.sec-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.sec-name{font-size:14px;font-weight:700;color:#111827}

.badge{font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:.05em}
.b-blue{background:#EFF6FF;color:#2563EB}
.b-green{background:#ECFDF5;color:#059669}
.b-amber{background:#FFFBEB;color:#D97706}
.b-purple{background:#F5F3FF;color:#7C3AED}

/* SEAT CAPACITY LINE */
.capacity-line{font-size:12px;color:#6B7280;margin-bottom:12px;padding:7px 10px;background:#F9FAFB;border-radius:8px;border:1px solid #F3F4F6}
.capacity-line strong{color:#111827}

/* MINI STATS (3 cols: available / occupied / booked) */
.mini-row{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:12px}
.mini{border-radius:9px;padding:10px;text-align:center}
.mini.m-avail {background:#ECFDF5;border:1px solid #A7F3D0}
.mini.m-occ   {background:#FEF2F2;border:1px solid #FECACA}
.mini.m-booked{background:#FFFBEB;border:1px solid #FDE68A}
.mini-val{font-size:20px;font-weight:700;line-height:1}
.mini.m-avail  .mini-val{color:#059669}
.mini.m-occ    .mini-val{color:#DC2626}
.mini.m-booked .mini-val{color:#D97706}
.mini-lbl{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#6B7280;margin-top:3px}

/* PROGRESS */
.prog-hdr{display:flex;justify-content:space-between;font-size:12px;color:#6B7280;margin-bottom:4px}
.prog-hdr span:last-child{font-weight:600;color:#374151}
.prog-track{height:6px;background:#F3F4F6;border-radius:3px;overflow:hidden}
.prog-fill{height:100%;border-radius:3px;transition:width .5s ease}
.pf-blue  {background:#3B82F6}
.pf-green {background:#10B981}
.pf-amber {background:#F59E0B}
.pf-purple{background:#8B5CF6}

/* SYSINFO */
.sysinfo{background:#fff;border:1px solid #E5E7EB;border-radius:12px;padding:12px 18px;display:flex;align-items:center;gap:28px;font-size:12px;color:#6B7280}
.sysinfo strong{color:#111827;font-weight:600}
</style>

<script>
// Sector totals must match fetch.php
const TOTALS = { 1: 100, 2: 60, 3: 50, 4: 20 };

function updateDashboard() {
    fetch('fetch.php')
        .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(data => {
            let grandTotal = 0, grandAvail = 0, grandOcc = 0, grandBooked = 0;

            for (let id = 1; id <= 4; id++) {
                const d   = data[id];
                if (!d) continue;

                const tot    = d.total;
                const occ    = d.occupied;
                const booked = d.booked;
                const avail  = d.available;
                const pct    = tot > 0 ? Math.round((occ + booked) / tot * 100) : 0;

                set('cap-'    + id, tot);
                set('avail-'  + id, avail);
                set('occ-'    + id, occ);
                set('booked-' + id, booked);
                set('pct-'    + id, pct + '%');
                wid('bar-'    + id, pct);

                grandTotal  += tot;
                grandAvail  += avail;
                grandOcc    += occ;
                grandBooked += booked;
            }

            set('sum-total',  grandTotal);
            set('sum-avail',  grandAvail);
            set('sum-occ',    grandOcc);
            set('sum-booked', grandBooked);

            const avPct  = grandTotal > 0 ? Math.round(grandAvail  / grandTotal * 100) : 0;
            const ocPct  = grandTotal > 0 ? Math.round(grandOcc    / grandTotal * 100) : 0;
            const bkPct  = grandTotal > 0 ? Math.round(grandBooked / grandTotal * 100) : 0;

            set('sub-avail',  avPct + '% open');
            set('sub-occ',    ocPct + '% in use');
            set('sub-booked', bkPct + '% pre-booked');
            wid('bar-avail',  avPct);
            wid('bar-occ',    ocPct);
            wid('bar-booked', bkPct);

            const t = new Date().toLocaleTimeString();
            set('time', t);
            set('time2', t);
        })
        .catch(e => console.error('Dashboard fetch error:', e));
}

function set(id, val) { const el = document.getElementById(id); if (el) el.innerText = val; }
function wid(id, pct) { const el = document.getElementById(id); if (el) el.style.width = pct + '%'; }

setInterval(updateDashboard, 3000);
window.onload = updateDashboard;
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
        <a href="dashboard.php" class="active">
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
        <div>Updated: <span id="time"><?php echo date('h:i:s A'); ?></span></div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="main">

    <!-- Header -->
    <div class="page-hdr">
        <div>
            <h1>Parking Overview</h1>
            <p>Live seat availability — refreshes every 3 seconds</p>
        </div>
        <a href="reserve.php" class="btn-book">+ Book a Slot</a>
    </div>

    <!-- Summary Stats -->
    <div class="stats-row">
        <div class="stat s-total">
            <div class="stat-lbl">Total Seats</div>
            <div class="stat-val" id="sum-total">230</div>
            <div class="stat-sub">Across all 4 sectors</div>
            <div class="stat-bar"><div style="width:100%"></div></div>
        </div>
        <div class="stat s-avail">
            <div class="stat-lbl">Available</div>
            <div class="stat-val" id="sum-avail">—</div>
            <div class="stat-sub" id="sub-avail">Loading…</div>
            <div class="stat-bar"><div id="bar-avail" style="width:0%"></div></div>
        </div>
        <div class="stat s-occ">
            <div class="stat-lbl">Occupied</div>
            <div class="stat-val" id="sum-occ">—</div>
            <div class="stat-sub" id="sub-occ">Loading…</div>
            <div class="stat-bar"><div id="bar-occ" style="width:0%"></div></div>
        </div>
        <div class="stat s-booked">
            <div class="stat-lbl">Booked / Reserved</div>
            <div class="stat-val" id="sum-booked">—</div>
            <div class="stat-sub" id="sub-booked">Loading…</div>
            <div class="stat-bar"><div id="bar-booked" style="width:0%"></div></div>
        </div>
    </div>

    <!-- Banner -->
    <div class="banner">
        <div>
            <h2>Need a Slot?</h2>
            <p>Find and reserve your parking spot instantly.</p>
        </div>
        <button class="btn-white" onclick="location.href='reserve.php'">Book Now →</button>
    </div>

    <div class="sec-lbl">Sector Breakdown</div>

    <!-- Sector Cards -->
    <div class="sectors">

        <!-- SECTOR A — Students -->
        <div class="sec-card">
            <div class="sec-head">
                <div class="sec-name">Sector A</div>
                <span class="badge b-blue">Students</span>
            </div>
            <div class="capacity-line">Total Capacity: <strong><span id="cap-1">100</span> seats</strong></div>
            <div class="mini-row">
                <div class="mini m-avail">
                    <div class="mini-val" id="avail-1">—</div>
                    <div class="mini-lbl">Available</div>
                </div>
                <div class="mini m-occ">
                    <div class="mini-val" id="occ-1">—</div>
                    <div class="mini-lbl">Occupied</div>
                </div>
                <div class="mini m-booked">
                    <div class="mini-val" id="booked-1">—</div>
                    <div class="mini-lbl">Booked</div>
                </div>
            </div>
            <div class="prog-hdr"><span>Usage</span><span id="pct-1">—</span></div>
            <div class="prog-track"><div class="prog-fill pf-blue" id="bar-1" style="width:0%"></div></div>
        </div>

        <!-- SECTOR B — Faculty -->
        <div class="sec-card">
            <div class="sec-head">
                <div class="sec-name">Sector B</div>
                <span class="badge b-green">Faculty</span>
            </div>
            <div class="capacity-line">Total Capacity: <strong><span id="cap-2">60</span> seats</strong></div>
            <div class="mini-row">
                <div class="mini m-avail">
                    <div class="mini-val" id="avail-2">—</div>
                    <div class="mini-lbl">Available</div>
                </div>
                <div class="mini m-occ">
                    <div class="mini-val" id="occ-2">—</div>
                    <div class="mini-lbl">Occupied</div>
                </div>
                <div class="mini m-booked">
                    <div class="mini-val" id="booked-2">—</div>
                    <div class="mini-lbl">Booked</div>
                </div>
            </div>
            <div class="prog-hdr"><span>Usage</span><span id="pct-2">—</span></div>
            <div class="prog-track"><div class="prog-fill pf-green" id="bar-2" style="width:0%"></div></div>
        </div>

        <!-- SECTOR C — Staff -->
        <div class="sec-card">
            <div class="sec-head">
                <div class="sec-name">Sector C</div>
                <span class="badge b-amber">Staff</span>
            </div>
            <div class="capacity-line">Total Capacity: <strong><span id="cap-3">50</span> seats</strong></div>
            <div class="mini-row">
                <div class="mini m-avail">
                    <div class="mini-val" id="avail-3">—</div>
                    <div class="mini-lbl">Available</div>
                </div>
                <div class="mini m-occ">
                    <div class="mini-val" id="occ-3">—</div>
                    <div class="mini-lbl">Occupied</div>
                </div>
                <div class="mini m-booked">
                    <div class="mini-val" id="booked-3">—</div>
                    <div class="mini-lbl">Booked</div>
                </div>
            </div>
            <div class="prog-hdr"><span>Usage</span><span id="pct-3">—</span></div>
            <div class="prog-track"><div class="prog-fill pf-amber" id="bar-3" style="width:0%"></div></div>
        </div>

        <!-- SECTOR D — University Reserved -->
        <div class="sec-card <?php echo (!$isAdmin) ? 'locked' : ''; ?>">
            <div class="sec-head">
                <div class="sec-name">Sector D</div>
                <span class="badge b-purple"><?php echo $isAdmin ? 'University Reserved' : '🔒 Admin Only'; ?></span>
            </div>
            <div class="capacity-line">Total Capacity: <strong><span id="cap-4">20</span> seats</strong></div>
            <div class="mini-row">
                <div class="mini m-avail">
                    <div class="mini-val" id="avail-4">—</div>
                    <div class="mini-lbl">Available</div>
                </div>
                <div class="mini m-occ">
                    <div class="mini-val" id="occ-4">—</div>
                    <div class="mini-lbl">Occupied</div>
                </div>
                <div class="mini m-booked">
                    <div class="mini-val" id="booked-4">—</div>
                    <div class="mini-lbl">Booked</div>
                </div>
            </div>
            <div class="prog-hdr"><span>Usage</span><span id="pct-4">—</span></div>
            <div class="prog-track"><div class="prog-fill pf-purple" id="bar-4" style="width:0%"></div></div>
        </div>

    </div><!-- /sectors -->

    <!-- System Info -->
    <div class="sysinfo">
        <div><span class="dot"></span><strong>Status:</strong> Online</div>
        <div><strong>Last Updated:</strong> <span id="time2"><?php echo date('h:i:s A'); ?></span></div>
        <div><strong>User:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?> (ID: <?php echo $uid; ?>)</div>
        <?php if($isAdmin): ?><div><strong>Role:</strong> System Admin</div><?php endif; ?>
    </div>

</div><!-- /main -->
</body>
</html>