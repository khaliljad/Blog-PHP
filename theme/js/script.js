// -------------------------- Login page ----------------------------//
const userName = document.getElementsByClassName('login-user-name'),
      mdp      = document.querySelectorAll('input[type="password"]');

// userName input change border color 

Array.from(userName).forEach(user => {

    function changeBorderuserName () {
        user.classList.add('border-bot');
        user.removeAttribute('placeholder');
    }

    function resetBorderuserName () {
        user.classList.remove('border-bot');
        user.setAttribute('placeholder', user.name);
    }
    user.addEventListener('focus', changeBorderuserName);
    user.addEventListener('blur', resetBorderuserName);
})

// password input change border color
Array.from(mdp).forEach(password => {
    
    function changeBorderMdp () {
        password.classList.add('border-bot');
        password.removeAttribute('placeholder');
    }
    
    function resetBorderMdp () {
        password.classList.remove('border-bot');
        password.setAttribute('placeholder', password.name);
    }
    password.addEventListener('focus', changeBorderMdp);
    password.addEventListener('blur', resetBorderMdp);
    
    
})
    // ---------- Show password --------
    
    var eyeIcon = document.getElementsByClassName('far fa-eye-slash');

Array.from(eyeIcon).forEach(eye => {

    eye.addEventListener('mouseover', function() {
        Array.from(mdp).forEach(password => {
            password.removeAttribute('type');
            password.setAttribute('type', 'text');
        })
    });

    eye.addEventListener('mouseout', function() {
        Array.from(mdp).forEach(password => {
            password.removeAttribute('text');
            password.setAttribute('type', 'password');
        })
    });
})

// --------------- change page ------------- //

const singIn = document.getElementById('singIn'),
      singUp = document.getElementById('signUp'),
      login  = document.getElementById('login'),
      registred = document.getElementById('registred');


singUp.addEventListener('click', function() {
    login.setAttribute('class', 'hidden');
    registred.removeAttribute('class');
});

singIn.addEventListener('click', function() {
    registred.setAttribute('class', 'hidden');
    login.removeAttribute('class');
});
