'use strict';

export class UIManager {

    static showLoader() {
        const loader = document.getElementById('loader');

        if (loader) {
            loader.style.display = 'block';
        }
    }

    static hideLoader() {
        const loader = document.getElementById('loader');

        if (loader) {
            loader.style.display = 'none';
        }
    }

    static showError(message) {
        alert(message);
    }

    static showSuccess(message) {
        alert(message);
    }
}
