document.addEventListener('DOMContentLoaded', function () {
    const errorContainer = document.querySelector('.error');
    const closeButton = document.querySelector('.error__close');

    function showError() {
        errorContainer.style.top = '0';
    }

    function hideError() {
        errorContainer.style.top = '-100px';
    }

    closeButton.addEventListener('click', hideError);

});

document.addEventListener('DOMContentLoaded', function () {
    const errorContainer = document.querySelector('.info');
    const closeButton = document.querySelector('.info__close');

    function showError() {
        errorContainer.style.top = '0';
    }

    function hideError() {
        errorContainer.style.top = '-100px';
    }

    closeButton.addEventListener('click', hideError);

});

document.addEventListener('DOMContentLoaded', function () {
    const errorContainer = document.querySelector('.success');
    const closeButton = document.querySelector('.success__close');

    function showError() {
        errorContainer.style.top = '0';
    }

    function hideError() {
        errorContainer.style.top = '-100px';
    }

    closeButton.addEventListener('click', hideError);

});