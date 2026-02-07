<!DOCTYPE html>
<html>
<head>
<title>Department Login</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:"Times New Roman", Times, serif;
}

/* Background */
body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:linear-gradient(135deg, #0f4c81, #d62828);
}

/* Login Box */
.box{
    width:360px;
    padding:35px 30px;
    background:#ffffff;
    border-radius:15px;
    box-shadow:0 20px 40px rgba(0,0,0,0.3);
    animation:fadeIn 0.8s ease-in-out;
}

/* Title */
.box h2{
    text-align:center;
    color:#0f4c81;
    margin-bottom:25px;
    letter-spacing:1px;
    border-bottom:3px solid #d62828;
    padding-bottom:10px;
}

/* Input container for alignment */
.input-container {
    position: relative;
    width: 100%;
    margin-bottom: 18px;
}

/* Inputs */
.input-container input{
    width:100%;
    padding:12px 14px;
    border:2px solid #dcdcdc;
    border-radius:8px;
    outline:none;
    font-size:15px;
    transition:0.3s;
}

.input-container input:focus{
    border-color:#0f4c81;
    box-shadow:0 0 5px rgba(15,76,129,0.5);
}

/* Password icon */
.input-container.password-container input {
    padding-right:40px; /* extra space for icon */
}

.password-container .toggle-password{
    position: absolute;
    right:12px;
    top:50%;
    transform: translateY(-50%);
    cursor:pointer;
    width:24px;
    height:24px;
    fill:#0f4c81;
    transition:0.3s;
}

.password-container .toggle-password:hover {
    fill:#d62828;
}

/* Button */
button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    background:linear-gradient(135deg,#0f4c81,#1e88e5);
    color:#ffffff;
    transition:0.3s;
}

button:hover{
    background:linear-gradient(135deg,#d62828,#b71c1c);
    box-shadow:0 10px 20px rgba(214,40,40,0.4);
}

/* Error Message */
.error{
    color:#b71c1c;
    background:#ffe5e5;
    padding:8px;
    border-radius:6px;
    text-align:center;
    margin-bottom:15px;
    font-size:14px;
    border:1px solid #f5c2c2;
}

/* Animation */
@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(20px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}
</style>

</head>
<body>

<div class="box">
    <h2>Admin & Department Login</h2>

    <?php if(isset($_GET['error'])){ ?>
        <p class="error">Invalid Username or Password</p>
    <?php } ?>

    <form method="post" action="login_check.php">

        <!-- Username input -->
        <div class="input-container">
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <!-- Password input -->
        <div class="input-container password-container">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <svg class="toggle-password" onclick="togglePassword()" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12c-2.761 0-5-2.238-5-5s2.239-5 5-5 5 2.238 5 5-2.239 5-5 5zm0-8c-1.657 0-3 1.344-3 3s1.343 3 3 3 3-1.344 3-3-1.343-3-3-3z"/>
            </svg>
        </div>

        <button type="submit">Login</button>
    </form>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = document.querySelector('.toggle-password path');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.setAttribute('d', 'M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12c-2.761 0-5-2.238-5-5s2.239-5 5-5 5 2.238 5 5-2.239 5-5 5z'); // eye-slash path
    } else {
        passwordInput.type = 'password';
        icon.setAttribute('d', 'M12 5c-7.633 0-12 7-12 7s4.367 7 12 7 12-7 12-7-4.367-7-12-7zm0 12c-2.761 0-5-2.238-5-5s2.239-5 5-5 5 2.238 5 5-2.239 5-5 5zm0-8c-1.657 0-3 1.344-3 3s1.343 3 3 3 3-1.344 3-3-1.343-3-3-3z'); // eye path
    }
}
</script>

</body>
</html>
