// Setup Stripe
const stripe = Stripe('your-publishable-key-here');

document.addEventListener('DOMContentLoaded', function() {
  const elements = stripe.elements();

  // Create an instance of the card Element
  const cardElement = elements.create('card', {
    hidePostalCode: true, // Optionally hide postal code if not needed
  });

  // Add an instance of the card Element into the 'card-element' div
  cardElement.mount('#card-element');

  // Handle form submission
  const form = document.getElementById('payment-form');
  form.addEventListener('submit', async (event) => {
    event.preventDefault();

    // Disable the submit button to prevent multiple submissions
    document.querySelector('#submit-button').disabled = true;

    const { token, error } = await stripe.createToken(cardElement);

    if (error) {
      // Display error.message in the UI
      document.getElementById('card-errors').textContent = error.message;
      document.querySelector('#submit-button').disabled = false;
    } else {
      // Send the token to your server
      stripeTokenHandler(token);
    }
  });
});

// Function to handle token submission to your server
async function stripeTokenHandler(token) {
  const response = await fetch('/process-payment', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ token: token.id }),
  });

  const result = await response.json();

  if (result.error) {
    // Show error from the server on the UI
    document.getElementById('card-errors').textContent = result.error.message;
    document.querySelector('#submit-button').disabled = false;
  } else {
    // Redirect or show success message
    window.location.href = '/payment-success'; // Redirect to success page
  }
}
