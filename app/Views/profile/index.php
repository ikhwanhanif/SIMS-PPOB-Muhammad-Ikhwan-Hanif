<header style="display:flex;justify-content:space-between;align-items:center;padding:20px;border-bottom:1px solid #ccc;">
<a href="/home" style="text-decoration: none;"><div style="display: flex; align-items: center; gap: 10px; font-weight: bold; font-size: 20px;">
    <img src="/assets/Logo.png" alt="Logo" style="height: 30px; width: 30px; object-fit: contain;">
        SIMS PPOB 
    </div></a>
  <nav style="display:flex;gap:20px;">
    <a href="/transaction/topup" style="color:#000;">Top Up</a>
    <a href="/transaction/history" style="color:#000;">Transaction</a>
    <a href="/profile" style="color:#f42c20;font-weight:bold;">Akun</a>
  </nav>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</header>

<main style="padding:20px;text-align:center;">

  <div style="position:relative;display:inline-block;margin-bottom:10px;">
  <img id="profile-photo" 
    src="<?= !empty($profile['profile_image']) ? esc($profile['profile_image']) : '/assets/Profile Photo.png' ?>" 
    alt="Profile" 
    style="width:120px;height:120px;border-radius:50%;object-fit:cover;">

    <input type="file" id="profileImageInput" accept="image/*" style="display:none;" onchange="previewAndUploadPhoto()">
    
    <button onclick="document.getElementById('profileImageInput').click()" 
      style="position:absolute;bottom:0;right:0;background:#f44336;border:none;border-radius:50%;padding:10px;color:white;cursor:pointer;">
      <i class="fas fa-camera"></i>
    </button>
  </div>

  <h2><?= esc(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? '')) ?></h2>

  <form id="profile-form" style="max-width:400px;margin:auto;margin-top:20px;display:flex;flex-direction:column;gap:15px;">
    <div style="text-align:left;">
      <label>Email</label>
      <div style="position:relative;">
        <i class="fas fa-envelope" style="position:absolute;top:50%;left:10px;transform:translateY(-50%);color:gray;"></i>
        <input type="email" name="email" value="<?= esc($profile['email'] ?? '') ?>" readonly
          style="padding-left:35px;width:100%;height:40px;border:1px solid #ccc;border-radius:5px;">
      </div>
    </div>

    <div style="text-align:left;">
      <label>Nama Depan</label>
      <div style="position:relative;">
        <i class="fas fa-user" style="position:absolute;top:50%;left:10px;transform:translateY(-50%);color:gray;"></i>
        <input type="text" name="first_name" value="<?= esc($profile['first_name'] ?? '') ?>" readonly
          style="padding-left:35px;width:100%;height:40px;border:1px solid #ccc;border-radius:5px;">
      </div>
    </div>

    <div style="text-align:left;">
      <label>Nama Belakang</label>
      <div style="position:relative;">
        <i class="fas fa-user" style="position:absolute;top:50%;left:10px;transform:translateY(-50%);color:gray;"></i>
        <input type="text" name="last_name" value="<?= esc($profile['last_name'] ?? '') ?>" readonly
          style="padding-left:35px;width:100%;height:40px;border:1px solid #ccc;border-radius:5px;">
      </div>
    </div>

    <button type="button" id="edit-btn" onclick="toggleEdit()" 
      style="height:45px;background:#f44336;color:white;border:none;border-radius:5px;font-weight:bold;">
      Edit Profil
    </button>

    <a href="/logout" id="logout-btn"
      style="height:45px;line-height:45px;text-align:center;border:1px solid #f44336;color:#f44336;border-radius:5px;text-decoration:none;font-weight:bold;display:block;">
      Logout
    </a>
  </form>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let isEditing = false;

function toggleEdit() {
  const form = document.getElementById('profile-form');
  const inputs = form.querySelectorAll('input');
  const editBtn = document.getElementById('edit-btn');
  const logoutBtn = document.getElementById('logout-btn');

  if (!isEditing) {
    inputs.forEach(input => {
      if (input.name !== 'email') {
        input.removeAttribute('readonly');
        input.style.background = '#fff';
      }
    });
    editBtn.innerText = 'Simpan';
    logoutBtn.style.display = 'none';
    isEditing = true;
  } else {
    submitProfile();
  }
}

async function submitProfile() {
  const firstName = document.querySelector('input[name="first_name"]').value.trim();
  const lastName = document.querySelector('input[name="last_name"]').value.trim();
  const editBtn = document.getElementById('edit-btn');

  if (!firstName || !lastName) {
    Swal.fire('Oops', 'Semua field wajib diisi.', 'warning');
    return;
  }

  editBtn.innerHTML = '<div class="spinner"></div> Saving...';
  editBtn.disabled = true;

  const data = {
    first_name: firstName,
    last_name: lastName
  };

  try {
    const response = await fetch('/profile/update', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    const result = await response.json();

    if (result.success) {
      Swal.fire('Sukses', 'Profil berhasil diperbarui!', 'success')
      .then(() => window.location.reload());
    } else {
      Swal.fire('Gagal', result.message, 'error');
    }
  } catch (error) {
    Swal.fire('Error', 'Terjadi kesalahan.', 'error');
  } finally {

    editBtn.innerHTML = 'Simpan';
    editBtn.disabled = false;
  }
}


function previewAndUploadPhoto() {
  const input = document.getElementById('profileImageInput');
  const img = document.getElementById('profile-photo');

  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function (e) {
      img.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);

    uploadPhoto();
  }
}

function uploadPhoto() {
  const input = document.getElementById('profileImageInput');
  if (input.files.length === 0) {
    Swal.fire('Oops', 'Pilih file terlebih dahulu.', 'warning');
    return;
  }

  const formData = new FormData();
  formData.append('file', input.files[0]);

  fetch('/profile/uploadPhoto', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      Swal.fire('Sukses', 'Foto profil berhasil diperbarui.', 'success')
      .then(() => window.location.reload());
    } else {
      Swal.fire('Gagal', data.message, 'error');
    }
  })
  .catch(error => {
    console.error(error);
    Swal.fire('Error', 'Terjadi kesalahan upload.', 'error');
  });
}
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

