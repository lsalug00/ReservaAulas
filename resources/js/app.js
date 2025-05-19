import './bootstrap';
import './custom/theme-toggle.js';

document.addEventListener('DOMContentLoaded', () => {
  const page = document.body.dataset.page;

  switch (page) {
    case 'index':
      import('./custom/index.js');
      break;
    case 'perfil-index':
      import('./custom/perfil-index.js');
      break;
    case 'register':
      import('./custom/register.js');
      break;
    case 'login':
      import('./custom/login.js');
      break;
    case 'change':
      import('./custom/change.js');
      break;
    case 'users-index':
      import('./custom/users-index.js');
      break;
    case 'dias-no-lectivos':
      import('./custom/dias-no-lectivos.js');
      break;
    case 'aulas':
      import('./custom/aulas.js');
      break;
    // Agrega más casos según tus páginas
    default:
      // Código común o nada
      break;
  }
});