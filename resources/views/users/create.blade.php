@extends('layouts.app')

@section('content')
<h2>Пользователи</h2>

<table id="users-table" border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th><th>Имя</th><th>Email</th><th>Роль</th><th>Пароль</th><th>Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr data-id="{{ $user->id }}">
            <td>{{ $user->id }}</td>
            <td class="editable" data-field="name">{{ $user->name }}</td>
            <td class="editable" data-field="email">{{ $user->email }}</td>
            <td class="editable" data-field="role">{{ $user->role }}</td>
            <td class="editable" data-field="password">******</td>
            <td>
                <button class="btn-delete">Удалить</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inline редактирование
    document.querySelectorAll('#users-table td.editable').forEach(td => {
        td.addEventListener('dblclick', function() {
            const original = td.innerText;
            const field = td.dataset.field;
            const tr = td.closest('tr');
            const id = tr.dataset.id;

            let input;
            if(field === 'role') {
                input = document.createElement('select');
                ['user','manager','admin'].forEach(r => {
                    const o = document.createElement('option');
                    o.value = r;
                    o.text = r;
                    if(r===original) o.selected = true;
                    input.appendChild(o);
                });
            } else if(field==='password'){
                input = document.createElement('input');
                input.type = 'password';
            } else {
                input = document.createElement('input');
                input.type = 'text';
                input.value = original;
            }

            td.innerHTML = '';
            td.appendChild(input);
            input.focus();

            const save = async () => {
                let value = input.value;
                if(field==='password' && value==='') { td.innerText = '******'; return; }

                let data = {};
                data[field] = value;
                if(field==='password') data[field]=value;

                try {
                    const res = await fetch(`/users/${id}`, {
                        method: 'PUT',
                        headers:{
                            'Content-Type':'application/json',
                            'X-CSRF-TOKEN':'{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    });
                    if(!res.ok) throw new Error('Ошибка');
                    td.innerText = field==='password'?'******':value;
                } catch(e){
                    alert('Ошибка сохранения');
                    td.innerText = original;
                }
            };

            input.addEventListener('blur', save);
            input.addEventListener('keydown', e=>{
                if(e.key==='Enter') input.blur();
                if(e.key==='Escape'){ td.innerText = original; }
            });
        });
    });

    // Удаление
    document.querySelectorAll('.btn-delete').forEach(btn=>{
        btn.addEventListener('click', async function(){
            if(!confirm('Удалить пользователя?')) return;
            const tr = btn.closest('tr');
            const id = tr.dataset.id;
            try{
                const res = await fetch(`/users/${id}`, {
                    method:'DELETE',
                    headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
                });
                if(!res.ok) throw new Error('Ошибка');
                tr.remove();
            } catch(e){ alert('Не удалось удалить'); }
        });
    });
});
</script>
@endsection