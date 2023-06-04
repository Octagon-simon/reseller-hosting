<?php

$showResetForm = false;

if (isset($_GET) && !empty($_GET['token']) && !empty($_GET['uid']) && !empty($_GET['exp'])) {
    //get the token
    $token = htmlspecialchars($_GET['token']);
    //get the user email
    $uid = htmlspecialchars(base64_decode($_GET['uid']));
    //get the expiry time
    $exp = intval($_GET['exp']);
    //show reset form
    $showResetForm = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'includes/head.php' ?>
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                                        <p class="mb-4">We get it, stuff happens. Just enter your email address below
                                            and we'll send you a link to reset your password!</p>
                                    </div>

                                    <?php if (!$showResetForm) : ?>
                                        <form class="user" id="form_send_link" method="post">
                                            <div class="form-group">
                                                <input name="email" octavalidate="R,EMAIL" type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                                Continue
                                            </button>
                                        </form>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', () => {
                                                const toast = new Toasty({
                                                    transition: "pinItDown"
                                                });

                                                $('#form_send_link').on('submit', (e) => {
                                                    e.preventDefault();
                                                    //init validation library
                                                    const ov = new octaValidate("form_send_link");
                                                    //validate the form
                                                    if (ov.validate()) {
                                                        const fd = new FormData(e.target);

                                                        //send request
                                                        fetch('api/users/sendResetLink.php', {
                                                                method: "post",
                                                                mode: "cors",
                                                                body: fd
                                                            })
                                                            .then(res => res.json())
                                                            .then((res) => {
                                                                if (res.success) {
                                                                    toast.success(res.data.message);
                                                                } else {
                                                                    toast.error(res.data.message);
                                                                    //check if its a form error 
                                                                    if (res.data.formError && Object.entries(res.data.formErrors)) {
                                                                        showErrors(res.data.formErrors)
                                                                    }
                                                                }
                                                            })
                                                            .catch(err => {
                                                                console.error(err);
                                                                toast.error("An Error has occured")
                                                            })
                                                    } else {
                                                        toast.error("Form validation failed")
                                                    }
                                                })
                                            })
                                        </script>
                                    <?php else : ?>
                                        <form class="user" id="form_reset" method="post">
                                            <input type="hidden" name="token" value="<?php print($token); ?>" />
                                            <input type="hidden" name="exp" value="<?php print($exp); ?>" />
                                            <div class="form-group">
                                                <input readonly name="uid" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" value="<?php print($uid); ?>">
                                            </div>
                                            <div class="form-group">
                                                <input octavalidate="R" minlength="8" name="pass" type="password" class="form-control form-control-user" id="exampleInputPass" aria-describedby="emailHelp" placeholder="Enter new password...">
                                            </div>
                                            <div class="form-group">
                                                <input octavalidate="R" minlength="8" equalto="exampleInputPass" name="con_pass" type="password" class="form-control form-control-user" id="exampleInputConPass" aria-describedby="emailHelp" placeholder="Re-enter password...">
                                            </div>
                                            <input type="hidden" name="uid" value="<?php print($uid); ?>" />
                                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                                Reset password
                                            </button>
                                        </form>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', () => {
                                                const toast = new Toasty({
                                                    transition: "pinItDown"
                                                });

                                                $('#form_reset').on('submit', (e) => {
                                                    e.preventDefault();
                                                    //init validation library
                                                    const ov = new octaValidate("form_reset");
                                                    //validate the form
                                                    if (ov.validate()) {
                                                        const fd = new FormData(e.target);

                                                        //send request
                                                        fetch('api/users/resetPassword.php', {
                                                                method: "post",
                                                                mode: "cors",
                                                                body: fd
                                                            })
                                                            .then(res => res.json())
                                                            .then((res) => {
                                                                if (res.success) {
                                                                    toast.success(res.data.message);
                                                                    //redirect to login
                                                                    setTimeout(() => {
                                                                        windows.location.href = "index.php"
                                                                    }, 3000)
                                                                } else {
                                                                    toast.error(res.data.message);
                                                                    //check if its a form error 
                                                                    if (res.data.formError && Object.entries(res.data.formErrors)) {
                                                                        ov.showBackendErrors(res.data.formErrors)
                                                                    }
                                                                }
                                                            })
                                                            .catch(err => {
                                                                console.error(err);
                                                                toast.error("An Error has occured")
                                                            })
                                                    } else {
                                                        toast.error("Form validation failed")
                                                    }
                                                })
                                            })
                                        </script>
                                    <?php endif; ?>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="index.php">Already have an account? Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <?php include 'includes/foot.php' ?>

</body>

</html>