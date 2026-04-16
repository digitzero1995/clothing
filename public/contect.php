<?php
session_start();

$name = $email = $message = "";
$nameErr = $emailErr = $messageErr = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["name"]))) {
        $nameErr = "Name is required";
    } else {
        $name = htmlspecialchars(trim($_POST["name"]));
    }

    if (empty(trim($_POST["email"]))) {
        $emailErr = "Email is required";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    } else {
        $email = htmlspecialchars(trim($_POST["email"]));
    }

    if (empty(trim($_POST["message"]))) {
        $messageErr = "Message is required";
    } else {
        $message = htmlspecialchars(trim($_POST["message"]));
    }

    if (empty($nameErr) && empty($emailErr) && empty($messageErr)) {
        $successMsg = "Thank you, $name! Your message has been received.";
        $name = $email = $message = "";

        // Store a JS alert to show after reload
        $_SESSION['contact_success'] = $successMsg;

        // Reload page to prevent resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

include 'header.php';
?>

<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
  crossorigin="anonymous"
  referrerpolicy="no-referrer"
/>

<style>
/* ===== Contact Form Styles ===== */
body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #181818ff, #faf9fbff); min-height:100vh; display:flex; justify-content:center; align-items:center; margin:0; padding:20px;}
.contact-container { position: relative; background: rgba(255,255,255,0.15); border-radius:20px; box-shadow:0 8px 32px 0 rgba(31,38,135,0.37),0 4px 16px 0 rgba(31,38,135,0.2); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border:1px solid rgba(255,255,255,0.18); width:100%; max-width:550px; padding:40px 50px 50px 50px; color:#0a0202ff; animation:fadeInUp 0.9s ease forwards;}
@keyframes fadeInUp { from {opacity:0; transform:translateY(30px);} to {opacity:1; transform:translateY(0);} }
.contact-container h2 { font-size:2.8rem; font-weight:700; text-align:center; margin-bottom:40px; letter-spacing:2px; text-shadow:0 3px 10px rgba(255,255,255,0.4); user-select:none; }
.input-group { position: relative; margin-bottom:35px; }
input[type="text"], input[type="email"], textarea { width:100%; padding:18px 18px 18px 16px; background:transparent; border:2px solid black; border-radius:12px; font-size:1.1rem; color:#121111ff; box-shadow: inset 0 0 5px rgba(122,156,199,0.12); transition: box-shadow 0.3s ease, border-color 0.3s ease; resize: vertical; }
input:focus, textarea:focus { outline:none; border-color:black; box-shadow:0 0 12px 2px #4d39caff, inset 0 0 8px rgba(255,255,255,0.4); }
textarea { min-height:130px; }
label { position:absolute; top:18px; left:16px; color: rgba(19,19,19,0.75); font-size:1rem; font-weight:600; pointer-events:none; user-select:none; transition:0.3s ease all; }
input:focus + label, input:not(:placeholder-shown) + label, textarea:focus + label, textarea:not(:placeholder-shown) + label { top:-10px; left:12px; font-size:0.85rem; color:#51192bff; background: rgba(255,255,255,0.15); padding:0 6px; border-radius:6px; box-shadow:0 0 10px #571b43ff; }
.error-msg { color:#ff6b6b; font-weight:700; font-size:0.9rem; margin-top:-24px; margin-bottom:8px; user-select:none; }
.success-msg { background: rgba(46,204,113,0.85); color:#fff; font-weight:700; padding:20px; border-radius:15px; text-align:center; margin-bottom:30px; box-shadow:0 0 12px #2ecc71; user-select:none; }
button.submit-btn { width:100%; padding:18px; font-size:1.25rem; font-weight:700; color:#fff; background: linear-gradient(45deg,#67dad2ff,#0d4180ff); border:none; border-radius:16px; cursor:pointer; box-shadow:0 0 15px #9b59b6,0 4px 10px rgba(0,0,0,0.25); transition: background 0.4s ease, box-shadow 0.4s ease, transform 0.2s ease; user-select:none; }
button.submit-btn:hover { background:linear-gradient(45deg,#8e44ad,#732d91); box-shadow:0 0 25px #60a0dbff,0 6px 16px rgba(0,0,0,0.3); transform:scale(1.05); }
button.submit-btn:active { transform:scale(0.97); }
.contact-info { margin-top:50px; text-align:center; color:rgba(3,0,0,0.85); font-size:1.1rem; font-weight:600; user-select:none; }
.contact-info p { margin-bottom:20px; letter-spacing:0.8px; }
.contact-info a { color:#03000cff; text-decoration:none; margin:0 16px; font-weight:700; display:inline-flex; align-items:center; gap:10px; transition: color 0.3s ease, transform 0.3s ease; }
.contact-info a:hover { color:#59aab6ff; transform:scale(1.15); }
.contact-info i { font-size:1.6rem; }
@media (max-width:600px){ .contact-container{ padding:30px 25px 40px 25px;} .contact-container h2{ font-size:2.2rem; margin-bottom:30px;} }
</style>

<div class="contact-container" role="main" aria-labelledby="contactTitle" tabindex="0">
  <h2 id="contactTitle">Contact Us</h2>

  <form method="POST" action="" novalidate>
    <div class="input-group">
      <input type="text" id="name" name="name" value="<?php echo $name; ?>" placeholder=" " aria-required="true" aria-describedby="nameError" />
      <label for="name">Name <span aria-hidden="true" style="color:#ff6b6b;">*</span></label>
      <?php if ($nameErr): ?><div class="error-msg" id="nameError"><?php echo $nameErr; ?></div><?php endif; ?>
    </div>

    <div class="input-group">
      <input type="email" id="email" name="email" value="<?php echo $email; ?>" placeholder=" " aria-required="true" aria-describedby="emailError" />
      <label for="email">Email <span aria-hidden="true" style="color:#ff6b6b;">*</span></label>
      <?php if ($emailErr): ?><div class="error-msg" id="emailError"><?php echo $emailErr; ?></div><?php endif; ?>
    </div>

    <div class="input-group">
      <textarea id="message" name="message" placeholder=" " aria-required="true" aria-describedby="messageError"><?php echo $message; ?></textarea>
      <label for="message">Message / Feedback <span aria-hidden="true" style="color:#ff6b6b;">*</span></label>
      <?php if ($messageErr): ?><div class="error-msg" id="messageError"><?php echo $messageErr; ?></div><?php endif; ?>
    </div>

    <button type="submit" class="submit-btn" aria-label="Send your message">Send Message</button>
  </form>

  <div class="contact-info" aria-label="Other ways to contact us">
    <p>Connect with us:</p>
    <a href="mailto:2301031000061@silveroakuni.ac.in" target="_blank" rel="noopener" title="Send Email">
      <i class="fa-solid fa-envelope" aria-hidden="true"></i>Email
    </a>
    <a href="https://instagram.com/krrishpatel_02" target="_blank" rel="noopener" title="Instagram Profile">
      <i class="fa-brands fa-instagram" aria-hidden="true"></i>Instagram
    </a>
  </div>
</div>


<?php
// JS popup for success message
if (isset($_SESSION['contact_success'])) {
    echo "<script>alert('" . $_SESSION['contact_success'] . "');</script>";
    unset($_SESSION['contact_success']);
}
?>