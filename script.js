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

  document.addEventListener('DOMContentLoaded', () => {
    // Fetch the initial cart count on page load
    updateCartCount();

    // Add event listeners to all "Add to Cart" buttons (if using AJAX to add items to cart)
    document.querySelectorAll('.button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            const url = this.href;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-count').textContent = data.totalItems;
                    alert('Product added to cart!');
                })
                .catch(error => console.error('Error:', error));
        });
    });
});

// Function to fetch and update cart count
function updateCartCount() {
    fetch('cart_count.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('cart-count').textContent = data.totalItems;
        })
        .catch(error => console.error('Error fetching cart count:', error));
}

});