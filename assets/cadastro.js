function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthDiv = document.getElementById('password-strength');
            
    if (password.length === 0) {
        strengthDiv.style.display = 'none';
        return;
    }
            
    strengthDiv.style.display = 'block';
            
    let strength = 0;
    let feedback = [];
            

    if (password.length >= 8) strength += 1;
    else feedback.push('at least 8 characters');
            
            // Check for lowercase
    if (/[a-z]/.test(password)) strength += 1;
    else feedback.push('lowercase letter');
            
            // Check for uppercase
    if (/[A-Z]/.test(password)) strength += 1;
    else feedback.push('uppercase letter');
            
            
    if (strength < 2) {
        strengthDiv.className = 'password-strength strength-weak';
        strengthDiv.textContent = 'Weak password - Add: ' + feedback.join(', ');

    } else if (strength < 3) {
        strengthDiv.className = 'password-strength strength-medium';
        strengthDiv.textContent = 'Medium strength - Consider adding: ' + feedback.join(', ');

    } else {
        strengthDiv.className = 'password-strength strength-strong';
        strengthDiv.textContent = 'Strong password!';
    }
}
        
function validateForm() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
            
    if (password !== confirmPassword) {
        alert('Passwords do not match!');
        return false;
    }
            
    if (password.length < 6) {
        alert('Password must be at least 6 characters long!');
        return false;
    }
            
    return true;
}