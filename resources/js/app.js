// Le menu mobile : le bouton burger affiche ou cache la liste des liens.
const menuButton = document.querySelector('#menu-button');
const menu = document.querySelector('#main-menu');

if (menuButton && menu) {
    menuButton.addEventListener('click', () => {
        const isOpen = menuButton.getAttribute('aria-expanded') === 'true';

        menuButton.setAttribute('aria-expanded', String(!isOpen));
        menu.classList.toggle('hidden');
    });
}
