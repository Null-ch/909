const table = document.querySelector('table[data-datatable-server]');

if (table) {
    const { default: DataTable } = await import('datatables.net');

    const tableInstance = new DataTable(table, {
        processing: true,
        serverSide: true,
        ajax: {
            url: table.dataset.ajaxUrl,
            data: (params) => {
                params.category_id = document.getElementById('filter-category')?.value || '';
                params.status = document.getElementById('filter-status')?.value || '';
                params.price_min = document.getElementById('filter-price-min')?.value || '';
                params.price_max = document.getElementById('filter-price-max')?.value || '';
            },
        },
        pageLength: 10,
        lengthChange: true,
        order: [[0, 'desc']],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false, render: (data, type) => type === 'display' ? data : '' },
            { data: 'name', name: 'name', render: (data, type) => (type === 'display' || type === 'filter') ? data : data.replace(/<[^>]*>?/gm, '') },
            { data: 'price', name: 'price', render: (data, type) => type === 'display' ? data : data.replace(/<[^>]*>?/gm, '') },
            { data: 'quantity', name: 'quantity' },
            { data: 'is_active', name: 'is_active', render: (data, type) => (type === 'display' || type === 'filter') ? data : data.replace(/<[^>]*>?/gm, '') },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, render: (data, type) => type === 'display' ? data : '' },
        ],
        language: {
            search: '',
            searchPlaceholder: 'Поиск…',
            lengthMenu: 'Показать _MENU_',
            info: 'Записи _START_–_END_ из _TOTAL_',
            infoEmpty: 'Нет записей',
            infoFiltered: '(отфильтровано из _MAX_)',
            zeroRecords: 'Ничего не найдено',
            processing: 'Загрузка…',
            paginate: { previous: '←', next: '→' },
        },
    });

    ['filter-category', 'filter-status', 'filter-price-min', 'filter-price-max'].forEach((id) => {
        document.getElementById(id)?.addEventListener('change', () => tableInstance.ajax.reload());
        document.getElementById(id)?.addEventListener('input', () => {
            if (id.startsWith('filter-price')) {
                clearTimeout(window.productPriceFilterTimer);
                window.productPriceFilterTimer = setTimeout(() => tableInstance.ajax.reload(), 400);
            }
        });
    });
}
