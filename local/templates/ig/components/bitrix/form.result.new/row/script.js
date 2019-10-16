'use strict';

function Form(selector) {
    this.selector = selector;
    this.forms = [];

    this.send = function (form) {
        let data = new FormData(form);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "/local/ajax/form_new.php");
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.send(data);

        xhr.onreadystatechange = function () {
            if (this.readyState !== 4) {
                return;
            }
            let response = JSON.parse(this.responseText);
            if (response.result === 'success') {
                form.innerHTML = response.string;
                return;
            }
            alert(response.error);
        }


    };
    this.setEvents = function () {
        this.forms = document.querySelectorAll(this.selector);
        let self = this;
        this.forms.forEach(function (form) {
            if (form.addEventListener) {
                form.addEventListener(
                    'submit',
                    function (e) {
                        e.preventDefault();
                        self.send(form);
                        return false;
                    },
                    true
                );
            } else {
                form.attachEvent(
                    'onsubmit',
                    function (e) {
                        e.preventDefault();
                        self.send(form);
                        return false;
                    }
                );
            }
        });
    };
    this.setEvents();
    return this;
}

document.addEventListener("DOMContentLoaded", function () {
    let form = new Form('.js-callback-form');
});