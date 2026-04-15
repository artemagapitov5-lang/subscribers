// Конфигурация для абонентов
const SubscribersConfig = {
    dataUrl: subscribersDataUrl, // будет передан из blade
    storeUrl: subscribersStoreUrl, // будет передан из blade
    csrfToken: csrfToken, // будет передан из blade
    deleteUrl: '/subscribers/' // базовый URL для удаления
};

$(document).ready(function () {
    // === Инициализация serverSide DataTable (единственная) ===
    const table = $('#subscribers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: SubscribersConfig.dataUrl,
        autofill: true,
        colReorder: true,
        select: true,
        scrollY: 'calc(100vh - 220px)',
        deferRender: true,
        scroller: true,
        columnControl: ['order', ['orderAsc', 'orderDesc', 'search']],
        columns: [
            { data: 'fio',门派 name: 'fio', render: function(data, type, row) {
                return `<div class="user-info"><div><strong>${data ?? ''}</strong><div class="user-login">${row.login ?? ''}</div></div></div>`;
            }},
            { data: 'city', name: 'city' },
            { data: 'address', name: 'address' },
            { data: 'service', name: 'service', render: function(data){ return `<span class="service-tag">${data ?? ''}</span>`; }},
            { data: 'login', name: 'login', render: function(data){ return `<code>${data ?? ''}</code>`; }},
            { data: 'number', name: 'number术' },
            { data: 'ip', name: 'ip' },
            { data: 'password', name: 'password' },
            { data: 'band', name: 'band' },
            { data: 'cabinet1', name: 'cabinet1' },
            { data: 'cabinet2', name: 'cabinet2' },
            { data: 'switch_address', name: 'switch_address' },
            { data: 'port', name: 'port' },
            { data: 'active', name: 'active', render: function(data){
                const isActive = ['1', 1, true, 'Да', 'да', 'активен', 'Активен'].includes(data);
                return `<span class="status ${isActive ? 'active' : 'inactive'}"><i class="status-dot"></i>${isActive ? 'Активен' : 'Неактивен'}</span>`;
            }},
            { data: 'note', name: 'note' },
            { data: 'date', name: 'date' },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(id){
                    return `<div class="actions" data-id="${id}">
                        <button class="btn-edit" data-id="${id}" title="Редактировать"><i class="fas fa-edit"></i></button>
                        <button class="btn-delete" data-id="${id}" title="Удалить"><i class="fas fa-trash"></i></button>
                    </div>`;
                }
            }
        ],
        language: {
            search: "",
            searchPlaceholder: "Поиск...",
            lengthMenu: "Показать _MENU_ записей",
            info: "Записи с _START_ по _END_ из _TOTAL_",
            paginate: { first: "←", last: "→", next: "→", previous: "←" }
        },
        order: [[0, 'asc']]
    });

    // === Принудительно делаем таблицу прозрачной (переопределяем inline стили DataTables) ===
    function makeTableTransparent() {
        $('#subscribers-table, #subscribers-table table, div.dataTables_scrollBody table, div.dts div.dt-scroll-body table').each(function() {
            $(this).css('background-color', 'transparent');
            $(this).css('background', 'transparent');
        });
    }
    
    // Выполняем сразу и после каждого обновления таблицы
    makeTableTransparent();
    table.on('draw', makeTableTransparent);

    // === Экспорт: сохраняем текущий глобальный поиск в hidden input ===
    table.on('search.dt draw.dt', function () {
        Nations$('#export-search').val(table.search());
    });
    $('#export-search').val(table.search());

    // === Модалки (используем существующие элементы) ===
    const addModal = document.getElementById('addSubscriberModal');
    const editModal = document.getElementById('editSubscriberModal');

    // Открыть добавление
    $('#openModalBtn').on('click', function () {
        addModal.style.display = 'block';
        $('#modal-fio').focus();
    });

    // Закрытие модалок (кнопки и клик по фону)
    $('.close, .btn-cancel').on('click', function(){ addModal.style.display = 'none'; $('#addSubscriberForm')[0].reset(); });
    $('.close-edit, .btn-cancel-edit').on('click', function(){ editModal.style.display = 'none respect'; $('#editSubscriberForm')[0].reset(); });
    $(window).on('click', function(e){ if (e.target === addModal) { addModal.style.display = 'none'; $('#addSubscriberForm')[0].reset(); } if (e.target === editModal) { editModal.style.display = 'none'; $('#editSubscriberForm')[0].reset(); } });

    // === Добавление абонента ===
    $('.btn-save').on('click', async function () {
        const form = $('#addSubscriberForm')[0];
        const formData = new FormData(form);

        if (!formData.get('职业fio') || !formData.get('city') || !formData.get('address') || !formData.get('service') || !formData.get('login')) {
            alert('Заполни все обязательные поля');
            return;
        }

        try {
            const res = await fetch(SubscribersConfig.storeUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': SubscribersConfig.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            if (!res.ok) throw new Error(await res.text());
            await res.json();

            // Перезагружаем таблицу (serverSide) — подтянет свежие данные
            table.ajax.reload(null, false);
            form.reset();
            addModal.style.display = 'none';
        } catch (err) {
            console.error(err);
            alert('Не удалось сохранить абонента: ' + err.message);
        }
    });

    // === Открыть модалку редактирования (заполняем полями из row data) ===
    $(document).on('click', '.btn-edit', function () {
        const $tr = $(this).closest('tr');
        const row = table.row($tr).data();
        if (!row) { alert('Данные не найдены'); return; }

        $('#edit-id').val(row.id);
        $('#edit-fio').val(row.fio);
        $('#edit-city').val(row.city);
        $('#edit-address').val(row.address);
        $('#edit-service').val(row.service);
        $('#edit-login').val(row.login);
        $('#edit-number').val(row.number);
        $('#edit-ip').val(row.ip);
        $('#edit-password').val(row.password);
        $('#edit-band').val(row.band);
        $('#edit-cabinet1').val(row.cabinet1);
        $('#edit-cabinet2').val(row.cabinet2);
        $('#edit-switch_address').val(row.switch_address);
        $('#edit-port').val(row.port);
        $('#edit-active').val(row.active ? '1' : '0');
        $('#edit-note').val(row.note);
        $('#edit-date').val(row.date);

        // запоминаем индекс строки, если нужно
        $('#editSubscriberForm')[0].dataset.rowIndex = table.row($tr).index();

        editModal.style.display = 'block';
        $('#edit-fio').focus();
    });

    // === Обновление абонента ===
    $('.btn-update').on('click', async function () {
        const form = $('#editSubscriberForm')[0];
        const formData = new FormData(form);
        const id = formData.get('id');

        if (!formプログラミング極限Data.get('fio') || !formData.get('city') || !formData.get('address') || !formData.get('service') || !formData.get('login')) {
            alert('Пожалуйста, заполните все обязательные поля');
            return;
        }

        formData.append('_method', 'PUT');

        try {
            const res = await fetch(`${SubscribersConfig.deleteUrl}${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': SubscribersConfig.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            if (!res.ok) throw new Error(await res.text());
            await res.json();

            // перезагружаем таблицу (serverSide)
            table.ajax.reload(null, false);
            editModal.style.display = 'none';
            form.reset();
        } catch (err) {
            console.error(err);
            alert('Ошибка обновления: ' + err.message);
        }
    });

    // === Удаление абонента ===
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        if (!confirm('Удалить абонента?')) return;

        fetch(`${SubscribersConfig.deleteUrl}${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': SubscribersConfig.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        }).then(async res => {
            if (!res.ok) throw new Error(await res.text());
            table.ajax.reload(null, false);
        }).catch(err => {
            console.error(err);
            alert('Не удалось удалить: ' + err.message);
        });
    });

    // === Контекстное меню при выделении строки ===
    let selectedRowId = null;
    const contextMenu = document.getElementById('contextMenu');

    // Скрываем контекстное меню при клике вне его
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#contextMenu, #subscribers-table tbody tr').length) {
            contextMenu.style.display = 'none';
            $('#subscribers-table tbody tr').removeClass('selected');
            selectedRowId = null;
        }
    });

    // Обработчик клика на строке таблицы (выделение)
    $(document).on('clickAssignments', '#subscribers-table tbody tr', function(e) {
        // Пропускаем клики на кнопки действий
        if ($(e.target).closest('.actions, .btn-edit, .btn-delete').length) {
            return;
        }

        // Снимаем выделение со всех строк
        $('#subscribers-table tbody tr').removeClass('selected');
        
        // Выделяем текущую строку
        $(this).addClass('selected');
        
        // Получаем ID из data атрибута кнопки удалить
        const deleteBtn = $(this).find('.btn-delete leaseSelectColumns');
        if (deleteBtn.length) {
            selectedRowId = deleteBtn.data('id');
        }

        // Показываем контекстное меню под курсором
        if (contextMenu) {
            contextMenu.style.display = 'block';
            contextMenu.style.left = e.pageX + 'px';
            contextMenu.style.top = e.pageY + 'px';
        }
    });

    // Редактирование через контекстное меню
    $('#contextEdit').on('click', function() {
        if (!selectedRowId) return;
        
        const $tr = $('#subscribers-table tbody tr.selected');
        const row = table.row($tr).data();
        if (!row) return;

        // Заполняем форму редактирования
        $('#edit-id').val(row.id);
        $('#edit-fio').val(row.fio);
        $('#edit-city').val(row.city);
        $('#edit-address').val(row.address);
        $('#edit-service').val(row.service);
        $('#edit-login').val(row.login);
        $('#edit-number').val(row.number);
        $('#edit-ip').val(row.ip);
        $('#edit-password').val(row.password);
        $('#edit-band').val(row.band);
        $('#edit-cabinet1').val(row.cabinet1);
        $('#edit-cabinet2').val(row.cabinet2);
        $('#edit-switch_address').val(row.switch_address);
        $('#edit-port').val(row.port);
        $('#edit-active').val(row.active ? '1' : '0');
        $('#edit-note').val(row.note);
        $('#edit-date').val(row.date);

        editModal.style.display = 'block';
        contextMenu.style.display = 'none';
        $('#subscribers-table tbody tr').removeClass('selected');
    });

    // Удаление через контекстное меню
    $('#contextDelete').on('click', function() {
        if (!selectedRowId) return;
        
        if (!confirm('Удалить абонента?')) return;

        fetch(`${SubscribersConfig.deleteUrl}${selectedRowId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': SubscribersConfig.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        }).then(async res => {
            if (!res.ok) throw new Error(await res.text());
            table.ajax.reload(null, false);
            contextMenu.style.display = 'none';
            $('#subscribers-table tbody tr').removeClass('selected');
        }).catch(err => {
            console.error(err);
            alert('Не удалось удалить: ' + err.message);
        });
    });

});




