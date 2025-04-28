<header style="display: flex; justify-content: space-between; align-items: center; padding: 20px; border-bottom: 1px solid #ccc;">
<a href="/home" style="text-decoration: none;"><div style="display: flex; align-items: center; gap: 10px; font-weight: bold; font-size: 20px;">
    <img src="/assets/Logo.png" alt="Logo" style="height: 30px; width: 30px; object-fit: contain;">
        SIMS PPOB 
    </div></a>
  <nav style="display: flex; gap: 20px;">
    <a href="/transaction/topup" style="color: #f42c20; font-weight: bold;">Top Up</a>
    <a href="/transaction/history" style="color: #000;">Transaction</a>
    <a href="/profile" style="color: #000;">Akun</a>
  </nav>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

</header>

<main style="padding: 20px;">

  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div style="display: flex; flex-direction: column; align-items: flex-start;">
    <img src="<?= !empty($profile['profile_image']) ? esc($profile['profile_image']) : '/assets/Profile Photo.png' ?>" 
            alt="Profile" 
            style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom: 20px;">
      <p style="margin: 0; font-size: 22px; color: #666;">Selamat datang,</p>
      <h2 style="margin: 0; font-size: 32px;"><?= ($profile['first_name'] ?? '-') . ' ' . ($profile['last_name'] ?? '') ?></h2>
    </div>

    <div style="background: url('/assets/Background Saldo.png') no-repeat center center / cover; color: white; padding: 20px; border-radius: 20px; width: 400px; height: 120px; display: flex; flex-direction: column; justify-content: center; position: relative;margin-top: 5px">
      <div style="display: flex; flex-direction: column; gap: 10px;">
        <p style="margin: 0;">Saldo anda</p>
        <h2 id="saldo-text" style="margin: 5px 0;">Rp ••••••</h2>
        <button id="toggle-button" onclick="toggleSaldo()" style="background: transparent; border: none; color: white; font-size: 14px; cursor: pointer; padding: 0; margin-top: 10px; align-self: flex-start;">Lihat Saldo 👁</button>
      </div>
    </div>
  </div>

  <div style="margin-bottom: 20px;">
    <p style="font-size: 22px; font-weight: normal; margin: 0;">Silahkan masukan</p>
    <p style="font-size: 26px; font-weight: bold; margin: 0;">Nominal Top Up</p>
  </div>

<div style="display: flex; align-items: flex-start; gap: 20px; margin-top: 30px; flex-wrap: wrap;">

  <div style="flex: 1; display: flex; flex-direction: column; gap: 10px; min-width: 300px;">
  <div style="position: relative;">
    <i class="fas fa-wallet" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: gray; font-size: 20px;"></i>
    <input 
        type="number" 
        id="amount" 
        oninput="validateInput()" 
        placeholder="masukan nominal Top Up"
        style="padding-left: 45px; height: 50px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; width: 100%;">
  </div>
    <button id="topup-btn" onclick="confirmTopUp()" disabled
    style="height: 50px; background-color: #f44336; color: white; font-weight: bold; border: none; border-radius: 5px; opacity: 0.6; cursor: not-allowed; width: 100%;">
    Top Up
    </button>
  </div>

  <div style="width: 1000px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
    <button onclick="fillNominal(10000)" class="quick-btn">Rp10.000</button>
    <button onclick="fillNominal(20000)" class="quick-btn">Rp20.000</button>
    <button onclick="fillNominal(50000)" class="quick-btn">Rp50.000</button>
    <button onclick="fillNominal(100000)" class="quick-btn">Rp100.000</button>
    <button onclick="fillNominal(250000)" class="quick-btn">Rp250.000</button>
    <button onclick="fillNominal(500000)" class="quick-btn">Rp500.000</button>
  </div>

</div>



</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

function toggleSaldo() {
  const saldoText = document.getElementById('saldo-text');
  const toggleButton = document.getElementById('toggle-button');
  const realSaldo = 'Rp <?= number_format($balance['balance'] ?? 0) ?>';
  const hiddenSaldo = 'Rp ••••••';

  if (saldoText.innerText === hiddenSaldo) {
    saldoText.innerText = realSaldo;
    toggleButton.innerText = 'Sembunyikan Saldo';
  } else {
    saldoText.innerText = hiddenSaldo;
    toggleButton.innerText = 'Lihat Saldo 👁';
  }
}

function fillNominal(amount) {
  const amountInput = document.getElementById('amount');
  amountInput.value = amount.toLocaleString('id-ID');
  validateInput(); 
}

function validateInput() {
  const input = document.getElementById('amount');
  const button = document.getElementById('topup-btn');
  const value = input.value.replace(/\D/g, ''); // hanya angka

  if (value.length > 0) {
    button.disabled = false;
    button.style.opacity = 1;
    button.style.cursor = 'pointer';
  } else {
    button.disabled = true;
    button.style.opacity = 0.6;
    button.style.cursor = 'not-allowed';
  }
}

function confirmTopUp() {
  const amountInput = document.getElementById('amount').value.replace(/\D/g, '');
  
  if (!amountInput || isNaN(amountInput)) {
    Swal.fire('Oops', 'Masukkan nominal Top Up yang valid!', 'warning');
    return;
  }

  Swal.fire({
    title: 'Konfirmasi Top Up',
    html: `Anda yakin ingin Top Up sebesar <b>Rp${parseInt(amountInput).toLocaleString('id-ID')}</b> ?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Ya, Top Up',
    cancelButtonText: 'Batal',
    confirmButtonColor: '#f44336',
  }).then((result) => {
    if (result.isConfirmed) {
      performTopUp(amountInput);
    }
  });
}

function performTopUp(amount) {
  const btn = document.getElementById('topup-btn');
  btn.innerHTML = 'Loading...';
  btn.disabled = true;

  fetch('/transaction/topup', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',

    },
    body: JSON.stringify({ amount: parseInt(amount) })
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 200 || data.success) {
      Swal.fire({
        icon: 'success',
        title: 'Top Up Berhasil!',
        html: `Top Up sebesar <b>Rp${parseInt(amount).toLocaleString('id-ID')}</b> berhasil!<br><br>Ingin ke beranda atau tetap di halaman ini?`,
        showDenyButton: true,
        confirmButtonText: 'Ke Home',
        denyButtonText: 'Tetap di sini',
        confirmButtonColor: '#f44336',
        denyButtonColor: '#aaa'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = '/home';
        } else {
          window.location.reload();
        }
      });
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Top Up Gagal',
        text: data.message || 'Silahkan coba lagi!',
        confirmButtonColor: '#f44336',
      });
    }
  })
  .catch(error => {
    Swal.fire('Error', 'Gagal terhubung ke server.', 'error');
  })
  .finally(() => {
    btn.innerHTML = 'Top Up';
    btn.disabled = false;
  });
}
</script>

<style>
.quick-btn {
  padding: 10px;
  background: white;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 13px;
  cursor: pointer;
  transition: 0.2s;
  height: 50px;
}

.quick-btn:hover {
  background: #f44336;
  color: white;
  border-color: #f44336;
}
</style>

