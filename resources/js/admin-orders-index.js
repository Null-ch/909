const table = document.querySelector('table[data-datatable-server]');

if (table) {
    const { default: DataTable } = await import('datatables.net');

    const tableInstance = new DataTable(table, {
        processing: true,
        serverSide: true,
        ajax: {
            url: table.dataset.ajaxUrl,
            data: (params) => {
                params.status = document.getElementById('filter-status')?.value || '';
                params.date_from = document.getElementById('filter-date-from')?.value || '';
                params.date_to = document.getElementById('filter-date-to')?.value || '';
                params.customer = document.getElementById('filter-customer')?.value || '';
            },
        },
        pageLength: 10,
        lengthChange: true,
        order: [[0, 'desc']],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'order_number', name: 'order_number', render: (data, type) => (type === 'display' || type === 'filter') ? data : data.replace(/<[^>]*>?/gm, '') },
            { data: 'customer', name: 'customer' },
            { data: 'total', name: 'total' },
            { data: 'status', name: 'status', render: (data, type) => (type === 'display' || type === 'filter') ? data : data.replace(/<[^>]*>?/gm, '') },
            { data: 'created_at', name: 'created_at' },
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

    ['filter-status', 'filter-date-from', 'filter-date-to'].forEach((id) => {
        document.getElementById(id)?.addEventListener('change', () => tableInstance.ajax.reload());
    });

    document.getElementById('filter-customer')?.addEventListener('input', () => {
        clearTimeout(window.orderCustomerFilterTimer);
        window.orderCustomerFilterTimer = setTimeout(() => tableInstance.ajax.reload(), 400);
    });
}
