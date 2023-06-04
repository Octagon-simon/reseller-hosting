<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'includes/head.php' ?>
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" id="form_register" method="post">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input name="first_name" type="text" class="form-control form-control-user" id="exampleFirstName" placeholder="First Name">
                                    </div>
                                    <div class="col-sm-6">
                                        <input name="last_name" type="text" class="form-control form-control-user" id="exampleLastName" placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input name="email" type="email" class="form-control form-control-user" id="exampleInputEmail" placeholder="Email Address">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input name="pass" type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input name="con_pass" type="password" class="form-control form-control-user" id="exampleRepeatPassword" placeholder="Repeat Password">
                                    </div>
                                </div>
                                <button type="submit" form="form_register" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="forgot-password.html">Forgot Password?</a>
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
    <?php include 'includes/foot.php' ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toast = new Toasty({
                transition: "pinItDown"
            });

            $('#form_register').on('submit', (e) => {
                e.preventDefault();

                const fd = new FormData(e.target);

                //send request
                fetch('./app/api/userController/userRegistration.php', {
                        method: "post",
                        mode: "cors",
                        body: fd
                    })
                    .then(res => res.json())
                    .then((res) => {
                        if (res.success) {
                            toast.success(res.data.message);
                            //redirect to login
                            setTimeout( () => {
                                window.location.href = "index.php";
                            }, 3000)
                        } else {
                            toast.error(res.data.message)
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        toast.error("An Error has occured")
                    })
            })
        })
    </script>
</body>

</html>