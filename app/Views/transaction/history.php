<header style="display: flex; justify-content: space-between; align-items: center; padding: 20px; border-bottom: 1px solid #ccc;">
<a href="/home" style="text-decoration: none;"><div style="display: flex; align-items: center; gap: 10px; font-weight: bold; font-size: 20px;">
    <img src="/assets/Logo.png" alt="Logo" style="height: 30px; width: 30px; object-fit: contain;">
        SIMS PPOB 
    </div></a>
  <nav style="display: flex; gap: 20px;">
    <a href="/transaction/topup" style="color: #000;">Top Up</a>
    <a href="/transaction/history" style="color: #f42c20; font-weight: bold;">Transaction</a>
    <a href="/profile" style="color: #000;">Akun</a>
  </nav>
</header>

<main style="padding: 20px;">

  <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div style="display: flex; flex-direction: column; align-items: flex-start;">
    <img src="<?= !empty($profile['profile_image']) ? esc($profile['profile_image']) : '/assets/Profile Photo.png' ?>" 
            alt="Profile" 
            style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom: 20px;">
      <p style="margin: 0; font-size: 22px; color: #666;">Selamat datang,</p>
      <h2 style="margin: 0; font-size: 32px;"><?= esc(($profile['first_name'] ?? '-') . ' ' . ($profile['last_name'] ?? '')) ?></h2>
    </div>

    <div style="background: url('/assets/Background Saldo.png') no-repeat center center / cover; color: white; padding: 20px; border-radius: 20px; width: 400px; height: 120px; display: flex; flex-direction: column; justify-content: center; position: relative;margin-top: 5px;">
      <div style="display: flex; flex-direction: column; gap: 10px;">
        <p style="margin: 0;">Saldo anda</p>
        <h2 id="saldo-text" style="margin: 5px 0;">Rp ••••••</h2>
        <button id="toggle-button" onclick="toggleSaldo()" style="background: transparent; border: none; color: white; font-size: 14px; cursor: pointer; padding: 0; margin-top: 10px; align-self: flex-start;">Lihat Saldo 👁</button>
      </div>
    </div>
  </div>

    <div style="margin-bottom: 20px; display: flex; gap: 20px; flex-wrap: wrap;">
        <?php
        $bulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $bulanAngka = [
            '01', '02', '03', '04', '05', '06',
            '07', '08', '09', '10', '11', '12'
        ];
        foreach ($bulan as $idx => $bln): ?>
            <button id="btn-<?= $bulanAngka[$idx] ?>" class="month-btn" onclick="filterByMonth('<?= $bulanAngka[$idx] ?>')"><?= $bln ?></button>
        <?php endforeach; ?>
        <button onclick="resetFilter()" class="month-btn" style="color:#000; font-weight:bold;">Tampilkan Semua</button>
    </div>

  <div id="transaction-list"></div>

  <div style="text-align: center; margin-top: 20px;">
    <button id="show-more-btn" style="display:none; background: #4caf50; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer;">
      Show More
    </button>
  </div>

  <p id="empty-message" style="color: #aaa; text-align: center; margin-top: 100px; display: none;">Maaf tidak ada histori transaksi di bulan ini</p>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

const allTransactions = <?= json_encode($transactions) ?>;

let filteredTransactions = [...allTransactions];
let itemsPerPage = 5;
let currentPage = 1;

const transactionList = document.getElementById('transaction-list');
const showMoreBtn = document.getElementById('show-more-btn');
const emptyMessage = document.getElementById('empty-message');

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

function renderTransactions() {
  transactionList.innerHTML = '';
  const totalDisplayed = currentPage * itemsPerPage;
  const slice = filteredTransactions.slice(0, totalDisplayed);

  if (slice.length === 0) {
    emptyMessage.style.display = 'block';
    showMoreBtn.style.display = 'none';
    return;
  } else {
    emptyMessage.style.display = 'none';
  }

  slice.forEach(trx => {
    if (trx && trx['created_on']) {
      const type = trx['transaction_type'];
      const amount = trx['total_amount'];
      const desc = trx['description'];
      const createdOn = trx['created_on'];
      const icon = type === 'TOPUP' ? '⬇️' : '⬆️';
      const color = type === 'TOPUP' ? '#4caf50' : '#f44336';
      const month = new Date(createdOn).toLocaleDateString('en-CA').slice(5,7);

      const item = `
        <div class="transaction-item" data-month="${month}">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
              <p style="margin: 0; color: ${color}; font-weight: bold;">
                ${icon} ${type === 'TOPUP' ? '+' : '-'} Rp${parseInt(amount).toLocaleString('id-ID')}
              </p>
              <small style="color: #999;">
                ${new Date(createdOn).toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' })} ${new Date(createdOn).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })} WIB
              </small>
            </div>
            <div style="font-size: 14px; color: #666; text-align: right;">
              ${desc}
            </div>
          </div>
        </div>
      `;
      transactionList.innerHTML += item;
    }
  });

  if (totalDisplayed >= filteredTransactions.length) {
    showMoreBtn.style.display = 'none';
  } else {
    showMoreBtn.style.display = 'inline-block';
  }
}

showMoreBtn.addEventListener('click', () => {
  currentPage++;
  renderTransactions();
});

function filterByMonth(month) {
  filteredTransactions = allTransactions.filter(trx => {
    const trxMonth = new Date(trx.created_on).toLocaleDateString('en-CA').slice(5,7);
    return trxMonth === month;
  });

  currentPage = 1;
  renderTransactions();

  document.querySelectorAll('.month-btn').forEach(btn => btn.classList.remove('active'));
  document.getElementById('btn-' + month).classList.add('active');
}

function resetFilter() {
  filteredTransactions = [...allTransactions];
  currentPage = 1;
  renderTransactions();

  document.querySelectorAll('.month-btn').forEach(btn => btn.classList.remove('active'));
}

window.addEventListener('DOMContentLoaded', () => {
  renderTransactions();
});
</script>

<style>
.month-btn {
  background: none;
  border: none;
  font-size: 16px;
  color: #999;
  cursor: pointer;
}
.month-btn:hover,
.month-btn.active {
  color: #f44336;
  font-weight: bold;
}
.transaction-item {
  padding: 15px;
  border: 1px solid #eee;
  border-radius: 8px;
  margin-bottom: 10px;
  transition: all 0.3s;
}
.transaction-item:hover {
  background: #fafafa;
}
</style>