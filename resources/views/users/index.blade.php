@extends('layouts.admin')

@section('content')

<style>
body{
margin:0;
font-family:Arial;
background:#f6f8fa;
color:#111827;
}

/* TOP */
.top{
display:flex;
justify-content:space-between;
align-items:center;
padding:16px 20px;
background:#ffffff;
border-bottom:1px solid #e5e7eb;
}

.title{
font-size:15px;
font-weight:600;
color:#16a34a;
letter-spacing:1px;
}

.btn{
background:#16a34a;
border:none;
padding:8px 12px;
border-radius:8px;
color:white;
cursor:pointer;
font-size:13px;
}

.btn:hover{
background:#15803d;
}

/* LAYOUT */
.wrap{
padding:18px;
display:grid;
grid-template-columns: 2fr 1fr;
gap:16px;
}

/* USERS TABLE */
.card{
background:#ffffff;
border:1px solid #e5e7eb;
border-radius:12px;
overflow:hidden;
}

table{
width:100%;
border-collapse:collapse;
}

thead{
background:#f0fdf4;
}

th{
text-align:left;
padding:14px;
font-size:12px;
color:#16a34a;
border-bottom:1px solid #dcfce7;
letter-spacing:1px;
}

td{
padding:14px;
font-size:13px;
border-top:1px solid #f1f5f9;
}

tr:hover{
background:#f0fdf4;
}

/* BADGES */
.badge{
padding:3px 8px;
border-radius:999px;
font-size:11px;
border:1px solid;
}

.admin{
color:#16a34a;
border-color:#16a34a;
background:#dcfce7;
}

.user{
color:#166534;
border-color:#166534;
background:#f0fdf4;
}

/* DELETE */
.del{
background:white;
border:1px solid #ef4444;
color:#ef4444;
padding:6px 10px;
border-radius:8px;
cursor:pointer;
}

.del:hover{
background:#ef4444;
color:white;
}

/* LOG PANEL */
.log-card{
background:#ffffff;
border:1px solid #e5e7eb;
border-radius:12px;
display:flex;
flex-direction:column;
height:100%;
}

.log-header{
padding:12px;
font-size:12px;
color:#16a34a;
border-bottom:1px solid #dcfce7;
font-weight:600;
letter-spacing:1px;
}

.log-body{
padding:12px;
font-family:monospace;
font-size:12px;
color:#14532d;
overflow:auto;
height:100%;
}

.log-body div{
margin-bottom:6px;
white-space:pre-wrap;
}

/* MODAL */
.modal{
display:none;
position:fixed;
inset:0;
background:rgba(0,0,0,0.4);
align-items:center;
justify-content:center;
}

.box{
width:360px;
background:white;
border:1px solid #e5e7eb;
border-radius:12px;
padding:16px;
}

.box input, .box select{
width:100%;
padding:10px;
margin-bottom:10px;
border:1px solid #e5e7eb;
border-radius:8px;
font-size:13px;
}

.save{
width:100%;
padding:10px;
background:#16a34a;
border:none;
border-radius:8px;
color:white;
font-weight:600;
cursor:pointer;
}

.save:hover{
background:#15803d;
}
</style>

<!-- TOP -->
<div class="top">
    <div class="title">NOC CONTROL PANEL / USERS</div>
    <button class="btn" onclick="openModal()">+ Add User</button>
</div>

<div class="wrap">

<!-- USERS -->
<div class="card">
<table>
<thead>
<tr>
<th>ID</th>
<th>NAME</th>
<th>EMAIL</th>
<th>ROLE</th>
<th>ACTION</th>
</tr>
</thead>

<tbody>
@foreach($users as $user)
<tr data-id="{{ $user->id }}">
    <td>{{ $user->id }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>

    <td>
        <span class="badge {{ $user->role == 'admin' ? 'admin' : 'user' }}">
            {{ strtoupper($user->role) }}
        </span>
    </td>

    <td>
        <button class="del">Delete</button>
    </td>
</tr>
@endforeach
</tbody>

</table>
</div>

<!-- LOGS -->
<div class="log-card">
    <div class="log-header">
        REAL SERVER LOG STREAM
    </div>

    <div class="log-body" id="logBox">
        <div>> waiting for logs...</div>
    </div>
</div>

</div>

<!-- MODAL -->
<div class="modal" id="modal">
<div class="box">

<form method="POST" action="{{ route('users.store') }}">
@csrf

<input name="name" placeholder="Name">
<input name="email" placeholder="Email">
<input name="password" type="password" placeholder="Password">

<select name="role">
<option value="user">user</option>
<option value="admin">admin</option>
</select>

<button class="save">Create</button>

</form>

</div>
</div>

<script>
function openModal(){
document.getElementById('modal').style.display='flex';
}

/* DELETE USER */
document.querySelectorAll('.del').forEach(btn=>{
btn.addEventListener('click',async()=>{
const tr=btn.closest('tr');
const id=tr.dataset.id;

await fetch(`/users/${id}`,{
method:'DELETE',
headers:{
'X-CSRF-TOKEN':'{{ csrf_token() }}'
}
});

tr.remove();
});
});

/* REAL LOG LOADER (FROM LARAVEL API) */
async function loadLogs(){
try{
const res = await fetch('/system/logs');
const data = await res.json();

const box = document.getElementById("logBox");
if(!box) return;

box.innerHTML = '';

(data.logs || []).forEach(line=>{
const div=document.createElement('div');
div.innerText=line.trim();
box.appendChild(div);
});

box.scrollTop = box.scrollHeight;

}catch(e){
console.log("log error",e);
}
}

setInterval(loadLogs, 3000);
loadLogs();

</script>

@endsection