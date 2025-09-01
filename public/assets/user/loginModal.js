const loginBtn = document.getElementById('loginBtn'); // আগের login button
const loginModal = document.getElementById('loginModal');
const closeModal = document.getElementById('closeModal');

// আগের loginBtn click
if (loginBtn) {
    loginBtn.addEventListener('click', function(e) {
        e.preventDefault();
        loginModal.classList.add('show');
    });
}

// নতুন guest triggers (My Orders, My Account)
document.querySelectorAll('.login-trigger').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        loginModal.classList.add('show');
    });
});

// Close modal
closeModal.addEventListener('click', function() {
    loginModal.classList.remove('show');
});

// Click outside modal closes it
window.addEventListener('click', function(e) {
    if(e.target === loginModal){
        loginModal.classList.remove('show');
    }
});
