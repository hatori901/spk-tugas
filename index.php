<?php include('components/header.php') ?>
<main class="form-signin w-100 m-auto">
    <form>
        <img class="mb-4 w-75" src="/assets/logo-2.png" alt="">
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

        <div class="form-floating">
            <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Email address</label>
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
<script>
$(document).ready(function() {
    if (localStorage.getItem('login')) {
        window.location.href = 'routes/dashboard.php'
    }
})
$('#login').on('click', function(e) {
    e.preventDefault()
    $('#login').html('Loading...')
    let email = $('input[name="email"]').val()
    let password = $('input[name="password"]').val()
    if (email == '' || password == '') {
        alert('Email dan Password harus diisi')
        $('#login').html('Login')
    } else {
        if (email == 'admin' && password == 'admin') {
            localStorage.setItem('login', true)
            window.location.href = 'routes/dashboard.php'
        } else {
            alert('Email atau Password salah')
        }
        $('#login').html('Login')
    }
})
</script>

<?php include('components/footer.php') ?>