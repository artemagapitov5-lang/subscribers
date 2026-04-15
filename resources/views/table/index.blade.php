<!DOCTYPE html>
<html>
<head>
    <title>Сборка колонок</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.css">
    <style>
        #columnsList { list-style: none; padding: 0; }
        #columnsList li { margin: 5px 0; padding: 5px; background: #f0f0f0; cursor: grab; }
    </style>
</head>
<body>

<h2>Настройка колонок</h2>
<ul id="columnsList">
    @foreach($userColumns as $col)
        <li data-col="{{ $col }}"><input type="checkbox" checked> {{ $col }}</li>
    @endforeach
</ul>
<button id="saveColumns">Сохранить</button>

<hr>

<h2>Таблица подписчиков</h2>
<table id="subscribersTable" class="display" style="width:100%">
    <thead>
        <tr id="tableHead"></tr>
    </thead>
</table>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

<script>
$(function(){
    // Сортировка drag-and-drop
    $("#columnsList").sortable();

    // Сохраняем выбранные колонки
    $("#saveColumns").on('click', function(){
        let cols = [];
        $("#columnsList li").each(function(){
            let checkbox = $(this).find("input[type=checkbox]");
            if(checkbox.is(":checked")) {
                cols.push($(this).data('col'));
            }
        });

        $.post("{{ route('table.columns.save') }}", {columns: cols, _token: "{{ csrf_token() }}"}, function(){
            alert("Колонки сохранены!");
            loadTable(cols);
        });
    });

    function loadTable(columns) {
        // Генерация шапки таблицы
        let thead = $("#tableHead");
        thead.empty();
        columns.forEach(c => thead.append('<th>' + c + '</th>'));

        // Инициализация DataTable
        if ($.fn.DataTable.isDataTable('#subscribersTable')) {
            $('#subscribersTable').DataTable().destroy();
        }

        $('#subscribersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('table.columns.data') }}",
            columns: columns.map(c => ({data: c, name: c})),
            order: [[0, 'asc']]
        });
    }

    // Первичная загрузка
    let initialCols = $("#columnsList li input:checked").map(function(){ return $(this).closest('li').data('col'); }).get();
    loadTable(initialCols);
});
</script>

</body>
</html>