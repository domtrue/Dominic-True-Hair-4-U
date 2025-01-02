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

document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("cartModal");
  const modalImage = document.getElementById("modalImage");
  const modalName = document.getElementById("modalName");
  const modalPrice = document.getElementById("modalPrice");
  const closeBtn = document.querySelector(".close");

  // Handle "Add to Cart" button click
  document.querySelectorAll(".add-to-cart").forEach(button => {
      button.addEventListener("click", event => {
          const productId = button.dataset.id;
          const productName = button.dataset.name;
          const productPrice = button.dataset.price;
          const productImage = button.dataset.image;

          // Update modal content
          modalImage.src = productImage;
          modalName.textContent = productName;
          modalPrice.textContent = `$${parseFloat(productPrice).toFixed(2)}`;

          // Show modal
          modal.style.display = "block";

          // Send AJAX request to add product to cart
          fetch(`add_to_cart.php?id=${productId}&quantity=1`)
              .then(response => response.json())
              .then(data => {
                  console.log("Cart updated:", data);
              })
              .catch(error => console.error("Error:", error));
      });
  });

  // Close modal
  closeBtn.addEventListener("click", () => {
      modal.style.display = "none";
  });

  // Close modal when clicking outside
  window.addEventListener("click", event => {
      if (event.target === modal) {
          modal.style.display = "none";
      }
  });
});
