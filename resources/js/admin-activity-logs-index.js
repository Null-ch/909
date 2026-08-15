const table = document.querySelector('table[data-datatable-server]');

if (table) {
    const { default: DataTable } = await import('datatables.net');

    new DataTable(table, {
        processing: true,
        serverSide: true,
        ajax: {
            url: table.dataset.ajaxUrl,
        },
        pageLength: 15,
        lengthChange: true,
        order: [[0, 'desc']],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user', name: 'user' },
            { data: 'action', name: 'action' },
            { data: 'entity', name: 'entity' },
            { data: 'description', name: 'description' },
            { data: 'created_at', name: 'created_at' },
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
