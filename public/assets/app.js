document.querySelector('.nav-toggle')?.addEventListener('click', () => {
  document.querySelector('.nav-links')?.classList.toggle('open');
});

const quantity = document.querySelector('.quantity');
if (quantity) {
  const input = quantity.querySelector('input');
  const price = document.querySelector('[data-price]');
  const basePrice = Number(price?.dataset.price || 0);
  const updateQuantity = (delta) => {
    const min = Number(input?.min || 1);
    const max = Number(input?.max || 10);
    const value = Math.max(min, Math.min(max, Number(input?.value || 1) + delta));
    if (input) input.value = String(value);
    if (price) price.textContent = new Intl.NumberFormat('fr-FR').format(basePrice * value) + ' FCFA';
  };
  quantity.querySelector('[data-minus]')?.addEventListener('click', () => updateQuantity(-1));
  quantity.querySelector('[data-plus]')?.addEventListener('click', () => updateQuantity(1));
}

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js').catch((error) => console.warn('PWA hors ligne indisponible :', error));
  });
}

(() => {
  const dialog = document.querySelector('#install-dialog');
  const installButton = document.querySelector('#install-app-button');
  const laterButton = document.querySelector('#install-later-button');
  if (!dialog || !installButton || !laterButton) return;

  let installPrompt = null;
  const isIOS = /iphone|ipad|ipod/i.test(navigator.userAgent) && !window.MSStream;
  const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
  const dismissedKey = 'sama-billet-install-dismissed';
  const iosHelp = document.querySelector('#install-ios-help');
  const description = document.querySelector('#install-copy');

  if (isStandalone) return;
  if (isIOS) {
    iosHelp.hidden = false;
    installButton.hidden = true;
    description.textContent = 'Installez Sama Billet sur votre iPhone ou iPad pour retrouver vos événements et billets plus facilement.';
  }

  window.addEventListener('beforeinstallprompt', (event) => {
    event.preventDefault();
    installPrompt = event;
  });
  window.addEventListener('appinstalled', () => {
    installPrompt = null;
    if (dialog.open) dialog.close();
    localStorage.setItem(dismissedKey, 'installed');
  });

  installButton.addEventListener('click', async () => {
    if (!installPrompt) {
      description.textContent = 'Pour installer l’application, ouvrez le menu de votre navigateur puis choisissez « Installer l’application » ou « Ajouter à l’écran d’accueil ». ';
      installButton.hidden = true;
      return;
    }
    installPrompt.prompt();
    await installPrompt.userChoice;
    installPrompt = null;
    dialog.close();
  });

  laterButton.addEventListener('click', () => {
    localStorage.setItem(dismissedKey, 'dismissed');
    dialog.close();
  });

  window.addEventListener('load', () => {
    if (localStorage.getItem(dismissedKey)) return;
    window.setTimeout(() => {
      if (!dialog.open && !isStandalone) dialog.showModal();
    }, 1800);
  }, { once: true });
})();
