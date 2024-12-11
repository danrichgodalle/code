
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Login Page</title>
</head>
<body>
    <div class="container login-container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="row w-100">
            <div class="col-md-5 welcome-column">
                <div class="welcome-message">             
                    <i class="fas fa-lock security-icon"></i>
                    <h4>Welcome to File Encryption</h4>
                    <p>Your file will be secure with our top-tier encryption technology.</p>
                </div>
            </div>
            <div class="col-md-7 login-column">
                <div class="card login-card">
                    <h3 class="text-center mb-4">Login</h3>
                    <form action="login.php" method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required aria-label="Username">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required aria-label="Password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
                    </form>
                  
                    <div class="text-center forgot-password">
                        <a href="#">Forgot Password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
