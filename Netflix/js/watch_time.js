focus();
const listener = window.addEventListener('blur', () => {
  if (document.activeElement === document.querySelector('#player')) {
    console.log('clicked on iframe')
  }
  window.removeEventListener('blur', listener);
});