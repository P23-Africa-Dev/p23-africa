export default class ConversationScrollManager {
    constructor(options = {}) {
        this.panels = (options.panels || []).filter(Boolean);
        this.cleanupTasks = [];
    }

    start() {
        this.panels.forEach((panel) => {
            let hovered = false;

            const onEnter = () => {
                hovered = true;
            };

            const onLeave = () => {
                hovered = false;
            };

            const onWheel = (event) => {
                if (!hovered) {
                    return;
                }

                event.preventDefault();
                panel.scrollTop += event.deltaY;
            };

            panel.addEventListener('mouseenter', onEnter);
            panel.addEventListener('mouseleave', onLeave);
            panel.addEventListener('wheel', onWheel, { passive: false });

            this.cleanupTasks.push(() => {
                panel.removeEventListener('mouseenter', onEnter);
                panel.removeEventListener('mouseleave', onLeave);
                panel.removeEventListener('wheel', onWheel);
            });
        });
    }

    stop() {
        this.cleanupTasks.forEach((task) => task());
        this.cleanupTasks = [];
    }
}
