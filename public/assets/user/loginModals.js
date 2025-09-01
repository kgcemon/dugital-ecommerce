const loginBtns = document.querySelectorAll('.loginBtn');
const loginModals = document.getElementById('loginModals');
const closeModal = document.getElementById('closeModal');

loginBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        loginModals.classList.add('show');
    });
});

closeModal.addEventListener('click', function() {
    loginModals.classList.remove('show');
});

window.addEventListener('click', function(e) {
    if(e.target === loginModals){
        loginModals.classList.remove('show');
    }
});
