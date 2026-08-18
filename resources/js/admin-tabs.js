export function initAdminTabs() {
    document.querySelectorAll('[data-admin-tabs]').forEach((tabsRoot) => {
        const tabButtons = tabsRoot.querySelectorAll('[data-tab-target]');
        const tabPanels = tabsRoot.querySelectorAll('[data-tab-panel]');

        tabButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const target = button.dataset.tabTarget;

                tabButtons.forEach((item) => {
                    item.classList.toggle('active', item === button);
                    item.setAttribute('aria-selected', item === button ? 'true' : 'false');
                });

                tabPanels.forEach((panel) => {
                    panel.classList.toggle('active', panel.dataset.tabPanel === target);
                });
            });
        });
    });
}
