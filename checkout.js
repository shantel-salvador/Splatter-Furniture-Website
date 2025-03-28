document.addEventListener('DOMContentLoaded', function() {
    const placeOrderButton = document.querySelector('.place-order');
    
    placeOrderButton.addEventListener('click', function(e) {
        e.preventDefault(); 
        
        const requiredFields = document.querySelectorAll('input[required], select[required]');
        let allFieldsFilled = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                allFieldsFilled = false;
                field.style.borderColor = 'red';
            } else {
                field.style.borderColor = '#ddd';
            }
        });
        
        if (allFieldsFilled) {
            showNotification('Your order has been placed successfully!', 'success');
            
            setTimeout(() => {
                document.querySelector('form').reset(); // Reset the form
            }, 3000);
        } else {
            showNotification('Please fill in all required fields', 'error');
        }
    });
    
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <p>${message}</p>
                <button class="close-notification">×</button>
            </div>
        `;
        
        // Add styles to the notification
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.padding = '15px 20px';
        notification.style.borderRadius = '5px';
        notification.style.boxShadow = '0 3px 10px rgba(0,0,0,0.2)';
        notification.style.zIndex = '1000';
        notification.style.minWidth = '300px';
        notification.style.animation = 'slideIn 0.5s forwards';
        
        if (type === 'success') {
            notification.style.backgroundColor = '#4CAF50';
            notification.style.color = 'white';
        } else if (type === 'error') {
            notification.style.backgroundColor = '#f44336';
            notification.style.color = 'white';
        }
        
        // Add close button styling
        const closeButton = notification.querySelector('.close-notification');
        closeButton.style.background = 'none';
        closeButton.style.border = 'none';
        closeButton.style.color = 'inherit';
        closeButton.style.fontSize = '20px';
        closeButton.style.cursor = 'pointer';
        closeButton.style.float = 'right';
        closeButton.style.marginLeft = '10px';
        
        // Add notification to the DOM
        document.body.appendChild(notification);
        
        // Close notification when clicking the close button
        closeButton.addEventListener('click', function() {
            document.body.removeChild(notification);
        });
        
        // Automatically remove the notification after 5 seconds
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 5000);
    }
    
    // Add CSS animation for notification
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
});