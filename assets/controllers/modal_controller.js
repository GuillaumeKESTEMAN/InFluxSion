import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["dialog"];

    connect() {
        document.querySelector('button[data-action="modal#show"]').addEventListener('click', () => {
            console.log('open');
            this.show();
        });
    }

    show() {
        this.dialogTarget.showModal();
    }

    close() {
        this.dialogTarget.close();
    }
}
