<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <main class="main-content mt-0">
        <section class="d-flex align-items-center justify-content-center vh-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-lg-5 col-md-6">
                        <div class="card card-plain mt-8 p-4">
                            <div class="card-header text-left bg-transparent">
                                <h3 class="font-weight-bolder text-info text-gradient card-title">Welcome back</h3>
                                <p class="mb-0 card-subtitle">Create a new account</p>
                                <p class="mb-0 card-subtitle">OR Sign in with these credentials:</p>
                                <p class="mb-0 card-subtitle">Email: <b>admin@softui.com</b></p>
                                <p class="mb-0 card-subtitle">Password: <b>secret</b></p>
                            </div>
                            <div class="card-body">
                                <form role="form" method="POST" action="/session">
                                    @csrf
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="admin@softui.com">
                                        @error('email')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label>Password</label>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="secret">
                                        @error('password')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                                        <label class="form-check-label" for="rememberMe">Remember me</label>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-info w-100 mt-4 mb-0">Sign in</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center">
                                <small class="text-muted">Forgot your password? Reset it
                                    <a href="/login/forgot-password" class="text-info font-weight-bold">here</a>
                                </small>
                                <p class="mb-4 text-sm">
                                    Don't have an account?
                                    <a href="register" class="text-info font-weight-bold">Sign up</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>


</body>
</html>





<style>
    body {
        background: linear-gradient(to bottom right, #e0eafc, #cfdef3); /* Soothing gradient background */
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        font-family: 'Roboto', sans-serif;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        background-color: rgba(255, 255, 255, 0.9);
    }

    .card-header {
        background: linear-gradient(to right, #2196f3, #64b5f6);
        color: white;
        padding: 20px;
        text-align: center;
    }

    .card-title {
        font-weight: 600;
        margin-bottom: 10px;
    }

    .card-subtitle {
        font-size: 0.9rem;
        color: #eee;
    }

    .card-body {
        padding: 30px;
    }

    label {
        color: #777;
        margin-bottom: 5px;
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 12px;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #2196f3;
        outline: none;
        box-shadow: 0 0 5px rgba(33, 150, 243, 0.2);
    }

    .btn-info {
        background: linear-gradient(to right, #2196f3, #64b5f6);
        border: none;
        border-radius: 8px;
        padding: 12px 25px;
        font-weight: 500;
        transition: background 0.3s ease;
    }

    .btn-info:hover {
        background: linear-gradient(to right, #1976d2, #42a5f5);
        box-shadow: 0 2px 5px rgba(33, 150, 243, 0.2);
    }

    .form-check-input {
        border-radius: 5px;
    }

    .text-info {
        color: #2196f3 !important;
    }

    .text-muted {
        color: #999;
    }

    .card-footer {
        padding: 20px;
        border-top: 1px solid #eee;
        background-color: rgba(255, 255, 255, 0.9);
        border-bottom-left-radius: 15px;
        border-bottom-right-radius: 15px;
    }

    .container {
        max-width: 400px;
    }
    </style>
