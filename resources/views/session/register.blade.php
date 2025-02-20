<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
        }
        .card {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 400px;
            text-align: center;
            color: white;
            animation: fadeIn 1s ease-in-out;
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            text-align: center;
            display: none;
        }
        h2 {
            color: #f8f9fa;
        }
        .btn-primary, .btn-success {
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #ff9800;
            transform: scale(1.05);
        }
        .btn-success:hover {
            background: #4caf50;
            transform: scale(1.05);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="overlay" id="overlayMessage"></div>

    <div class="card">
        <h2>Register</h2>
        <div id="messageBox" class="alert-custom"></div>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="Name" required>
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3 d-flex gap-2">
                <input type="text" name="otp" class="form-control" placeholder="Enter OTP" required disabled>
                <button type="button" class="btn btn-primary" onclick="sendOtp()">Send OTP</button>
                <button type="button" class="btn btn-success" onclick="verifyOtp()">Verify</button>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required disabled>
            </div>
            <div class="mb-3">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required disabled>
            </div>
            <button type="submit" class="btn btn-primary w-100" disabled>Register</button>
        </form>
        <a href="{{ route('login') }}" class="d-block mt-3 text-light">Already have an account? Login</a>
    </div>

    <script>
        let generatedOtp = null;
        let otpTimestamp = null;
        let countdownInterval = null;

        function showMessage(message, type) {
            let messageBox = document.getElementById('messageBox');
            messageBox.innerText = message;
            messageBox.style.background = type === "success" ? "rgba(76, 175, 80, 0.7)" : "rgba(255, 87, 34, 0.7)";
            messageBox.style.display = "block";

            setTimeout(() => {
                messageBox.style.display = "none";
            }, 3000);
        }

        function sendOtp() {
            let email = document.querySelector('input[name="email"]').value;
            let otpInput = document.querySelector('input[name="otp"]');
            let sendOtpButton = document.querySelector('.btn-primary');

            showMessage("Sending OTP...", "info");
            sendOtpButton.disabled = true;

            fetch('/send-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    generatedOtp = data.otp;
                    otpTimestamp = new Date().getTime();
                    otpInput.disabled = false;
                    startCountdown(60);
                    showMessage("OTP Sent Successfully! Valid for 1 minute.", "success");
                } else if (data.error === "user_exists") {
                    showMessage("⚠️ User is already registered! Please login.", "error");
                    sendOtpButton.disabled = false;
                } else {
                    showMessage("Failed to send OTP. Try again.", "error");
                    sendOtpButton.disabled = false;
                }
            })
            .catch(() => {
                showMessage("Something went wrong. Please try again.", "error");
                sendOtpButton.disabled = false;
            });
        }

        function startCountdown(seconds) {
            let sendOtpButton = document.querySelector('.btn-primary');
            let countdown = seconds;

            sendOtpButton.innerText = `Resend OTP (${countdown}s)`;
            sendOtpButton.disabled = true;

            countdownInterval = setInterval(() => {
                countdown--;
                sendOtpButton.innerText = `Resend OTP (${countdown}s)`;

                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    sendOtpButton.innerText = "Send OTP";
                    sendOtpButton.disabled = false;
                }
            }, 1000);
        }

        function verifyOtp() {
    let enteredOtp = document.querySelector('input[name="otp"]').value;
    let passwordInputs = document.querySelectorAll('input[type="password"]');
    let registerButton = document.querySelector('button[type="submit"]');
    let sendOtpButton = document.querySelector('.btn-primary');

    let currentTime = new Date().getTime();
    if (!generatedOtp || (currentTime - otpTimestamp) > 60000) {
        showMessage("⚠️ OTP expired! Request a new one.", "error");
        return;
    }

    if (enteredOtp == generatedOtp) {
        showMessage("✅ OTP Verified Successfully!", "success");

        // Stop the countdown timer
        clearInterval(countdownInterval);
        sendOtpButton.innerText = "Verified ✅";
        sendOtpButton.disabled = true;

        // Enable password fields and register button
        passwordInputs.forEach(input => input.disabled = false);
        registerButton.disabled = false;
    } else {
        showMessage("❌ Invalid OTP. Please try again.", "error");
    }
}

    </script>
</body>
</html>
