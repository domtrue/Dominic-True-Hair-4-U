const stripe = Stripe("<?php echo $_ENV['STRIPE_PUBLISHABLE_KEY']; ?>");

// Customize the appearance (optional)
const appearance = {
    theme: 'flat', // Can be 'flat', 'stripe', 'night', or 'none'
    variables: {
        colorPrimary: '#0570de',
        colorBackground: '#ffffff',
        colorText: '#333333',
        fontSizeBase: '16px'
    }
};

// Accordion layout options
const options = {
    layout: {
        type: 'accordion',
        defaultCollapsed: false,
        radios: true,
        spacedAccordionItems: false
    }
};

// Initialize Stripe elements
const elements = stripe.elements({ clientSecret, appearance });
const paymentElement = elements.create('payment', options);
paymentElement.mount('#payment-element');

document.getElementById('submit-button').addEventListener('click', async (e) => {
    e.preventDefault();

    // Confirm the payment
    const { error } = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: 'payment_success.php', // Optional success page
        }
    });

    if (error) {
        const messageContainer = document.getElementById('payment-message');
        messageContainer.classList.remove('hidden');
        messageContainer.textContent = error.message;
    }
});
