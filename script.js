function toggleMenu() {
  var menu = document.querySelector('.menu');
  menu.classList.toggle('show');
}
document.addEventListener('click', function(event) {
  var menu = document.querySelector('.menu');
  var targetElement = event.target; 

  // Check if the clicked element is not within the menu itself or the navbar button
  if (!menu.contains(targetElement) && targetElement.getAttribute('onclick') !== 'toggleMenu()') {
    menu.classList.remove('show');
  }
});