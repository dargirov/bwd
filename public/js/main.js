window.addEventListener('DOMContentLoaded', (event) => {

    var searchBtn = document.querySelector('#search form a');
    searchBtn.onclick = function(e) {
        e.preventDefault();
        document.querySelector('#search form').submit();
        return false;
    }

    var mobileNavHeader = document.getElementById('header-mobile');
    var mobileNavOpened = false;
    var mobileNavBtn = document.getElementById('nav-icon1');
    mobileNavBtn.onclick = function(e) {
        if (!mobileNavOpened) {
            this.classList.add('open');
            mobileNavHeader.style.height = '400px';
            var divMenuOverlay = document.createElement('div');
            divMenuOverlay.id = 'menu-overlay';
            divMenuOverlay.onclick = function(e) {
                mobileNavBtn.classList.remove('open');
                mobileNavHeader.style.height = 0;
                this.remove();
                mobileNavOpened = false;
                return false;
            }
            document.body.appendChild(divMenuOverlay);
        } else {
            this.classList.remove('open');
            mobileNavHeader.style.height = 0;
            var divMenuOverlay = document.getElementById('menu-overlay');
            if (divMenuOverlay !== null) {
                divMenuOverlay.remove();
            }
        }

        mobileNavOpened = !mobileNavOpened;
    }

    var loginPopups = document.getElementsByClassName('login-popup');
    for (var i = 0; i < loginPopups.length; i++) {
        loginPopups[i].onclick = openLoginPopup;
    }

    function openLoginPopup(e) {
        e.preventDefault();
        var divOverlay = document.createElement('div');
        divOverlay.id = 'overlay';
        divOverlay.onclick = function(e) {
            divOverlay.remove();
            divPopup.remove();
        }

        var divPopup = document.createElement('div');
        divPopup.id = 'popup';
        var divInnerContainer = document.createElement('div');

        var strongTitle = document.createElement('strong');
        strongTitle.textContent = '???????? ?? ????????????';
        divInnerContainer.appendChild(strongTitle);

        var form = document.createElement('form');
        form.method = 'post';
        form.action = '/auth';

        var divEmail = document.createElement('div');
        var inputEmail = document.createElement('input');
        inputEmail.type = 'email';
        inputEmail.name = 'email';
        inputEmail.placeholder = 'Email ??????????';
        inputEmail.required = 'required';
        divEmail.appendChild(inputEmail);
        form.appendChild(divEmail);

        var divPassword = document.createElement('div');
        var inputPassword = document.createElement('input');
        inputPassword.type = 'password';
        inputPassword.name = 'password';
        inputPassword.placeholder = '????????????';
        inputPassword.required = 'required';
        divPassword.appendChild(inputPassword);
        form.appendChild(divPassword);

        var divSubmit = document.createElement('div');
        var inputSubmit = document.createElement('input');
        inputSubmit.type = 'submit';
        inputSubmit.value = '????????';

        var inputAction = document.createElement('input');
        inputAction.type = 'hidden';
        inputAction.name = 'action';
        inputAction.value = 'login';
        divSubmit.appendChild(inputAction);

        divSubmit.appendChild(inputSubmit);
        form.appendChild(divSubmit);
        divInnerContainer.appendChild(form);

        var divClose = document.createElement('div');
        divClose.id = 'popup-close';
        var aClose = document.createElement('a');
        aClose.onclick = function(e) {
            divOverlay.remove();
            divPopup.remove();
        }
        var imgClose = document.createElement('img');
        imgClose.src = '/images/close.svg';
        aClose.appendChild(imgClose);
        divClose.appendChild(aClose);
        divInnerContainer.appendChild(divClose);

        divPopup.appendChild(divInnerContainer);

        document.body.appendChild(divPopup);
        document.body.appendChild(divOverlay);
        return false;
    }

    var registerPopups = document.getElementsByClassName('register-popup');
    for (var i = 0; i < registerPopups.length; i++) {
        registerPopups[i].onclick = openRegisterPopup;
    }

    function openRegisterPopup(e) {
        e.preventDefault();

        var divOverlay = document.createElement('div');
        divOverlay.id = 'overlay';
        divOverlay.onclick = function(e) {
            divOverlay.remove();
            divPopup.remove();
        }

        var divPopup = document.createElement('div');
        divPopup.id = 'popup';
        var divInnerContainer = document.createElement('div');

        var strongTitle = document.createElement('strong');
        strongTitle.textContent = '???????????????? ?????? ????????????';
        divInnerContainer.appendChild(strongTitle);

        var form = document.createElement('form');
        form.method = 'post';
        form.action = '/auth';

        var divEmail = document.createElement('div');
        var inputEmail = document.createElement('input');
        inputEmail.type = 'email';
        inputEmail.name = 'email';
        inputEmail.placeholder = 'Email ??????????';
        inputEmail.required = 'required';
        divEmail.appendChild(inputEmail);
        form.appendChild(divEmail);

        var divPassword = document.createElement('div');
        var inputPassword = document.createElement('input');
        inputPassword.type = 'password';
        inputPassword.name = 'password';
        inputPassword.placeholder = '????????????';
        inputPassword.required = 'required';
        divPassword.appendChild(inputPassword);
        form.appendChild(divPassword);

        var divPassword2 = document.createElement('div');
        var inputPassword2 = document.createElement('input');
        inputPassword2.type = 'password';
        inputPassword2.name = 'password2';
        inputPassword2.placeholder = '???????????????? ????????????????';
        inputPassword2.required = 'required';
        divPassword2.appendChild(inputPassword2);
        form.appendChild(divPassword2);

        var divGr = document.createElement('div');
        divGr.style.display = 'flex';
        divGr.style.justifyContent = 'center';
        divGr.id = 'grecaptcha-reg';
        form.appendChild(divGr);

        var divSubmit = document.createElement('div');
        var inputSubmit = document.createElement('input');
        inputSubmit.type = 'submit';
        inputSubmit.value = '??????????????????????';

        var inputAction = document.createElement('input');
        inputAction.type = 'hidden';
        inputAction.name = 'action';
        inputAction.value = 'register';
        divSubmit.appendChild(inputAction);

        divSubmit.appendChild(inputSubmit);
        form.appendChild(divSubmit);
        divInnerContainer.appendChild(form);

        var divClose = document.createElement('div');
        divClose.id = 'popup-close';
        var aClose = document.createElement('a');
        aClose.onclick = function(e) {
            divOverlay.remove();
            divPopup.remove();
        }
        var imgClose = document.createElement('img');
        imgClose.src = '/images/close.svg';
        aClose.appendChild(imgClose);
        divClose.appendChild(aClose);
        divInnerContainer.appendChild(divClose);

        divPopup.appendChild(divInnerContainer);

        document.body.appendChild(divPopup);
        document.body.appendChild(divOverlay);

        appendRecaptcha();
        return false;
    }

    var topA = document.querySelector('#top a');
    topA.onclick = function(e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return false;
    }

    var top = document.getElementById('top');
    window.addEventListener('scroll', function(){
        var st = window.pageYOffset || document.documentElement.scrollTop;
        if (st > 300) {
            top.classList.remove('hidden');
        } else {
            top.classList.add('hidden');
        }
     }, false);

    var recaptchaAppended = false;
    function appendRecaptcha() {
        if (document.body.dataset.recaptcha == '1') {
            recaptchaAppended = true;
        }

        if (!recaptchaAppended) {
            var script = document.createElement('script');
            script.src = 'https://www.google.com/recaptcha/api.js?hl=bg&onload=onloadRecaptchaCallback&render=explicit';
            document.head.appendChild(script);
            recaptchaAppended = true;
        } else {
            onloadRecaptchaCallback();
        }
    }

});

function onloadRecaptchaCallback() {
    var divGr = document.getElementById('grecaptcha-reg');
    grecaptcha.render(divGr, {
        'sitekey': '6LetxrIeAAAAAJrGGuHfKw26AU9dfnzxY5TWbZQ8',
        'size': 'compact'
    });
}