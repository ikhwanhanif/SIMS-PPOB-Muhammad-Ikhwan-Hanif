<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - SIMS PPOB</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="min-h-screen flex">

  <div class="w-full md:w-1/2 flex flex-col justify-center items-center px-8 py-12 bg-white">
    <div class="w-full max-w-sm text-center">

      <div class="flex justify-center items-center gap-2 mb-6">
        <img src="/assets/logo.png" class="w-6 h-6" alt="logo">
        <span class="text-lg font-semibold">SIMS PPOB</span>
      </div>

      <h2 class="text-2xl font-bold mb-8">Masuk atau buat akun<br>untuk memulai</h2>

      <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-red-100 text-red-700 p-2 text-sm mb-4 rounded"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <form action="/login" method="post" class="space-y-4 text-left">
        <div class="relative">
          <i class="ph ph-envelope-simple absolute left-3 top-3.5 text-gray-400"></i>
          <input type="email" name="email" placeholder="masukan email anda" required
            class="pl-10 pr-4 py-2 w-full border rounded-md focus:outline-none focus:ring-2 focus:ring-red-400" />
        </div>

        <div class="relative">
          <i class="ph ph-lock-simple absolute left-3 top-3.5 text-gray-400"></i>
          <input type="password" id="password" name="password" placeholder="masukan password anda" required
            class="pl-10 pr-10 py-2 w-full border rounded-md focus:outline-none focus:ring-2 focus:ring-red-400" />
          <button type="button" onclick="togglePassword()" class="absolute right-3 top-3.5 text-gray-400 focus:outline-none">
            <i id="eye-icon" class="ph ph-eye"></i>
          </button>
        </div>


        <button type="submit"
          class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-md font-semibold">Masuk</button>
      </form>

      <p class="text-sm text-center mt-6 text-gray-500">
        belum punya akun? <a href="/register" class="text-red-600 font-medium hover:underline">registrasi di sini</a>
      </p>

    </div>
  </div>

  <div class="hidden md:block md:w-1/2 bg-pink-50">
    <img src="/assets/Illustrasi Login.png" alt="Login Illustration" class="object-cover w-full h-full">
  </div>
  <script>
  function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');

    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      eyeIcon.classList.remove('ph-eye');
      eyeIcon.classList.add('ph-eye-slash');
    } else {
      passwordInput.type = 'password';
      eyeIcon.classList.remove('ph-eye-slash');
      eyeIcon.classList.add('ph-eye');
    }
  }
  </script>
</body>


</html>