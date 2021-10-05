<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login</title>
    <?php
    $page = 2;
    include("csslinks.php");
    ?>
</head>

<body>
    <?php
    include("navbar.php");
    include_once('connection.php');
    $username = "";
    $currentpwd = "";
    $newpwd = "";
    $confirmpwd = "";
    if (isset($_POST['submit'])) {

        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $currentpwd = $_POST['curr-pass'];
        $newpwd = $_POST['new-pass'];
        $confirmpwd = $_POST['new-confirm-pass'];
        $oldhash = sha1($currentpwd);

        if ($newpwd != $confirmpwd) {
            $_SESSION['message'] = "New password and confirm password is not the same! Try again.";
            $_SESSION['type'] = "danger";
        } else {
            $hash = sha1($newpwd);
        }

        $CPquery = mysqli_query($conn, "SELECT username,password_hash FROM smartiesproject.member where username = '$username' AND password_hash='$oldhash' ");
        $num =  mysqli_fetch_array($CPquery);
        if ($num == 0) {
            $_SESSION['message'] = 'Username or Current Password does not exists';
            $_SESSION['type'] = 'danger';
        }

        if ($newpwd != $confirmpwd || $num == 0) {
            include("message.php");
        } elseif (!empty($hash) && $num > 0) {
            $query = "UPDATE member set password_hash = '$hash' where username = '$username'";
            if (mysqli_query($conn, $query)) {
                $_SESSION['message'] = 'Password changed successfully!';
                $_SESSION['type'] = 'success';
                if (isset($_SESSION['username'])) {
                    echo "
                    <script>
                    location.href = 'index.php';
                    </script>
                    ";
                } else {
                    echo "
                    <script>
                    location.href = 'Login.php';
                    </script>
                    ";
                }
            } else {
                $_SESSION['message'] = 'Failure to changed password: ' . mysqli_error($conn);
                $_SESSION['type'] = 'danger';
                include("message.php");
            }
        }
    }

    ?>

    <body class="bg-gradient-primary">

        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-9 col-lg-12 col-xl-10">
                    <div class="card shadow-lg o-hidden border-0 my-5">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-flex">
                                    <div class="flex-grow-1 bg-login-image" style="background-image: url(&quot;assets/img/1566798061-exam-online.png&quot;);">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h4 class="text-dark mb-4">Reset your password!</h4>
                                        </div>
                                        <form class="user" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                            <div class="form-group"><input class="form-control form-control-user" value="<?php if (isset($_SESSION['username'])) {
                                                                                                                                echo $_SESSION['username'];
                                                                                                                            } elseif ($_SESSION['reset_pass_username']) {
                                                                                                                                echo $_SESSION['reset_pass_username'];
                                                                                                                            } else {
                                                                                                                                echo $username;
                                                                                                                            } ?>" type="text" name="username" placeholder="Username">
                                            </div>
                                            <div class="form-group"><input class="form-control form-control-user" value="<?php if ($_SESSION['Random_pass']) {
                                                                                                                                echo $_SESSION['Random_pass'];
                                                                                                                            } else {
                                                                                                                                echo $currentpwd;
                                                                                                                            } ?>" class="form-control" type="password" name="curr-pass" placeholder="Current password" minlength="1" maxlength="25" required>
                                            </div>
                                            <div class="form-group"><input class="form-control form-control-user" value="<?php echo $newpwd; ?>" class="form-control" type="password" name="new-pass" placeholder="New password" minlength="1" maxlength="25" required>
                                            </div>
                                            <div class="form-group"><input class="form-control form-control-user" value="<?php echo $confirmpwd; ?>" class="form-control" type="password" name="new-confirm-pass" placeholder="Confirm new password" minlength="1" maxlength="25" required>
                                            </div>

                                            <button class="btn btn-primary btn-block text-white btn-user" name="submit" type="submit">Change Password</button>

                                        </form>
                                        <div class="text-center"><a class="small" href="login.php">Go back to login</a>
                                        </div>
                                        <div class="text-center"><a class="small" href="register.php">Go back to
                                                resgister</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include('jslinks.php');

        ?>


    </body>

</html>