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
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" id="form_login" method="post">
                                        <div class="form-group">
                                            <input octavalidate="R,EMAIL" name="email" type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                                        </div>
                                        <div class="form-group">
                                            <input octavalidate="R" minlength="8" name="pass" type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.php">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toast = new Toasty({
                transition: "pinItDown"
            });

            $('#form_login').on('submit', (e) => {
                e.preventDefault();
                //init validation library
                const ov = new octaValidate("form_login");
                //validate the form
                if (ov.validate()) {
                    //set form data
                    const fd = new FormData(e.target);
                    //send request
                    fetch('api/users/userLogin.php', {
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
                                    window.location.href = "./admin/dashboard";
                                }, 3000)
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
</body>

</html>