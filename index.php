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
<main class="relative flex items-center bg-[#F0F2F5] h-screen">
    <div class="absolute top-5 left-5">
        <img src="assets/logo-2.png" alt="Login" class="h-[50px]">
    </div>
    <div class="w-full md:w-2/3">
        <div class="w-2/3 md:w-[400px] mx-auto flex flex-col items-center justify-center gap-5">
            <h1 class="text-2xl font-bold">Welcome Back</h1>
            <p>Login into your account</p>
            <form action="<?php $_SERVER['PHP_SELF']?>" method="post" class="w-full flex flex-col gap-y-5">
                <div class="flex flex-col">
                    <input type="text" name="username" id="username" placeholder="Username"
                        class="w-full outline-none px-3 py-3 rounded-md">
                </div>
                <div class="flex flex-col">
                    <input type="password" name="password" id="password" placeholder="Password"
                        class="outline-none px-3 py-3 rounded-md">
                </div>
                <button type="submit"
                    class="rounded-md border border-[#5A5A5A] py-3 hover:bg-[#20DC49] hover:text-white hover:border-none transition duration-300 ease-out">Login</button>
            </form>
        </div>
    </div>
    <div class="hidden md:block relative w-1/3">
        <img src="assets/login-background.png" alt="Login" class="w-full h-screen">
        <div class="absolute bottom-3 bg-gray-500/50 backdrop-blur p-5 mx-5 rounded-md">
            <span class="bg-[#20DC49] rounded-md px-5 py-2 mb-3 font-semibold">
                Best Craft Marketplace in UNY
            </span>
            <p class="text-white mt-5">
                UNY Craft Market, Yogyakarta State’s Universities number 1 craft marketplace provide craftsmen and craft
                enjoyers all over Yogyakarta to buy, sell, and enjoy arts and crafts ranging from traditional, modern,
                and mixed crafts from Indonesia
            </p>
        </div>
    </div>
</main>

<?php include('components/footer.php') ?>