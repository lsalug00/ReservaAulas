function togglePasswordVisibility(toggleId, inputId, openId, closedId) {
    const input = document.getElementById(inputId);
    const toggle = document.getElementById(toggleId);
    const eyeOpen = document.getElementById(openId);
    const eyeClosed = document.getElementById(closedId);
    if (toggle && input) {
        toggle.addEventListener('click', () => {
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isHidden);
            eyeClosed.classList.toggle('hidden', !isHidden);
        });
    }
}

togglePasswordVisibility('togglePasswordCurrent', 'current_password', 'eyeOpenCurrent', 'eyeClosedCurrent');
togglePasswordVisibility('togglePassword', 'password', 'eyeOpen', 'eyeClosed');
togglePasswordVisibility('togglePasswordConfirm', 'password_confirmation', 'eyeOpenConfirm', 'eyeClosedConfirm');