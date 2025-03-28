document.addEventListener('DOMContentLoaded', () => {
    const transferForm = document.getElementById('points-transfer-form');
    
    if (transferForm) {
        transferForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(transferForm);
            const resultDiv = document.getElementById('transfer-result');
            
            try {
                const response = await fetch(transferForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            Points transferred successfully!
                        </div>
                    `;
                    // Refresh balance display
                    prestashop.emit('updateLoyaltyPoints');
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            ${data.error || 'Transfer failed'}
                        </div>
                    `;
                }
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        Network error - please try again
                    </div>
                `;
            }
            
            setTimeout(() => {
                resultDiv.innerHTML = '';
            }, 3000);
        });
    }
});