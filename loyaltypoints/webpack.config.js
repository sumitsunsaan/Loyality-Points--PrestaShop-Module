document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.social-share-button').forEach(button => {
        button.addEventListener('click', async (e) => {
            e.preventDefault();
            
            const { productId, platform } = button.dataset;
            const account = prompt('Please enter your social media account ID:');
            
            if (account) {
                try {
                    const response = await fetch(prestashop.urls.base_url + '/module/loyaltypoints/social', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            platform: platform,
                            account: account
                        })
                    });

                    const result = await response.json();
                    if (result.success) {
                        prestashop.emit('updateLoyaltyPoints');
                    } else {
                        showError(result.error || 'Failed to record share');
                    }
                } catch (error) {
                    showError('Network error - please try again');
                }
            }
        });
    });

    function showError(message) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger';
        alert.textContent = message;
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 3000);
    }
});