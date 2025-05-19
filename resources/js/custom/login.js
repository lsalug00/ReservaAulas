const input = document.getElementById('password');
const eyeOpen = document.getElementById('eyeOpen');
const eyeClosed = document.getElementById('eyeClosed');
const toggleBtn = document.getElementById('togglePassword');

toggleBtn.addEventListener('click', () => {
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    eyeOpen.classList.toggle('hidden', isHidden);
    eyeClosed.classList.toggle('hidden', !isHidden);
});