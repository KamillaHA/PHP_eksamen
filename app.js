// Vis scroll-top knap når der scrolles ned
window.addEventListener('scroll', () => {
    const scrollBtn = document.getElementById('scrollTop');
    if (window.pageYOffset > 300) {
        scrollBtn.classList.add('show');
    } else {
        scrollBtn.classList.remove('show');
    }
});


// Burger menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const burger = document.querySelector('.burger');
    const nav = document.querySelector('nav');
    
    if (burger && nav) {
        burger.addEventListener('click', function() {
            burger.classList.toggle('open');
            nav.classList.toggle('active');
        });
    }
});