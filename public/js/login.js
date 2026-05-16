function openModal() {
    const modal = document.getElementById('login-modal');
    if (modal) {
        modal.style.display = 'block';
    } else {
        console.error("Modal Login tidak ditemukan! Pastikan ID 'login-modal' ada di front.blade.php");
    }
}

function closeModal() {
    const modal = document.getElementById('login-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

window.addEventListener('click', function(event) {
    const loginModal = document.getElementById('login-modal');
    if (event.target == loginModal) {
        closeModal();
    }
});


document.addEventListener('DOMContentLoaded', function() {
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const switchToRegister = document.getElementById('switch-to-register-link');
    const switchToLogin = document.getElementById('switch-to-login-link');
    const loginContent = document.getElementById('login-content');
    const registerContent = document.getElementById('register-content');

    if (switchToRegister) {
        switchToRegister.addEventListener('click', function(e) {
            e.preventDefault();
            if(loginContent) loginContent.style.display = 'none';
            if(registerContent) registerContent.style.display = 'block';
        });
    }

    if (switchToLogin) {
        switchToLogin.addEventListener('click', function(e) {
            e.preventDefault();
            if(registerContent) registerContent.style.display = 'none';
            if(loginContent) loginContent.style.display = 'block';
        });
    }


    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const emailInput = document.getElementById('username'); 
            const passInput = document.getElementById('password');

            if(!emailInput || !passInput) {
                console.error("Input email/password tidak ditemukan ID-nya!");
                return;
            }

            fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ 
                    email: emailInput.value, 
                    password: passInput.value 
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Login Berhasil! Selamat datang ' + (data.user_name || ''));
                    location.reload(); 
                } else {
                    alert('Login Gagal: ' + data.message);
                }
            })
            .catch(err => console.error(err));
        });
    }

const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const name     = document.getElementById('reg-name')?.value;
            const email    = document.getElementById('reg-email')?.value;
            const password = document.getElementById('reg-password')?.value;

            fetch('/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ name, email, password })
            })
            .then(async (res) => {
                let data = null;
                try {
                    data = await res.json();
                } catch (e) {
                    console.error('Gagal parse JSON register:', e);
                }
                if (!res.ok) {
                    let msg = 'Terjadi kesalahan.';
                    if (data?.errors) {
                        msg = Object.values(data.errors).flat().join('\n');
                    } else if (data?.message) {
                        msg = data.message;
                    }
                    alert('Gagal: ' + msg);
                    return;
                }

                if (data && data.success) {
                    alert(data.message || 'Registrasi Berhasil! Anda sudah login.');
                    location.reload();
                } else {
                    const msg = data?.message || 'Terjadi kesalahan.';
                    alert('Gagal: ' + msg);
                }
            })
            .catch(err => {
                console.error('Error register fetch:', err);
                alert('Gagal: tidak dapat terhubung ke server.');
            });
        });
    }

});

