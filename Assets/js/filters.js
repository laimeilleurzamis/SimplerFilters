(function() {
    /**
     * Main logic for dropdowns and multi-filtering system
     */
    function setupFilters() {
        /* Toggle regular dropdowns */
        document.addEventListener('click', function(e) {
            const toggle = e.target.closest('.simpler-dropdown-toggle');
            if (toggle) {
                const dropdown = toggle.closest('.simpler-dropdown');
                const wasActive = dropdown.classList.contains('active');
                closeAll();
                if (!wasActive) dropdown.classList.add('active');
                e.stopPropagation();
            } else if (!e.target.closest('.simpler-dropdown-menu') && !e.target.closest('.column-selector-dropdown')) {
                closeAll();
            }
        });

        /* Toggle Column Selector */
        document.addEventListener('click', function(e) {
            if (e.target.closest('.apply-filters-btn-toggle')) {
                const menu = document.querySelector('.column-selector-dropdown');
                menu.classList.toggle('active');
                e.stopPropagation();
            }
        });

        /* Generic Toggle for all list items (Dropdowns + Columns) */
        document.addEventListener('click', function(e) {
            const item = e.target.closest('li[data-value], .column-list li');
            if (!item) return;
            e.stopPropagation();

            const isChecked = item.getAttribute('data-checked') === 'true';
            item.setAttribute('data-checked', !isChecked);
            item.querySelector('.chk-icon').className = !isChecked ? 'fa fa-check-square-o chk-icon' : 'fa fa-square-o chk-icon';
        });

        /* Confirm and Apply search */
        document.addEventListener('click', function(e) {
            if (e.target.closest('.confirm-apply-btn')) {
                const wrapper = document.querySelector('.simpler-filter-wrapper');
                const baseUrl = wrapper.getAttribute('data-base-url');
                
                let query = 'status:open&status:closed';
                
                // Collect Dropdown Filters
                const selectedFilters = wrapper.querySelectorAll('.sub-dropdown li[data-checked="true"]');
                selectedFilters.forEach(el => query += ' ' + el.getAttribute('data-value'));

                // Collect Column Filters
                const selectedColumns = wrapper.querySelectorAll('.column-list li[data-checked="true"]');
                selectedColumns.forEach(el => query += ' ' + el.getAttribute('data-column-value'));

                window.location.href = baseUrl + '&search=' + encodeURIComponent(query);
            }
        });

        /* Reset all to status:open */
        document.addEventListener('click', function(e) {
            if (e.target.closest('.reset-filters-btn')) {
                const baseUrl = document.querySelector('.simpler-filter-wrapper').getAttribute('data-base-url');
                window.location.href = baseUrl + '&search=status:open&status:closed';
            }
        });
    }

    function closeAll() {
        document.querySelectorAll('.simpler-dropdown, .column-selector-dropdown').forEach(d => d.classList.remove('active'));
    }

    function syncFromUrl() {
        const wrapper = document.querySelector('.simpler-filter-wrapper');
        if (!wrapper) return;
        const currentSearch = decodeURIComponent(wrapper.getAttribute('data-current-search'));

        wrapper.querySelectorAll('li[data-value]').forEach(li => {
            const val = li.getAttribute('data-value');
            if (currentSearch.includes(val)) {
                li.setAttribute('data-checked', 'true');
                li.querySelector('.chk-icon').className = 'fa fa-check-square-o chk-icon';
            }
        });
    }

    setupFilters();
    syncFromUrl();
})();