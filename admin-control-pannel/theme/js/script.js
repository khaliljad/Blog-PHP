// -------------------------- Add members page ----------------------------//

var inputs = Array.from(document.getElementsByClassName('add-inputs'));
console.log(inputs);

inputs.forEach(input => {
    
    function changeBorderuserName () {
        input.removeAttribute('placeholder');
    }
    
    function resetBorderuserName () {
        input.setAttribute('placeholder', input.name);
    }

    input.addEventListener('focus', changeBorderuserName)
    input.addEventListener('blur', resetBorderuserName);
    })

// -------------------------- Login page ----------------------------//
const userName = document.getElementById('login-user-name'),
mdp = document.querySelector('input[type="password"]');

// userName input change border color 

function changeBorderuserName () {
    userName.classList.add('border-bot');
    userName.removeAttribute('placeholder');
}

function resetBorderuserName () {
    userName.classList.remove('border-bot');
    userName.setAttribute('placeholder', userName.name);
}
userName.addEventListener('focus', changeBorderuserName);
userName.addEventListener('blur', resetBorderuserName);


// password input change border color

mdp.addEventListener('focus', changeBorderMdp);
mdp.addEventListener('blur', resetBorderMdp);

function changeBorderMdp () {
    mdp.classList.add('border-bot');
    mdp.removeAttribute('placeholder');
}

function resetBorderMdp () {
    mdp.classList.remove('border-bot');
    mdp.setAttribute('placeholder', mdp.name);
}

// ---------- Show password --------

var eyeIcon = document.getElementById('eyepass');

eyeIcon.addEventListener('mouseover', function() {
    mdp.removeAttribute('type');
    mdp.setAttribute('type', 'text');
});

eyeIcon.addEventListener('mouseout', function() {
    mdp.removeAttribute('text');
    mdp.setAttribute('type', 'password');
});

