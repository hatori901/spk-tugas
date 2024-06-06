<?php include('components/header.php') ?>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $login = select('users', "username = '$username' AND password = '$password'");
    if ($login) {
        $_SESSION['login'] = true;
        header('Location: routes/dashboard.php');
    } else {
        echo "<script>alert('Username atau Password salah')</script>";
    }
}

?>
<main class="form-signin w-100 m-auto">
    <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
        <img class="mb-4 w-75" src="/assets/logo-2.png" alt="">
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

        <div class="form-floating">
            <input type="text" name="username" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Username</label>
        </div>
        <div class="form-floating">
            <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
            <label for="floatingPassword">Password</label>
        </div>

        <div class="form-check text-start my-3">
            <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault">
                Remember me
            </label>
        </div>
        <button class="btn btn-primary w-100 py-2" id="login" type="submit">Login</button>
        <p class="mt-5 mb-3 text-body-secondary">&copy; 2017–2024</p>
    </form>
</main>

<?php include('components/footer.php') ?>