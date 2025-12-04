window.openModal = function() {
    document.getElementById('login-modal').style.display = 'flex';
    switchToLogin();
}

window.closeModal = function() {
    document.getElementById('login-modal').style.display = 'none';
}


function switchToRegister() {
    document.getElementById('login-content').style.display = 'none';
    document.getElementById('register-content').style.display = 'block';
    
}


function switchToLogin() {
    document.getElementById('login-content').style.display = 'block';
    document.getElementById('register-content').style.display = 'none';
    
}

document.addEventListener('DOMContentLoaded', () => {
    const switchToRegisterLink = document.getElementById('switch-to-register-link');
    const switchToLoginLink = document.getElementById('switch-to-login-link');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    if (switchToRegisterLink) {
        switchToRegisterLink.addEventListener('click', function(event) {
            event.preventDefault();
            switchToRegister();
        });
    }

    if (switchToLoginLink) {
        switchToLoginLink.addEventListener('click', function(event) {
            event.preventDefault();
            switchToLogin();
        });
    }

    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault(); 
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            
            if (username && password) {
                alert('Login berhasil! Selamat datang, ' + username);
                closeModal();
            } else {
                alert('Harap isi semua field.');
            }
        });
    }


    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            event.preventDefault(); 
            const name = document.getElementById('reg-name').value;
            const email = document.getElementById('reg-email').value;
            const password = document.getElementById('reg-password').value;

            if (name && email && password) {
                alert('Pendaftaran berhasil! Silakan masuk dengan email Anda: ' + email);
                switchToLogin(); 
            } else {
                alert('Harap isi semua field pendaftaran.');
            }
        });
    }
});