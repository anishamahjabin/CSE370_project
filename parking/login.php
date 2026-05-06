<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — UniPark</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F1F3F7;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}

.auth-wrap{width:100%;max-width:420px}

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
.field input{
    width:100%;padding:10px 14px;
    border:1.5px solid #D1D5DB;border-radius:10px;
    font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;color:#111827;
    background:#F9FAFB;outline:none;
    transition:border-color .15s,box-shadow .15s;
}
.field input:focus{border-color:#2563EB;box-shadow:0 0 0 3px rgba(37,99,235,.1);background:#fff}
.field input::placeholder{color:#9CA3AF}

/* ERROR */
.error-msg{background:#FEF2F2;border:1px solid #FECACA;border-radius:9px;padding:11px 14px;font-size:13px;color:#DC2626;font-weight:500;margin-bottom:18px;display:flex;align-items:center;gap:8px}

/* BUTTON */
.btn-login{
    width:100%;padding:11px;
    background:#2563EB;color:#fff;border:none;border-radius:10px;
    font-family:'Plus Jakarta Sans',sans-serif;font-size:14px;font-weight:700;
    cursor:pointer;transition:background .15s;margin-top:4px;
}
.btn-login:hover{background:#1D4ED8}

/* FOOTER LINK */
.auth-footer{text-align:center;margin-top:20px;font-size:13px;color:#6B7280}
.auth-footer a{color:#2563EB;font-weight:600;text-decoration:none}
.auth-footer a:hover{text-decoration:underline}

/* DIVIDER */
.divider{border:none;border-top:1px solid #F3F4F6;margin:20px 0}
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
        <div class="auth-card-title">Welcome back 👋</div>
        <div class="auth-card-sub">Sign in to access your parking dashboard.</div>

        <?php
        if (isset($_POST['login'])) {
            $uid  = mysqli_real_escape_string($conn, $_POST['uid']);
            $pass = $_POST['pass'];

            $query  = "SELECT * FROM user WHERE user_id = '$uid' AND password = '$pass'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
                $_SESSION['uid']  = $user_data['user_id'];
                $_SESSION['name'] = $user_data['name'];
                header("Location: dashboard.php");
                exit;
            } else {
                echo '<div class="error-msg">
                        <svg width="16" height="16" fill="none" viewBox="0 0 16 16"><circle cx="8" cy="8" r="7" stroke="#DC2626" stroke-width="1.5"/><path d="M8 5v3M8 10.5v.5" stroke="#DC2626" stroke-width="1.5" stroke-linecap="round"/></svg>
                        Invalid User ID or Password. Please try again.
                      </div>';
            }
        }
        ?>

        <form method="POST">
            <div class="field">
                <label>User ID</label>
                <input type="text" name="uid" placeholder="e.g. 11001" required
                    value="<?php echo isset($_POST['uid']) ? htmlspecialchars($_POST['uid']) : ''; ?>">
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="pass" placeholder="••••••••" required>
            </div>
            <button type="submit" name="login" class="btn-login">Sign In →</button>
        </form>

        <hr class="divider">

        <div class="auth-footer">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>

</div>
</body>
</html>