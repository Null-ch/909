const table = document.querySelector('table[data-datatable-server]');

if (table) {
    const { default: DataTable } = await import('datatables.net');

    new DataTable(table, {
        processing: true,
        serverSide: true,
        ajax: { url: table.dataset.ajaxUrl },
        pageLength: 10,
        order: [[0, 'asc']],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'rates_count', name: 'rates_count' },
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
}
