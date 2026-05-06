<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — UniPark</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F1F3F7;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}

.auth-wrap{width:100%;max-width:460px}

/* LOGO */
.auth-logo{text-align:center;margin-bottom:28px}
.auth-logo .brand{font-size:28px;font-weight:700;color:#111827;letter-spacing:-.5px}
.auth-logo .brand span{color:#2563EB}
.auth-logo .tagline{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:#9CA3AF;margin-top:4px}

/* CARD */
.auth-card{background:#fff;border:1px solid #E5E7EB;border-radius:16px;padding:36px 32px;box-shadow:0 1px 4px rgba(0,0,0,.06)}
.auth-card-title{font-size:20px;font-weight:700;color:#111827;margin-bottom:4px}
.auth-card-sub{font-size:13px;color:#6B7280;margin-bottom:28px}

/* FIELDS */
.field{margin-bottom:16px}
.field label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#6B7280;margin-bottom:6px}
.field input,
.field select{
    width:100%;padding:10px 14px;
    border:1.5px solid #D1D5DB;border-radius:10px;
    font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;color:#111827;
    background:#F9FAFB;outline:none;
    transition:border-color .15s,box-shadow .15s;
    appearance:none;
}
.field input:focus,
.field select:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.1);background:#fff}
.field input::placeholder{color:#9CA3AF}

/* TWO COL */
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:14px}

/* ROLE PICKER */
.role-picker{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.role-opt{position:relative}
.role-opt input[type="radio"]{position:absolute;opacity:0;width:0;height:0}
.role-opt label{
    display:flex;flex-direction:column;align-items:center;justify-content:center;
    padding:11px 8px;border:2px solid #E5E7EB;border-radius:10px;
    cursor:pointer;transition:all .15s;background:#F9FAFB;text-align:center;
}
.role-opt label:hover{border-color:#93C5FD;background:#EFF6FF}
.role-opt input:checked + label{border-color:#2563EB;background:#EFF6FF}
.role-icon{font-size:20px;margin-bottom:4px}
.role-name{font-size:12px;font-weight:700;color:#111827}
.role-id{font-size:10px;color:#9CA3AF;margin-top:2px}

/* INFO BOX */
.info-box{background:#EFF6FF;border:1px solid #BFDBFE;border-radius:9px;padding:11px 14px;font-size:12px;color:#1D4ED8;margin-bottom:18px;display:flex;align-items:flex-start;gap:8px}
.info-box svg{flex-shrink:0;margin-top:1px}

/* BUTTON */
.btn-register{
    width:100%;padding:11px;
    background:#2563EB;color:#fff;border:none;border-radius:10px;
    font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;
    cursor:pointer;transition:background .15s;margin-top:4px;
}
.btn-register:hover{background:#1D4ED8}

/* DIVIDER */
.divider{border:none;border-top:1px solid #F3F4F6;margin:20px 0}

/* FOOTER */
.auth-footer{text-align:center;margin-top:20px;font-size:13px;color:#6B7280}
.auth-footer a{color:#2563EB;font-weight:600;text-decoration:none}
.auth-footer a:hover{text-decoration:underline}

/* SUCCESS */
.success-msg{background:#ECFDF5;border:1px solid #A7F3D0;border-radius:9px;padding:11px 14px;font-size:13px;color:#065F46;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px}
</style>
</head>
<body>

<div class="auth-wrap">

    <!-- Logo -->
    <div class="auth-logo">
        <div class="brand">Uni<span>Park</span></div>
        <div class="tagline">Campus Parking Management System</div>
    </div>

    <!-- Card -->
    <div class="auth-card">
        <div class="auth-card-title">Create an account</div>
        <div class="auth-card-sub">Register to reserve and manage your parking slots.</div>

        <?php
        if (isset($_POST['reg'])) {
            $role   = $_POST['role'];
            $prefix = ($role == 'student') ? '11' : (($role == 'faculty') ? '22' : '33');
            $uid    = $prefix . rand(1000, 9999);
            $name   = mysqli_real_escape_string($conn, $_POST['name']);
            $email  = mysqli_real_escape_string($conn, $_POST['email']);
            $pass   = mysqli_real_escape_string($conn, $_POST['password']);
            $v_no   = mysqli_real_escape_string($conn, $_POST['v_no']);

            mysqli_query($conn, "INSERT INTO user VALUES ('$uid', '$name', '$email', '$pass', '$v_no')");
            $table    = ($role == 'student') ? 'students' : (($role == 'faculty') ? 'faculty' : 'staff');
            $id_field = $role . '_id';
            mysqli_query($conn, "INSERT INTO $table (user_id, $id_field) VALUES ('$uid', '".rand(100,999)."')");

            echo '<div class="success-msg">
                    <svg width="16" height="16" fill="none" viewBox="0 0 16 16"><circle cx="8" cy="8" r="7" stroke="#059669" stroke-width="1.5"/><path d="M5 8l2.5 2.5L11 5.5" stroke="#059669" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Account created! Your User ID is <strong>&nbsp;' . $uid . '</strong>. Please save it — you need it to log in.
                  </div>';
        }
        ?>

        <div class="info-box">
            <svg width="15" height="15" fill="none" viewBox="0 0 16 16"><circle cx="8" cy="8" r="7" stroke="#2563EB" stroke-width="1.4"/><path d="M8 7v4M8 5.5v.5" stroke="#2563EB" stroke-width="1.4" stroke-linecap="round"/></svg>
            Your User ID will be generated automatically based on your role. Save it after registering — you'll need it to log in.
        </div>

        <form method="POST">

            <div class="two-col">
                <div class="field">
                    <label>Full Name</label>
                    <input type="text" name="name" placeholder="e.g. Sadia Ahmed" required>
                </div>
                <div class="field">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="you@university.edu" required>
                </div>
            </div>

            <div class="two-col">
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <div class="field">
                    <label>Vehicle Plate</label>
                    <input type="text" name="v_no" placeholder="e.g. DHA-1234" required>
                </div>
            </div>

            <div class="field">
                <label>Select Your Role</label>
                <div class="role-picker">
                    <div class="role-opt">
                        <input type="radio" name="role" id="r-student" value="student" checked>
                        <label for="r-student">
                            <span class="role-icon">🎓</span>
                            <span class="role-name">Student</span>
                            <span class="role-id">ID: 11xxx</span>
                        </label>
                    </div>
                    <div class="role-opt">
                        <input type="radio" name="role" id="r-faculty" value="faculty">
                        <label for="r-faculty">
                            <span class="role-icon">👨‍🏫</span>
                            <span class="role-name">Faculty</span>
                            <span class="role-id">ID: 22xxx</span>
                        </label>
                    </div>
                    <div class="role-opt">
                        <input type="radio" name="role" id="r-staff" value="staff">
                        <label for="r-staff">
                            <span class="role-icon">🏢</span>
                            <span class="role-name">Staff</span>
                            <span class="role-id">ID: 33xxx</span>
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" name="reg" class="btn-register">Create Account →</button>
        </form>

        <hr class="divider">

        <div class="auth-footer">
            Already have an account? <a href="login.php">Sign in here</a>
        </div>
    </div>

</div>
</body>
</html>