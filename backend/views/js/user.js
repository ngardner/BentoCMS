$(document).ready(function() {
    
    togglePermissions();
    
});

function validUserForm() {
    
    var isValid = true;
    
    //remove previous errors
    removeMessages();
    
    var password = $('input[name="user_password"]').val();
    var password2 = $('input[name="user_password2"]').val();
    
    if($('input[name="user_id"]').val()) {
        
        //existing user
        if(password) {
            
            if(!validPassword(password)) {
                
                displayMessage('error','Invalid password. Must be at least 6 characters.');
                isValid = false;
                
            }
            
            if(password != password2) {
                
                $('input[name="user_password"]').addClass('form-err');
                $('input[name="user_password2"]').addClass('form-err');
                displayMessage('error','Passwords do not match.');
                isValid = false;
                
            }
            
        } else {
            
            // user is not changing password, ignore it
            
        }
        
    } else {
        
        //new user
        
        if(!validPassword(password)) {
            
            displayMessage('error','Invalid password. Must be at least 6 characters.');
            isValid = false;
            
        }
        
        if(password != password2) {
            
            $('input[name="user_password"]').addClass('form-err');
            $('input[name="user_password2"]').addClass('form-err');
            displayMessage('error','Passwords do not match.');
            isValid = false;
            
        }
        
    }
    
    var email = $('input[name="user_email"]').val();
    
    if(!validEmail(email)) {
        
        $('input[name="user_email"]').addClass('form-err');
        displayMessage('error','Invalid email address.');
        isValid = false;
        
    }
    
    return isValid;
    
}

function togglePermissions() {
    
    var userType = $('select[name="user_type"]').val();
    
    if(userType == 'admin') {
        
        $('#admin_permissions').show();
        
    } else {
        
        $('#admin_permissions').hide();
        
    }
    
}

function validPassword(password) {
    
    if(password.length >= 6) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}

function validEmail(email) {
    
    if(email.length > 7) {
        
        return true;
        
    } else {
        
        return false;
        
    }
    
}