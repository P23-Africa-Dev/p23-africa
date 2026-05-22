export default class ScrollManager {
    constructor(container, options = {}) {
        this.container = container;
        this.newestButton = options.newestButton || null;
        this.bottomThreshold = Number(options.bottomThreshold || 120);
    }

    isNearBottom() {
        if (!this.container) {
            return true;
        }

        const distance = this.container.scrollHeight - this.container.scrollTop - this.container.clientHeight;
        return distance <= this.bottomThreshold;
    }

    scrollToBottom(behavior = 'smooth') {
        if (!this.container) {
            return;
        }

        this.container.scrollTo({
            top: this.container.scrollHeight,
            behavior,
        });
        this.hideButton();
    }

    showButton() {
        if (!this.newestButton) {
            return;
        }

        this.newestButton.classList.add('show');
    }

    hideButton() {
        if (!this.newestButton) {
            return;
        }

        this.newestButton.classList.remove('show');
    }

    syncButtonVisibility() {
        if (this.isNearBottom()) {
            this.hideButton();
        } else {
            this.showButton();
        }
    }
}