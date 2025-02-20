<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
    <div class="card">
        <h2>Forgot Password</h2>
        <div id="messageBox" class="alert-custom"></div>

        <form id="forgotPasswordForm">
            <div class="mb-3">
                <input type="email" id="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <button type="button" class="btn btn-primary w-100" onclick="sendOtp()">Send OTP</button>
        </form>

        <form id="resetPasswordForm" style="display: none;">
            <div class="mb-3">
                <input type="text" id="otp" class="form-control" placeholder="Enter OTP" required>
            </div>
            <div class="mb-3">
                <input type="password" id="password" class="form-control" placeholder="New Password" required>
            </div>
            <div class="mb-3">
                <input type="password" id="confirm_password" class="form-control" placeholder="Confirm Password" required>
            </div>
            <button type="button" class="btn btn-success w-100" onclick="verifyOtp()">Reset Password</button>
        </form>

        <a href="{{ route('login') }}" class="d-block mt-3 text-light">Back to Login</a>
    </div>

    <script>
        function showMessage(message, type) {
            let messageBox = document.getElementById('messageBox');
            messageBox.innerText = message;
            messageBox.style.color = type === "success" ? "lightgreen" : "red";
            messageBox.style.display = "block";
        }

        function sendOtp() {
            let email = document.getElementById('email').value;

            fetch("{{ route('send.reset.otp') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage("OTP Sent Successfully!", "success");
                    document.getElementById('forgotPasswordForm').style.display = "none";
                    document.getElementById('resetPasswordForm').style.display = "block";
                } else {
                    showMessage("Error: " + (data.error || "Unknown error"), "error");
                }
            })
            .catch(error => {
                console.error("Fetch Error:", error);
                showMessage("An error occurred. Please check the console.", "error");
            });
        }

        function verifyOtp() {
            let otp = document.getElementById('otp').value;
            let password = document.getElementById('password').value;
            let confirmPassword = document.getElementById('confirm_password').value;

            fetch("{{ route('verify.reset.otp') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ otp: otp, password: password, password_confirmation: confirmPassword })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage("Password Reset Successful! Redirecting...", "success");
                    setTimeout(() => window.location.href = "{{ route('login') }}", 2000);
                } else {
                    showMessage("Error: " + data.error, "error");
                }
            });
        }
    </script>
</body>
</html>
