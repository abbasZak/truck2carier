// Tab Switching
        function switchTab(tab) {
            const loginForm = document.getElementById('loginForm');
            const signupForm = document.getElementById('signupForm');
            const tabSlider = document.getElementById('tabSlider');
            const tabButtons = document.querySelectorAll('.tab-button');

            tabButtons.forEach(btn => btn.classList.remove('active'));

            if (tab === 'login') {
                loginForm.classList.add('active');
                signupForm.classList.remove('active');
                tabSlider.classList.remove('signup');
                tabButtons[0].classList.add('active');
            } else {
                signupForm.classList.add('active');
                loginForm.classList.remove('active');
                tabSlider.classList.add('signup');
                tabButtons[1].classList.add('active');
            }
        }

        // Password Toggle
        function togglePassword(inputId, toggleIcon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }