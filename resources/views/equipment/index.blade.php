<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transtelecom</title>

    <link rel="icon" type="image/png" href="{{ asset('transtelecom.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet" integrity="sha384-yzOI+AGOH+8sPS29CtL/lEWNFZ+HKVVyYxU0vjId0pMG6xn7UMDo9waPX5ImV0r6" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/bs-3.3.7/jq-3.7.0/jszip-3.10.1/dt-2.3.4/af-2.7.1/b-3.2.5/b-colvis-3.2.5/b-html5-3.2.5/b-print-3.2.5/cr-2.1.2/cc-1.1.1/date-1.6.1/fc-5.0.5/fh-4.0.4/kt-2.12.1/r-3.0.7/rg-1.6.0/rr-1.5.0/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.3/sr-1.4.3/datatables.css" rel="stylesheet" integrity="sha384-2mk4UVmNAF+BFLuNoAB2TxuktLWCIPPk0NzCCWRV/dxaXsA2UZjHMeIdt373rx+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
    <header class="top-header">
        <div class="logo-time">
            <div class="logo-block">
                <a href="{{ route('main') }}">
                    <img src="ttc_logo.svg" alt="Логотип" class="logo">
                </a>
            </div>

            <div class="server-info">
                <div class="server-date">
                    <i class="fa-regular fa-calendar date-icon"></i>
                    <span id="server-date">{{ \Carbon\Carbon::now()->format('d.m.Y') }}</span>
                </div>
                <div class="server-time">
                    <i class="fa-regular fa-clock time-icon"></i>
                    <span id="server-time">{{ \Carbon\Carbon::now()->format('H:i:s') }}</span>
                </div>
                <div class="weather">
                    <span id="weather-icon"></span>
                    <span id="weather-temp"></span>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button class="btn btn-default table-switch active" data-table="equipment">Оборудование</button>
            <button class="btn btn-default table-switch" data-table="network">Network</button>
            <button class="btn btn-default table-switch" data-table="subscribers">Абоненты</button>
            <button class="btn btn-default table-switch" data-table="charts">Графики</button>
        </div>

        <a href="#" class="btn btn-add" id="btn-add" title="Добавить запись">
            <i class="fas fa-plus"></i>
            <i class="fas fa-network-wired"></i>
        </a>

        <a href="#" class="btn btn-filter" id="btn-filter" title="Фильтр">
            <i class="fas fa-filter"></i>
        </a>

        <!-- Выпадающий фильтр -->
        <div id="subscriberFilterDropdown" class="filter-dropdown" style="display:none;">
            <form id="subscriberFilterForm">
                <div class="filter-buttons">
                    <button type="submit" class="btn btn-primary">Применить</button>
                    <button type="button" id="filterResetBtn" class="btn btn-secondary">Сбросить</button>
                </div>
            </form>
        </div>


        <a href="#" class="btn btn-reset" id="resetFilters" title="Сбросить фильтры">
            <i class="fas fa-broom"></i>
        </a>

        <a href="#" class="btn btn-delete" id="btn-delete-selected" title="Удалить выбранную строку">
            <i class="fas fa-trash"></i>
        </a>

        <a href="#" class="dropdown-item" id="btn-export" title="Экспорт в Excel">
            <i class="fas fa-file-excel me-2"></i>
        </a>

        @auth
        <div style="position: fixed; top:15px; right:20px; z-index:9999;">
    
            <div style="position: relative; display: inline-block;">
        
                <!-- КНОПКА -->
                <button id="userMenuButton" style="
                    background: linear-gradient(135deg, #2563eb,rgb(44, 145, 4));
                    color: white;
                    padding: 8px 18px;
                    border: none;
                    border-radius: 999px;
                    cursor: pointer;
                    font-size: 14px;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
                ">
                    👤 {{ auth()->user()->email }}
                </button>

                <!-- ВЫПАДАЮЩЕЕ МЕНЮ -->
                <div id="userDropdown" style="
                    display: none;
                    position: absolute;
                    right: 0;
                    margin-top: 10px;
                    background: white;
                    min-width: 200px;
                    border-radius: 10px;
                    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
                    overflow: hidden;
                ">

                    <hr style="margin:0;">

                    <a href="{{ route('users.index') }}" style="
                        display:block;
                        padding:10px 15px;
                        text-decoration:none;
                        color:#333;
                    " onmouseover="this.style.background='#f3f4f6'" 
                    onmouseout="this.style.background='white'">
                        ⚙️ Настройки
                    </a>

                    <button id="darkModeToggle" style="
                        width:100%;
                        text-align:left;
                        padding:10px 15px;
                        border:none;
                        background:none;
                        cursor:pointer;
                        color:#333;
                    " onmouseover="this.style.background='#f3f4f6'" 
                    onmouseout="this.style.background='white'">
                        🌙 Тёмная тема
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="
                            width:100%;
                            text-align:left;
                            padding:10px 15px;
                            border:none;
                            background:none;
                            cursor:pointer;
                            color:#dc2626;
                        " onmouseover="this.style.background='#fee2e2'" 
                        onmouseout="this.style.background='white'">
                            Выйти
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endauth
    </header>

    {{-- Форма экспорта для оборудования --}}
    <form method="GET" action="{{ route('equipment.export') }}" id="exportForm" style="display:none;">
        <input type="hidden" name="search" id="export-search">
        <input type="hidden" name="ids" id="export-ids">
    </form>


    {{-- Таб "Оборудование" --}}
    <div id="tab-equipment" class="table-tab">
        <div class="table-responsive">
            <table id="equipment-table" class="modern-table">
                <thead>
                <tr>
                    <th>Наименование</th>
                    <th>Серийный номер</th>
                    <th>Инвентарный номер</th>
                    <th>Тип</th>
                    <th>Место установки</th>
                    <th>Дата установки</th>
                    <th>Статус</th>
                    <th>IP</th>
                    <th>Абонент</th>
                    <th>Город</th>
                    <th>Сегмент</th>
                    <th>Диллер</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- Таб "Network" --}}
    <div id="tab-network" class="table-tab" style="display:none;">
        <div class="table-responsive">
            <table id="network-table" class="modern-table">
                <thead>
                <tr>
                    <th>IP адрес</th>
                    <th>Оборудование</th>
                    <th>Местонахождение</th>
                    <th>Примечание</th>
                    <th>VLAN</th>
                    <th>Подсеть</th>
                    <th>Статус</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- Таб "Абоненты" --}}
    <div id="tab-subscribers" class="table-tab" style="display:none;">
        <div class="table-responsive">
            <table id="subscribers-table" class="modern-table">
                <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Город/Станция</th>
                    <th>Адрес</th>
                    <th>Услуга</th>
                    <th>Технология</th>
                    <th>Логин</th>
                    <th>Телефон</th>
                    <th>IP оборудования</th>
                    <th>Пароль</th>
                    <th>Гром Полоса</th>
                    <th>Шкаф 1</th>
                    <th>Шкаф 2</th>
                    <th>Коммутатор</th>
                    <th>Порт</th>
                    <th>Статус</th>
                    <th>Примечание</th>
                    <th>Дата подключения</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- Графики --}}
    <div id="charts-view" style="width:100%; max-width:900px; margin:auto; display:none;">
       <canvas id="equipmentChart"></canvas>
    </div>
</div>

{{-- Модалка: оборудование --}}
<div id="addEquipmentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" data-close="addEquipmentModal">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addEquipmentForm">
                @csrf

                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="modal-equipment">Наименование</label>
                        <input type="text" id="modal-equipment" name="equipment" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="modal-serial">Серийный номер</label>
                        <input type="text" id="modal-serial" name="serial" required>
                    </div>
                </div>

                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="modal-inventory">Инвентарный номер</label>
                        <input type="text" id="modal-inventory" name="inventory" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="modal-sity">Тип</label>
                        <select id="modal-sity" name="sity" required>
                            <option value="switch">Маршрутизатор</option>
                            <option value="router">Роутер</option>
                            <option value="rrl">РРЛ</option>
                            <option value="ott">ОТТ</option>
                            <option value="pc">Системный блок</option>
                            <option value="voip">VoIP шлюз</option>
                            <option value="basket">Корзина</option>
                            <option value="board">Плата</option>
                            <option value="media">Медиаконвертор</option>
                            <option value="passive">Пассивное</option>
                            <option value="electric">Эл.оборудование</option>
                            <option value="akb">АКБ</option>
                        </select>
                    </div>
                </div>

                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="modal-install">Место установки</label>
                        <input type="text" id="modal-install" name="install" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="equipment-date">Дата установки</label>
                        <input type="date" id="equipment-date" name="date" required>
                    </div>
                </div>

                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="modal-fio">IP</label>
                        <input type="text" id="modal-fio" name="FIO" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="modal-status">Статус</label>
                        <select id="modal-status" name="status" required>
                            <option value="work">В работе</option>
                            <option value="zip">ЗИП</option>
                            <option value="nowork">Не исправно</option>
                        </select>
                    </div>
                </div>

                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="modal-location">Абонент</label>
                        <input type="text" id="modal-location" name="location" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="modal-manager">Станция</label>
                        <select id="modal-manager" name="manager" required>
                            <option value="Уленты">Уленты</option>
                            <option value="Бозшаколь">Бозшаколь</option>
                            <option value="Чидерты">Чидерты</option>
                            <option value="ОП-116">ОП-116</option>
                            <option value="Екибастуз">Екибастуз</option>
                            <option value="Атыгай">Атыгай</option>
                            <option value="Майкаин">Майкаин</option>
                            <option value="Токубай">Токубай</option>
                            <option value="Карасор">Карасор</option>
                            <option value="Калкаман">Калкаман</option>
                            <option value="Таскудык">Таскудык</option>
                            <option value="Ушкулын">Ушкулын</option>
                            <option value="Акбидаик">Акбидаик</option>
                            <option value="Солнечный">Солнечный</option>
                            <option value="ГРЭС-1">ГРЭС-1</option>
                            <option value="ГРЭС-2">ГРЭС-2</option>
                        </select>
                    </div>
                </div>

                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="modal-segment">Сегмент</label>
                        <input type="text" id="modal-segment" name="segment" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="modal-dealer">Диллер</label>
                        <input type="text" id="modal-dealer" name="dealer" required>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" data-close="addEquipmentModal">Отмена</button>
            <button type="button" class="btn-save" id="saveEquipment">Сохранить</button>
        </div>
    </div>
</div>

{{-- Модалка: абоненты --}}
<div id="addSubscribersModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" data-close="addSubscribersModal">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addSubscriberForm">
                @csrf
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="sub-fio">ФИО</label>
                        <input type="text" id="sub-fio" name="fio" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="sub-city">Город</label>
                        <input type="text" id="sub-city" name="city" required>
                    </div>
                </div>
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="sub-address">Адрес</label>
                        <input type="text" id="sub-address" name="address" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="sub-service">Услуга</label>
                        <input type="text" id="sub-service" name="service" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="sub-technology">Технология</label>
                        <input type="text" id="sub-technology" name="technology">
                    </div>
                </div>
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="sub-login">Логин</label>
                        <input type="text" id="sub-login" name="login" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="sub-number">Телефон</label>
                        <input type="text" id="sub-number" name="number" required>
                    </div>
                </div>
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="sub-ip">IP оборудования</label>
                        <input type="text" id="sub-ip" name="ip" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="sub-password">Пароль</label>
                        <input type="text" id="sub-password" name="password" required>
                    </div>
                </div>
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="sub-band">Гром Полоса</label>
                        <input type="text" id="sub-band" name="band" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="sub-cabinet1">Шкаф 1</label>
                        <input type="text" id="sub-cabinet1" name="cabinet1" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="sub-cabinet2">Шкаф 2</label>
                        <input type="text" id="sub-cabinet2" name="cabinet2" required>
                    </div>
                </div>
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="sub-switch-address">Адрес Комутатора</label>
                        <input type="text" id="sub-switch-address" name="switch_address" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="sub-port">Порт</label>
                        <input type="text" id="sub-port" name="port" required>
                    </div>
                </div>
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="sub-active">Активен</label>
                        <select id="sub-active" name="active">
                            <option value="1">Активен</option>
                            <option value="0">Неактивен</option>
                        </select>
                    </div>
                    <div class="modal-form-group">
                        <label for="sub-note">Примечание</label>
                        <input type="text" id="sub-note" name="note" required>
                    </div>
                </div>
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="subscriber-date">Дата подключения</label>
                        <input type="date" id="subscriber-date" name="date" required>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" data-close="addSubscribersModal">Отмена</button>
            <button type="button" class="btn-save" id="saveSubscriber">Сохранить</button>
        </div>
    </div>
</div>

{{-- Модалка: network --}}
<div id="addNetworkModal" class="modal">
    <div class="modal-content">
                <div class="modal-form-group">
                    <label for="sub-band">Гром Полоса</label>
                    <input type="text" id="sub-band" name="band" required>
                </div>
                <div class="modal-form-group">
                    <label for="sub-cabinet1">Шкаф 1</label>
                    <input type="text" id="sub-cabinet1" name="cabinet1" required>
                </div>
                <div class="modal-form-group">
                    <label for="sub-cabinet2">Шкаф 2</label>
                    <input type="text" id="sub-cabinet2" name="cabinet2" required>
                </div>
                <div class="modal-form-group">
                    <label for="sub-switch-address">Адрес Комутатора</label>
                    <input type="text" id="sub-switch-address" name="switch_address" required>
                </div>
                <div class="modal-form-group">
                    <label for="sub-port">Порт</label>
                    <input type="text" id="sub-port" name="port" required>
                </div>
                <div class="modal-form-group">
                    <label for="sub-active">Активен</label>
                    <select id="sub-active" name="active">
                        <option value="1">Активен</option>
                        <option value="0">Неактивен</option>
                    </select>
                </div>
                <div class="modal-form-group">
                    <label for="sub-note">Примечание</label>
                    <input type="text" id="sub-note" name="note" required>
                </div>
                <div class="modal-form-group">
                    <label for="subscriber-date">Дата подключения</label>
                    <input type="date" id="subscriber-date" name="date" required>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" data-close="addSubscribersModal">Отмена</button>
            <button type="button" class="btn-save" id="saveSubscriber">Сохранить</button>
        </div>
    </div>
</div>

{{-- Модалка: network --}}
<div id="addNetworkModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="close" data-close="addNetworkModal">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addNetworkForm">
                @csrf
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="net-ip">IP адрес *</label>
                        <input type="text" id="net-ip" name="ip" required>
                    </div>
                    <div class="modal-form-group">
                        <label for="net-equipment">Оборудование</label>
                        <input type="text" id="net-equipment" name="equipment">
                    </div>
                </div>
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="net-location">Местонахождение</label>
                        <input type="text" id="net-location" name="location">
                    </div>
                    <div class="modal-form-group">
                        <label for="net-note">Примечание</label>
                        <input type="text" id="net-note" name="note">
                    </div>
                </div>
                <div class="modal-form-row">
                    <div class="modal-form-group">
                        <label for="net-vlan">VLAN</label>
                        <input type="text" id="net-vlan" name="vlan">
                    </div>
                    <div class="modal-form-group">
                        <label for="net-subnet">Подсеть</label>
                        <input type="text" id="net-subnet" name="subnet">
                    </div>
                    <div class="modal-form-group">
                        <label for="net-status">Статус</label>
                        <select id="net-status" name="status">
                            <option value="free">Свободен</option>
                            <option value="busy">Занят</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" data-close="addNetworkModal">Отмена</button>
            <button type="button" class="btn-save" id="saveNetwork">Сохранить</button>
        </div>
    </div>
</div>

{{-- Модалка: абоненты --}}
<div id="subscriberModal" class="modal">

    <div class="modal-content">

        <div class="modal-header">
            <h3 id="subName">Карточка клиента</h3>
        </div>

        <div class="modal-body" id="modalBody">
            <!-- JS будет вставлять поля -->
        </div>

        <div class="modal-footer">
            <button class="btn-save">Сохранить</button>
            <button class="btn-close" data-close="subscriberModal">Закрыть</button>
        </div>

    </div>

</div>
<!-- Скрипты -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js" integrity="sha384-OkuKCCwNNAv3fnqHH7lwPY3m5kkvCIUnsHbjdU7sN022wAYaQUfXkqyIZLlL0xQ/" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.js" integrity="sha384-P2rohseTZr3+/y/u+6xaOAE3CIkcmmC0e7ZjhdkTilUMHfNHCerfVR9KICPeFMOP" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs-3.3.7/jq-3.7.0/jszip-3.10.1/dt-2.3.4/af-2.7.1/b-3.2.5/b-colvis-3.2.5/b-html5-3.2.5/b-print-3.2.5/cr-2.1.2/cc-1.1.1/date-1.6.1/fc-5.0.5/fh-4.0.4/kt-2.12.1/r-3.0.7/rg-1.6.0/rr-1.5.0/sc-2.4.3/sb-1.8.4/sp-2.3.5/sl-3.1.3/sr-1.4.3/datatables.js" integrity="sha384-s9EsYmcVwXfr0bdXTt1MOACQt07g0Ex3livIJI0CQCKOXQ3qTtnBV5QmI9uSF/5Z" crossorigin="anonymous"></script>

<script>
// ВСЁ, что связано с таблицами, модалками и кнопками
$(document).ready(function () {
    let activeTable = 'equipment';

    const sityLabels = {
        switch: 'Маршрутизатор',
        router: 'Роутер',
        rrl: 'РРЛ',
        ott: 'ОТТ',
        pc: 'Системный блок',
        voip: 'VoIP шлюз',
        basket: 'Корзина',
        board: 'Плата',
        media: 'Медиаконвертор',
        passive: 'Пассивное',
        electric: 'Эл.оборудование',
        akb: 'АКБ' 
    };

    const statusLabels = {
        work: 'В работе',
        zip: 'ЗИП',
        nowork: 'не работает'
    };

    // Приведение значения "активен/неактивен" между БД (1/0) и UI (текст)
    function normalizeActiveValue(val) {
        // Возвращаем строку, чтобы совпадать с валидацией Laravel (nullable|string)
        if (val === 1 || val === '1' || val === true || val === 'true' || val === 'Активен') return '1';
        if (val === 0 || val === '0' || val === false || val === 'false' || val === 'Неактивен') return '0';
        return val;
    }

    function activeValueToLabel(val) {
        return normalizeActiveValue(val) == 1 ? 'Активен' : 'Неактивен';
    }

    const managers = [
        'Уленты', 'Бозшаколь', 'Чидерты', 'ОП-116', 'Екибастуз',
        'Атыгай', 'Майкаин', 'Токубай', 'Карасор', 'Калкаман',
        'Таскудык', 'Ушкулын', 'Акбидаик', 'Солнечный', 'ГРЭС-1', 'ГРЭС-2'
    ];

    const fieldLabels = {
        'fio': 'ФИО',
        'city': 'Город/Станция',
        'address': 'Адрес',
        'service': 'Услуга',
        'technology': 'Технология',
        'login': 'Логин',
        'number': 'Телефон',
        'ip': 'IP адрес',
        'password': 'Пароль',
        'band': 'Гром Полоса',
        'cabinet1': 'Шкаф 1',
        'cabinet2': 'Шкаф 2',
        'switch_address': 'Коммутатор',
        'port': 'Порт',
        'active': 'Статус',
        'note': 'Примечание',
        'date': 'Дата'
    };

    const sections = {
        client: ['fio', 'address', 'phone', 'login', 'password'],
        network: ['band', 'switch_address', 'port', 'cabinet1', 'cabinet2', 'active'],
        other: ['date', 'note']
    };

    const equipmentTable = $('#equipment-table').DataTable({
        ajax: "{{ route('equipment.data') }}",
        autofill: true,
        colReorder: true,
        select: true,
        scrollY: 'calc(100vh - 220px)',
        deferRender: true,
        scroller: true,
        columnControl: [['searchList']],
        columns: [
            { data: 'equipment', orderable: true, searchable: true },
            { data: 'serial', orderable: true, searchable: true },
            { data: 'inventory', orderable: true, searchable: true },
            {
                data: 'sity',
                orderable: true,
                searchable: true,
                render: function (data) { return sityLabels[data] ?? data; }
            },
            { data: 'install', orderable: true, searchable: true },
            { data: 'date', orderable: true, searchable: true },
            {
                data: 'status',
                orderable: true,
                searchable: true,
                render: function (data) {
                    // Сначала приводим к ключу statusLabels
                    let label = statusLabels[data] ?? data;

                    switch (label) {
                        case 'В работе':
                            return `<span class="status-working">${label}</span>`;
                        case 'не работает':
                            return `<span class="status-broken">${label}</span>`;
                        case 'ЗИП':
                            return `<span class="status-zip">${label}</span>`;
                        default:
                            return label; // если что-то не учтено
                    }
                }
            },
            { data: 'FIO', orderable: true, searchable: true },
            { data: 'location', orderable: true, searchable: true },
            { data: 'manager', orderable: true, searchable: true },
            { data: 'segment', orderable: true, searchable: true },
            { data: 'dealer', orderable: true, searchable: true }
        ],
        order: [[0, 'asc']]
    });

    const networkTable = $('#network-table').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('network.data') }}",
        autofill: true,
        colReorder: true,
        select: true,
        scrollY: 'calc(100vh - 220px)',
        deferRender: true,
        scroller: true,
        columnControl: [['searchList']],
        columns: [
            { data: 'ip', orderable: true, searchable: true },
            { data: 'equipment', orderable: true, searchable: true },
            { data: 'location', orderable: true, searchable: true },
            { data: 'note', orderable: true, searchable: true },
            { data: 'vlan', orderable: true, searchable: true },
            { data: 'subnet', orderable: true, searchable: true },
            {
                data: 'status',
                orderable: true,
                searchable: true,
                render: function (data) {
                    const isFree = !data || data === 'free' || data === 'Свободен';
                    const label = isFree ? 'Свободен' : 'Занят';
                    const color = isFree ? 'green' : 'red';
                    return '<span style="color:' + color + '; font-weight:600;">' + label + '</span>';
                }
            }
        ],
        order: [[0, 'asc']]
    });

    let subscribersFilter = {};

    const subscribersTable = $('#subscribers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('subscribers.data') }}",
            data: function(d){
                d.filter = subscribersFilter;
            }
        },
        autofill: true,
        colReorder: true,
        select: true,
        scrollY: 'calc(100vh - 220px)',
        deferRender: true,
        scroller: true,
        columns: [
            { data: 'fio', orderable: true, searchable: true },
            { data: 'city', orderable: true, searchable: true },
            { data: 'address', orderable: true, searchable: true },
            { data: 'service', orderable: true, searchable: true },
            { data: 'technology', orderable: true, searchable: true },
            { data: 'login', orderable: true, searchable: true, visible: false },
            { data: 'number', orderable: true, searchable: true },
            { data: 'ip', orderable: true, searchable: true },
            { data: 'password', orderable: true, searchable: true, visible: false },
            { data: 'band', name: 'band', visible: false },
            { data: 'cabinet1', name: 'cabinet1', visible: false },
            { data: 'cabinet2', name: 'cabinet2', visible: false },
            { data: 'switch_address', name: 'switch_address', visible: false },
            { data: 'port', name: 'port', visible: true },
            {
                data: 'active',
                name: 'active',
                visible: true,
                render: function (data) {
                    const isActive = normalizeActiveValue(data) == '1';
                    const label = isActive ? 'Активен' : 'Неактивен';
                    const color = isActive ? '#16a34a' : '#dc2626';
                    return '<span style="color:' + color + '; font-weight:600;">' + label + '</span>';
                }
            },
            { data: 'note', name: 'note', visible: false },
            { data: 'date', name: 'date', visible: false }
        ],
        order: [[0, 'asc']]
    });

    const tabs = {
        equipment: { button: '[data-table="equipment"]', block: '#tab-equipment', table: equipmentTable },
        network:   { button: '[data-table="network"]',   block: '#tab-network',   table: networkTable },
        subscribers:{button:'[data-table="subscribers"]',block:'#tab-subscribers',table: subscribersTable },
        charts:    { button: '[data-table="charts"]',    block: '#charts-view',   table: null }
    };

    function switchTable(type) {
        if (!tabs[type]) return;
        activeTable = type;
        Object.values(tabs).forEach(t => {
            $(t.block).hide();
            $(t.button).removeClass('active');
        });
        $(tabs[type].block).show();
        $(tabs[type].button).addClass('active');

        if (type === 'charts') {
            loadEquipmentChart();
        }
    }

    $('.table-switch').on('click', function (e) {
        e.preventDefault();
        switchTable($(this).data('table'));
    });

    function getActiveDataTable() {
        const cfg = tabs[activeTable];
        return cfg && cfg.table ? cfg.table : null;
    }

    function reloadActiveTable() {
        const dt = getActiveDataTable();
        if (dt) dt.ajax.reload(null, false);
    }

    $('#resetFilters').on('click', function(e){
        e.preventDefault();

        var table = getActiveDataTable(); // получаем активную таблицу
        if(!table) return;

        // 1️⃣ Сброс глобального поиска и сортировки
        table.search('');
        table.order([]);

        // 2️⃣ Очистка визуального поля поиска DataTables
        $('.dataTables_filter input').val('');

        // 3️⃣ Перезагрузка таблицы через AJAX (сбрасывает все фильтры columnControl)
        table.ajax.reload(null, false); // false = остаёмся на текущей странице
    });

    // Экспорт только для оборудования
    equipmentTable.on('search.dt draw.dt', function () {
        $('#export-search').val(equipmentTable.search());
    });
    $('#export-search').val(equipmentTable.search());

    $('#btn-export').on('click', function (e) {
        e.preventDefault();
        if (activeTable !== 'equipment') {
            alert('Экспорт доступен только для таблицы "Оборудование"');
            return;
        }

        const rowsData = equipmentTable.rows({ filter: 'applied' }).data();
        const ids = [];
        for (let i = 0; i < rowsData.length; i++) {
            if (rowsData[i].id) ids.push(rowsData[i].id);
        }

        if (!ids.length) {
            alert('Нет данных для экспорта (ничего не найдено по текущему фильтру)');
            return;
        }

        $('#export-search').val(equipmentTable.search());
        $('#export-ids').val(ids.join(','));
        $('#exportForm').submit();
    });

    // Закрытие модалок
    $('.close, .btn-cancel, .btn-close').on('click', function () {
        const $modal = $(this).closest('.modal');
        $modal.hide();
        const form = $modal.find('form')[0];
        if (form) form.reset();
    });
    $(window).on('click', function (e) {
        $('.modal').each(function () {
            if (e.target === this) {
                $(this).hide();
                const form = $(this).find('form')[0];
                if (form) form.reset();
            }
        });
    });

    // Карточка Клиента 
    $('#subscribers-table tbody').on('dblclick', 'tr', function () {

        const data = subscribersTable.row(this).data();
        if (!data) return;

        $('#subscriberModal').data('id', data.id);
        $('#subName').text('Карточка клиента — ' + (data.fio ?? ''));

        const $body = $('#modalBody');
        $body.empty();

        let row;
        let i = 0;

        Object.keys(fieldLabels).forEach(field => {

            let value = data[field] ?? '';

            if (i % 3 === 0) {
                row = $('<div class="modal-form-row"></div>');
                $body.append(row);
            }

            const group = $('<div class="modal-form-group"></div>');

            group.append(`<label>${fieldLabels[field]}</label>`);

            if (field === 'active') {
                const activeNumeric = normalizeActiveValue(value);
                group.append(`
                    <select
                        class="modal-input"
                        data-field="${field}">
                        <option value="1" ${activeNumeric == 1 ? 'selected' : ''}>Активен</option>
                        <option value="0" ${activeNumeric != 1 ? 'selected' : ''}>Неактивен</option>
                    </select>
                `);
            } else {
                group.append(`
                    <input type="text"
                        value="${value}"
                        class="modal-input"
                        data-field="${field}">
                `);
            }

            row.append(group);

            i++;

        });

        $('#subscriberModal').show();

    });

    //Сохранение инлайн в модалке 
    $(document).on('change', '.modal-input', function() {
        const input = $(this);
        const field = input.data('field');
        let value = input.val();
        if (field === 'active') value = normalizeActiveValue(value);
        const id = $('#subscriberModal').data('id');

        fetch('/subscribers/' + id, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ [field]: value })
        }).then(res => {
            if(res.ok) input.css('border-color','#2ecc71'); // зелёный = сохранено
            else input.css('border-color','#e74c3c'); // красный = ошибка
            setTimeout(()=>input.css('border-color','#e0e0e0'),800);
        });
    });

    //Сохранение данных
    $('#subscriberModal .btn-save').on('click', async function() {

    const id = $('#subscriberModal').data('id');
    const $inputs = $('#subscriberModal .modal-input');

    const payload = {};

    $inputs.each(function() {
        const field = $(this).data('field');
        let value = $(this).val();
        if (field === 'active') value = normalizeActiveValue(value);
        payload[field] = value;
    });

    try {
        const res = await fetch('/subscribers/' + id, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        });

        if (!res.ok) throw new Error(await res.text());

        // закрываем модалку
        $('#subscriberModal').hide();

        // обновляем таблицу абонентов
        subscribersTable.ajax.reload(null, false);

    } catch(err) {
        console.error(err);
        alert('Ошибка при сохранении: ' + (err?.message || err));
    }

    });

    $(document).on('click', '#btn-filter', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#subscriberFilterDropdown').toggle();
    });

    // скрываем фильтр при клике вне
    $(document).on('click', function(e){
        if(!$(e.target).closest('#subscriberFilterDropdown, #subscriberFilterBtn').length){
            $('#subscriberFilterDropdown').hide();
        }
    });

    // создаём поля фильтра
    const $filterForm = $('#subscriberFilterForm');
    $filterForm.empty();

    Object.keys(fieldLabels).forEach(field => {
        $filterForm.append(`
            <div class="filter-field">
                <label>${fieldLabels[field]}</label>
                <input type="text" name="${field}" placeholder="Фильтр по ${fieldLabels[field]}">
            </div>
        `);
    });

    // кнопка Применить должна быть внутри формы
    $filterForm.append(`
        <div class="filter-buttons">
            <button type="submit" class="btn btn-primary">Применить</button>
            <button type="button" id="filterResetBtn" class="btn btn-secondary">Сбросить</button>
        </div>
    `);

    // обработка submit
    $filterForm.on('submit', function(e){
        e.preventDefault();

        subscribersFilter = {};
        $(this).serializeArray().forEach(item => {
            if(item.value.trim() !== '') subscribersFilter[item.name] = item.value.trim();
        });

        console.log('Фильтр для сервера:', subscribersFilter);

        // перезагрузка таблицы с фильтром
        subscribersTable.ajax.reload(null, false); 
    });

    // сброс фильтра
    $('#filterResetBtn').on('click', function(){
        $filterForm[0].reset();
        subscribersFilter = {};
        subscribersTable.ajax.reload(null, false);
    });

    // Кнопка "Добавить"
    const addModals = {
        equipment: '#addEquipmentModal',
        network: '#addNetworkModal',
        subscribers: '#addSubscribersModal'
    };

    $('#btn-add').on('click', function () {
        const modal = addModals[activeTable];
        if (modal) $(modal).show();
    });

    // Сохранение оборудования
    $('#saveEquipment').on('click', async function () {
        const form = $('#addEquipmentForm')[0];
        const formData = new FormData(form);
        if (!formData.get('equipment')) {
            alert('Заполни все обязательные поля');
            return;
        }
        try {
            const res = await fetch('{{ route('equipment.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            if (!res.ok) throw new Error(await res.text());
            await res.json();
            reloadActiveTable();
            form.reset();
            $('#addEquipmentModal').hide();
        } catch (err) {
            console.error(err);
            alert('Не удалось сохранить оборудование: ' + err.message);
        }
    });

    // Сохранение абонента
    $('#saveSubscriber').on('click', async function () {
        const form = $('#addSubscriberForm')[0];
        const formData = new FormData(form);
        if (!formData.get('fio')) {
            alert('Заполни все обязательные поля');
            return;
        }
        try {
            const res = await fetch('{{ route('subscribers.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            if (!res.ok) throw new Error(await res.text());
            await res.json();
            reloadActiveTable();
            form.reset();
            $('#addSubscribersModal').hide();
        } catch (err) {
            console.error(err);
            alert('Не удалось сохранить абонента: ' + err.message);
        }
    });

    // Сохранение network
    $('#saveNetwork').on('click', async function () {
        const form = $('#addNetworkForm')[0];
        const formData = new FormData(form);
        if (!formData.get('ip')) {
            alert('Заполни все обязательные поля');
            return;
        }
        try {
            const res = await fetch('{{ route('network.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            });
            if (!res.ok) throw new Error(await res.text());
            await res.json();
            reloadActiveTable();
            form.reset();
            $('#addNetworkModal').hide();
        } catch (err) {
            console.error(err);
            alert('Не удалось сохранить сеть: ' + err.message);
        }
    });

    // Выделение строки
    $('table').on('click', 'tbody tr', function () {
        const dt = getActiveDataTable();
        if (!dt) return;
        $(dt.table().body()).find('tr').removeClass('row-active');
        $(this).addClass('row-active');
    });

    // Удаление
    $('#btn-delete-selected').on('click', function (e) {
        e.preventDefault();
        const dt = getActiveDataTable();
        if (!dt) return;

        const $row = $(dt.table().body()).find('tr.row-active').first();
        if (!$row.length) {
            alert('Сначала выберите строку для удаления');
            return;
        }

        const rowData = dt.row($row).data();
        if (!rowData || !rowData.id) {
            alert('Не найден ID записи');
            return;
        }

        let url = null;
        if (activeTable === 'equipment') url = '/equipment/' + rowData.id;
        if (activeTable === 'network')   url = '/network/' + rowData.id;
        if (activeTable === 'subscribers') url = '/subscribers/' + rowData.id;
        if (!url) return;

        if (!confirm('Удалить выбранную запись?')) return;

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(res => {
            if (!res.ok) throw new Error('Ошибка удаления');
            dt.ajax.reload(null, false);
        }).catch(err => {
            console.error(err);
            alert('Не удалось удалить: ' + err.message);
        });
    });

    // Подвкладки Network (фильтр по VLAN)
    $('.network-subtab').on('click', function (e) {
        e.preventDefault();
        const $li = $(this).parent();
        $li.addClass('active').siblings().removeClass('active');
        const segment = $(this).data('segment');
        if (segment === 'all') {
            networkTable.column(4).search('').draw();
        } else {
            networkTable.column(4).search(segment, true, false).draw();
        }
    });

    // Inline‑edit оборудования
    const inlineConfig = {
        equipment: {
            table: equipmentTable,
            selector: '#equipment-table',
            url: id => '/equipment/' + id,
            fields: [
                'equipment','serial','inventory','sity',
                'install','date','status','FIO',
                'location','manager','segment','dealer'
            ]
        },
        network: {
            table: networkTable,
            selector: '#network-table',
            url: id => '/network/' + id,
            fields: [
                'ip','equipment','location','note','vlan', 'subnet', 'status'
            ]
        },
        subscribers: {
            table: subscribersTable,
            selector: '#subscribers-table',
            url: id => '/subscribers/' + id,
            fields: [
                'fio','city','address','service','login','number',
                'ip','password','band','cabinet1','cabinet2',
                'switch_address','port','active','note','date'
            ]
        }
    };

    Object.keys(inlineConfig).forEach(type => {

        const cfg = inlineConfig[type];

        $(cfg.selector + ' tbody').on('dblclick', 'td', function () {

            const dt = cfg.table;
            const cell = dt.cell(this);
            const index = cell.index();
            if (!index) return;

            const field = cfg.fields[index.column];
            if (!field) return;

            const rowData = dt.row(index.row).data();
            if (!rowData || !rowData.id) return;

            let originalValue = rowData[field] ?? '';

            if ($(this).find('input').length) return;

            let $input;

            // ====== SELECT для оборудования ======
            if (type === 'equipment' && field === 'sity') {

                let options = '';
                for (const key in sityLabels) {
                    options += `<option value="${key}" ${rowData.sity === key ? 'selected' : ''}>
                                    ${sityLabels[key]}
                                </option>`;
                }
                $input = $(`<select class="inline-edit-input">${options}</select>`);

            }
            else if (type === 'equipment' && field === 'status') {

                let options = '';
                for (const key in statusLabels) {
                options += `<option value="${key}" ${rowData.status === key ? 'selected' : ''}>
                                ${statusLabels[key]}
                            </option>`;
            }
            $input = $(`<select class="inline-edit-input">${options}</select>`);

            }
            else if (type === 'equipment' && field === 'manager') {

                let options = '';
                managers.forEach(m => {
                    options += `<option value="${m}" ${rowData.manager === m ? 'selected' : ''}>
                                    ${m}
                                </option>`;
                });

                $input = $(`<select class="inline-edit-input">${options}</select>`);

            }
            else if (type === 'network' && field === 'status') {

                let options = `
                    <option value="free" ${rowData.status === 'free' ? 'selected' : ''}>Свободен</option>
                    <option value="busy" ${rowData.status === 'busy' ? 'selected' : ''}>Занят</option>
                `;

                $input = $(`<select class="inline-edit-input">${options}</select>`);
            }
            else {

                // обычное текстовое поле
                $input = $('<input>', {
                    type: 'text',
                    class: 'inline-edit-input',
                    value: originalValue
                });

            }
            

            $(this).empty().append($input);
            $input.focus();

            const finish = async (save) => {

                const newValue = $input.val();

                if (!save || newValue === originalValue) {
                    cell.data(originalValue).draw(false);
                    return;
                }

                try {

                    const payload = { ...rowData };
                    payload[field] = newValue;

                    const res = await fetch(cfg.url(rowData.id), {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    if (!res.ok) throw new Error(await res.text());

                    let displayValue = newValue;

                    if (type === 'equipment' && field === 'sity') {
                        displayValue = sityLabels[newValue] ?? newValue;
                    }

                    if (type === 'equipment' && field === 'status') {
                        displayValue = statusLabels[newValue] ?? newValue;
                    }

                    cell.data(displayValue).draw(false);

                } catch (err) {
                    console.error(err);
                    alert('Ошибка сохранения');
                    cell.data(originalValue).draw(false);
                }
            };

            $input.on('keydown', e => {
                if (e.key === 'Enter') finish(true);
                if (e.key === 'Escape') finish(false);
            });

            $input.on('blur', () => finish(true));
        });
    });

    $('#tab-equipment, #tab-network, #tab-subscribers').on('contextmenu', 'th', function(e) {
        e.preventDefault();

        const th = e.target.closest('th');
        if (!th) return;

        const tableId = $(this).closest('table').attr('id');

        // Проверяем, что это активная таблица
        if (tableId !== activeTable + '-table') return;

        let dt;
        switch (activeTable) {
            case 'equipment': dt = equipmentTable; break;
            case 'network':   dt = networkTable;   break;
            case 'subscribers': dt = subscribersTable; break;
            default: return;
        }

        const column = dt.column(th);
        dt.columnControl().show(column.index());
    });

    async function loadEquipmentChart() {

        try {
            const res = await fetch("{{ route('equipment.data') }}");
            const json = await res.json();

            const rows = json.data ?? json;

            const counts = {};

            rows.forEach(row => {
                const type = row.sity || 'unknown';
                counts[type] = (counts[type] || 0) + 1;
            });

            const labels = [];
            const data = [];

            for (const key in counts) {
                labels.push(sityLabels[key] ?? key);
                data.push(counts[key]);
            }

            const ctx = document.getElementById('equipmentChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Количество оборудования',
                        data: data
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

        } catch (e) {
            console.error('Ошибка графика:', e);
        }
    }

    document.getElementById('userMenuButton').addEventListener('click', function() {
        let dropdown = document.getElementById('userDropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Закрытие при клике вне меню
    document.addEventListener('click', function(event) {
        let button = document.getElementById('userMenuButton');
        let dropdown = document.getElementById('userDropdown');

        if (!button.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });
});

// Обновление "серверного" времени
let serverTime = new Date(@json(\Carbon\Carbon::now()->toIso8601String()));
setInterval(function () {
    serverTime.setSeconds(serverTime.getSeconds() + 1);
    const dateEl = document.getElementById('server-date');
    const timeEl = document.getElementById('server-time');
    if (dateEl && timeEl) {
        dateEl.textContent = serverTime.toLocaleString('ru-RU', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
        timeEl.textContent = serverTime.toLocaleString('ru-RU', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }
}, 1000);

// Погода в Экибастузе
async function updateWeather() {
    const apiKey = 'ce285e1469cd7a64e8a48d1d93d76dce';
    if (!apiKey) return;
    try {
        const res = await fetch(`https://api.openweathermap.org/data/2.5/weather?q=Ekibastuz,KZ&units=metric&lang=ru&appid=${apiKey}`);
        if (!res.ok) throw new Error('Не удалось получить погоду');
        const data = await res.json();
        const temp = Math.round(data.main.temp);
        const condition = data.weather[0].main || '';
        let icon = '☁';
        if (condition === 'Clear') icon = '☀';
        else if (condition === 'Clouds') icon = '☁';
        else if (condition === 'Rain' || condition === 'Drizzle') icon = '🌧';
        else if (condition === 'Thunderstorm') icon = '⛈';
        else if (condition === 'Snow') icon = '❄';
        const iconEl = document.getElementById('weather-icon');
        const tempEl = document.getElementById('weather-temp');
        if (iconEl && tempEl) {
            iconEl.textContent = icon;
            tempEl.textContent = `${temp}°C, Экибастуз`;
        }
    } catch (e) {
        console.error('Ошибка погоды:', e);
    }
}
updateWeather();
setInterval(updateWeather, 10 * 60 * 1000);

$(document).on('click', '#darkModeToggle', function() {
    $('body').toggleClass('dark-mode');

    if ($('body').hasClass('dark-mode')) {
        localStorage.setItem('darkMode', 'enabled');
    } else {
        localStorage.setItem('darkMode', 'disabled');
    }
});

// Применяем тему при загрузке страницы
if (localStorage.getItem('darkMode') === 'enabled') {
    $('body').addClass('dark-mode');
}
</script>
</body>
</html>
