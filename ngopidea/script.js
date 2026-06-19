const navToggle = document.querySelector('.nav-toggle');
const navMenu = document.getElementById('nav-menu');

// fungsi toggle menu
if (navToggle) {
    navToggle.addEventListener('click', () => {
        const expanded = navToggle.getAttribute('aria-expanded') === 'true';
        navToggle.setAttribute('aria-expanded', !expanded);
        navMenu.classList.toggle('active');
    });
}

// Tutup menu saat link diklik pada toggle menu
const navLinks = document.querySelectorAll('.nav-links li a');
navLinks.forEach(link => {
    link.addEventListener('click', () => {
        navToggle.setAttribute('aria-expanded', 'false');
        navMenu.classList.remove('active');
    });
});

// Scroll Event navbar
window.addEventListener('scroll', () => {
    const navbar = document.getElementById('navbar-header');
    const logo = document.querySelector('.logo img');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
        logo.src = logo.getAttribute('data-after');
    } else {
        navbar.classList.remove('scrolled');
        logo.src = logo.getAttribute('data-before');
    }
});

// Split menu items evenly into left and right columns
document.querySelectorAll('.menu-grid[data-auto-split="true"]').forEach((grid) => {
    const source = grid.querySelector('.menu-source');
    const leftColumn = grid.querySelector('.menu-list-left');
    const rightColumn = grid.querySelector('.menu-list-right');

    if (!source || !leftColumn || !rightColumn) {
        return;
    }

    const menuItems = Array.from(source.children).filter((child) => child.classList.contains('menu-item'));
    const splitIndex = Math.ceil(menuItems.length / 2);

    menuItems.forEach((item, index) => {
        const targetColumn = index < splitIndex ? leftColumn : rightColumn;
        targetColumn.appendChild(item);
    });

    source.remove();
});



