<header style="display: flex; justify-content: space-between; align-items: center; padding: 20px; border-bottom: 1px solid #ccc;">
<a href="/home" style="text-decoration: none;"><div style="display: flex; align-items: center; gap: 10px; font-weight: bold; font-size: 20px;">
    <img src="/assets/Logo.png" alt="Logo" style="height: 30px; width: 30px; object-fit: contain;">
        SIMS PPOB 
    </div></a>
  <nav style="display: flex; gap: 20px;">
    <a href="/transaction/topup" style="color: #000;">Top Up</a>
    <a href="/transaction/history" style="color: #000;">Transaction</a>
    <a href="/profile" style="color: #000;">Akun</a>
  </nav>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</header>

<main style="padding: 20px;">

  <div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
    <div style="flex-direction: column; display: flex; align-items: flex-start;">
    <img src="<?= !empty($profile['profile_image']) ? esc($profile['profile_image']) : '/assets/Profile Photo.png' ?>" 
            alt="Profile" 
            style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom: 20px;">
      <p style="margin:0; font-size:22px; color:#666;">Selamat datang,</p>
      <h2 style="margin:0; font-size:32px;"><?= esc(($profile['first_name'] ?? '-') . ' ' . ($profile['last_name'] ?? '')) ?></h2>
    </div>


        <div style="background: url('/assets/Background Saldo.png') no-repeat center center / cover; color: white; padding: 20px; border-radius: 20px; width: 400px; height: 120px; display: flex; flex-direction: column; justify-content: center; position: relative; margin-top: 5px;">
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <p style="margin: 0;">Saldo anda</p>
                <h2 id="saldo-text" style="margin: 5px 0;">Rp ••••••</h2> <!-- Disembunyikan -->
                <button id="toggle-button" onclick="toggleSaldo()" style="background: transparent; border: none; color: white; font-size: 14px; cursor: pointer; padding: 0; margin-top: 10px; align-self: flex-start;">Lihat Saldo 👁</button>
            </div>
        </div>
  </div>

  <h3>Pembayaran</h3>

  <div style="display: flex; align-items: center; margin: 20px 0; gap: 15px;">
    <img src="<?= esc($service['service_icon'] ?? '/assets/default.png') ?>" alt="<?= esc($service['service_name'] ?? '') ?>" style="width: 50px; height: 50px; object-fit: contain;">

    <h4 style="margin: 0;"><?= esc($service['service_name'] ?? 'Service') ?></h4>
  </div>

  <form id="purchase-form" style="margin-top:20px;">
    <input type="hidden" name="service_code" value="<?= esc($service['service_code']) ?>">
    
    <div style="margin-bottom: 20px; position: relative;">
      <label style="display: block; margin-bottom: 5px;">Harga</label>
      <div style="position:relative;">
        <i class="fas fa-wallet" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:gray;font-size:20px;"></i>
        <input 
          type="text" 
          id="amount" 
          value="Rp <?= number_format($service['service_tariff'] ?? 0, 0, ',', '.') ?>" 
          readonly
          style="padding-left:45px; height:50px; border:1px solid #ccc; border-radius:5px; font-size:16px; width:100%; background:#f9f9f9;">
      </div>
    </div>

    <button id="pay-button" type="submit"
      style="background:#f44336; color:white; border:none; padding:10px 20px; border-radius:8px; font-size:16px; cursor:pointer; width:100%; height:50px;">
      Bayar
    </button>
  </form>

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

document.getElementById('purchase-form').addEventListener('submit', async function(e) {
  e.preventDefault();

  const service_code = document.querySelector('input[name="service_code"]').value;
  const amount = <?= (int)($service['service_tariff'] ?? 0) ?>;

  if (!amount || amount <= 0) {
    Swal.fire('Oops!', 'Nominal tidak valid.', 'warning');
    return;
  }

  const result = await Swal.fire({
    title: 'Konfirmasi Pembayaran',
    text: `Yakin ingin membayar Rp${amount.toLocaleString('id-ID')} ?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Bayar Sekarang',
    cancelButtonText: 'Batal'
  });

  if (result.isConfirmed) {
    const btn = document.getElementById('pay-button');
    btn.innerHTML = '<div class="spinner"></div> Loading...';
    btn.disabled = true;

    try {
      const response = await fetch('/purchase/process', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ service_code, amount })
      });
      const data = await response.json();

      if (data.success) {
        Swal.fire({
          title: 'Pembelian Berhasil!',
          text: 'Transaksi Anda berhasil.',
          icon: 'success',
          showCancelButton: true,
          confirmButtonText: 'Ke Home',
          cancelButtonText: 'Tetap di Sini'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = '/home';
          } else {
            location.reload();
          }
        });
      } else {
        Swal.fire('Pembelian Gagal', data.message, 'error');
      }
    } catch (error) {
      Swal.fire('Error', 'Terjadi kesalahan saat pembayaran.', 'error');
    } finally {
      btn.innerHTML = 'Bayar';
      btn.disabled = false;
    }
  }
});
</script>

<style>
.spinner {
  border: 3px solid #f3f3f3;
  border-top: 3px solid #f44336;
  border-radius: 50%;
  width: 16px;
  height: 16px;
  animation: spin 1s linear infinite;
  display: inline-block;
  vertical-align: middle;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>