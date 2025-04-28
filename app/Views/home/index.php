<header style="display: flex; justify-content: space-between; align-items: center; padding: 20px; border-bottom: 1px solid #ccc;">
<a href="/home" style="text-decoration: none;"><div style="display: flex; align-items: center; gap: 10px; font-weight: bold; font-size: 20px;">
    <img src="/assets/Logo.png" alt="Logo" style="height: 30px; width: 30px; object-fit: contain;">
        SIMS PPOB 
    </div></a>
    <nav style="display: flex; gap: 20px;">
        <a href="/transaction/topup" style="color: #000; text-decoration: none;">Top Up</a>
        <a href="/transaction/history" style="color: #000; text-decoration: none;">Transaction</a>
        <a href="/profile" style="color: #000; text-decoration: none;">Akun</a>
    </nav>
</header>

<main style="padding: 20px;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; gap: 20px;">

        <div style="display: flex; flex-direction: column; align-items: flex-start; text-align: left; gap: 0;">
        <img src="<?= !empty($profile['profile_image']) ? esc($profile['profile_image']) : '/assets/Profile Photo.png' ?>" 
            alt="Profile" 
            style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom: 20px;">
            <p style="margin: 0; font-size: 22px; color: #666;">Selamat datang,</p>
            <h2 style="margin: 0; font-size: 32px;"><?= ($profile['first_name'] ?? '-') . ' ' . ($profile['last_name'] ?? '') ?></h2>
        </div>

        <div style="background: url('/assets/Background Saldo.png') no-repeat center center / cover; color: white; padding: 20px; border-radius: 20px; width: 400px; height: 120px; display: flex; flex-direction: column; justify-content: center; position: relative;margin-top: 5px;">
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <p style="margin: 0;">Saldo anda</p>
                <h2 id="saldo-text" style="margin: 5px 0;">Rp ••••••</h2> 
                <button id="toggle-button" onclick="toggleSaldo()" style="background: transparent; border: none; color: white; font-size: 14px; cursor: pointer; padding: 0; margin-top: 10px; align-self: flex-start;">Lihat Saldo 👁</button>
            </div>
        </div>

    </div>

    <div class="services-container" style="margin-top: 80px;">
        <?php foreach($services as $service): ?>
            <div class="service-item" onclick="goToPurchase('<?= $service['service_code'] ?>')">
                <img src="<?= $service['service_icon'] ?>" alt="<?= $service['service_name'] ?>" class="service-icon">
                <p class="service-name"><?= $service['service_name'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <h3 style="margin-top: 50px;">Temukan promo menarik</h3>
    <div class="banners-container">
        <?php foreach($banners as $b): ?>
            <div class="banner-item">
                <img src="<?= $b['banner_image'] ?>" alt="Promo" class="banner-image">
            </div>
        <?php endforeach; ?>
    </div>

</main>

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

function goToPurchase(serviceCode) {
    window.location.href = '/purchase/' + serviceCode;
}
</script>

<style>
.services-container {
    display: flex;
    gap: 20px;
    margin-bottom: 40px;
    overflow-x: auto;
    padding-bottom: 10px;
}

.service-item {
    text-align: center;
    flex: 0 0 auto;
    width: 80px;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}
.service-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.service-icon {
    width: 60px;
    height: 60px;
    object-fit: contain;
}

.service-name {
    font-size: 14px;
    margin-top: 5px;
}

.banners-container {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    padding-bottom: 10px;
}

.banner-item {
    flex: 0 0 auto;
    width: 300px;
}

.banner-image {
    width: 100%;
    border-radius: 10px;
}
</style>
