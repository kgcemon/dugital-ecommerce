const loginBtns = document.querySelectorAll('.loginBtn');
const loginModal = document.getElementById('loginModal');
const closeModal = document.getElementById('closeModal');

loginBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        loginModal.classList.add('show');
    });
});

closeModal.addEventListener('click', function() {
    loginModal.classList.remove('show');
});

window.addEventListener('click', function(e) {
    if(e.target === loginModal){
        loginModal.classList.remove('show');
    }
});
