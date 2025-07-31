/**
 * Authentication Forms JavaScript Enhancement
 * Provides better UX for login and registration forms
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Password strength indicator for registration
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmPasswordInput = document.querySelector('input[name="password_confirmation"]');
    
    if (passwordInput) {
        // Create password strength indicator
        const strengthIndicator = document.createElement('div');
        strengthIndicator.className = 'password-strength mt-1';
        strengthIndicator.innerHTML = '<div class="strength-bar"><div class="strength-fill"></div></div><small class="strength-text text-muted"></small>';
        passwordInput.parentNode.appendChild(strengthIndicator);
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            updatePasswordStrength(strengthIndicator, strength);
        });
    }
    
    // Password confirmation validation
    if (confirmPasswordInput && passwordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            const password = passwordInput.value;
            const confirmation = this.value;
            
            if (confirmation && password !== confirmation) {
                this.setCustomValidity('Passwords do not match');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
                if (confirmation) this.classList.add('is-valid');
            }
        });
    }
    
    // Phone number formatting
    const phoneInput = document.querySelector('input[name="phone_number"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, ''); // Remove non-digits
            
            // Ensure starts with 0 and is max 10 digits
            if (value.length > 0 && value[0] !== '0') {
                value = '0' + value;
            }
            
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            
            this.value = value;
            
            // Validation feedback
            if (value.length === 10 && value[0] === '0') {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (value.length > 0) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });
        
        phoneInput.placeholder = "0712345678";
    }
    
    // Form submission loading states
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bi bi-arrow-clockwise me-2" style="animation: spin 1s linear infinite;"></i>Processing...';
                submitBtn.disabled = true;
                
                // Re-enable after 10 seconds as fallback
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 10000);
            }
        });
    });
    
    // Enhanced form validation feedback
    const inputs = document.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.checkValidity()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
    });
});

function calculatePasswordStrength(password) {
    let score = 0;
    let feedback = [];
    
    if (password.length >= 8) score += 25;
    else feedback.push('At least 8 characters');
    
    if (/[a-z]/.test(password)) score += 25;
    else feedback.push('Include lowercase letters');
    
    if (/[A-Z]/.test(password)) score += 25;
    else feedback.push('Include uppercase letters');
    
    if (/[\d]/.test(password)) score += 25;
    else feedback.push('Include numbers');
    
    if (/[\W]/.test(password)) score += 25;
    else feedback.push('Include special characters');
    
    return {
        score: Math.min(score, 100),
        feedback: feedback
    };
}

function updatePasswordStrength(indicator, strength) {
    const fill = indicator.querySelector('.strength-fill');
    const text = indicator.querySelector('.strength-text');
    
    fill.style.width = strength.score + '%';
    
    let strengthClass = 'bg-danger';
    let strengthText = 'Weak';
    
    if (strength.score >= 75) {
        strengthClass = 'bg-success';
        strengthText = 'Strong';
    } else if (strength.score >= 50) {
        strengthClass = 'bg-warning';
        strengthText = 'Medium';
    }
    
    fill.className = 'strength-fill ' + strengthClass;
    text.textContent = strengthText + (strength.feedback.length ? ': ' + strength.feedback.join(', ') : '');
}

// Add CSS for password strength indicator
const style = document.createElement('style');
style.textContent = `
    .password-strength .strength-bar {
        height: 4px;
        background-color: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
    }
    
    .password-strength .strength-fill {
        height: 100%;
        transition: width 0.3s ease, background-color 0.3s ease;
        border-radius: 2px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .form-control.is-valid, .form-select.is-valid {
        border-color: #198754;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.8-.77-.76-.76-.76.76zm1.48-4.86c.04-.14.06-.3.06-.48l-.46-.18c-.04.18-.1.32-.18.46l.58.2zm.42-1.2c-.08-.1-.2-.16-.34-.18l-.07.72c.08.02.14.06.18.14l.23-.68zm-.76.14c-.16.04-.3.1-.42.2l.5.54c.06-.04.14-.06.22-.08l-.3-.66zm-1.26 1.08c-.1.1-.18.26-.24.42l.7.26c.02-.08.06-.14.12-.2l-.58-.48zm-.16.96c-.02.16.02.3.08.42l.66-.3c-.02-.06-.02-.14-.04-.22l-.7.1zm.2.88c.08.14.2.24.36.28l.18-.7c-.08-.02-.12-.06-.16-.12l-.38.54zm.68.34c.14.02.32 0 .48-.04l-.2-.7c-.08.02-.14.04-.2.06l-.08.68zm1.16-.02c.16-.06.26-.16.34-.3l-.54-.5c-.04.08-.1.12-.18.16l.38.64z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6l.4-.4 1.4 1.4 1.4-1.4.4.4-1.4 1.4 1.4 1.4-.4.4-1.4-1.4-1.4 1.4-.4-.4 1.4-1.4z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
`;
document.head.appendChild(style);
