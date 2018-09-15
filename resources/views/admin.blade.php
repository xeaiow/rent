@extends('layouts.adminLayout')
 
@section('title', '後臺')

@section('content')

<nav>
    <div class="nav-wrapper teal darken-2">
        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="sass.html">Sass</a></li>
            <li><a href="badges.html">Components</a></li>
            <li><a href="collapsible.html">JavaScript</a></li>
        </ul>
    </div>
</nav>
<div class="container" id="app">
    <div class="sidebar-wrapper z-depth-2 side-nav fixed" id="sidebar">
        <div class="sidebar-title">
            <h5>資管系教室預約系統</h5>
            <h5 id="eventDayName">後臺管理</h5>
        </div>
        <div class="sidebar-events" id="sidebarEvents">
            <div class="empty-message">
                <ul>
                    <li>預約列表</li>
                    <li>新增預約</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s9">
            <table class="highlight">
                <thead>
                    <tr> 
                        <th>租借人</th> 
                        <th>學號</th>
                        <th>原因</th>
                        <th>描述</th>
                        <th>聯絡電話</th>
                        <th>租借日期</th>
                        <th>租借期間</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody id="rental"></tbody>
            </table>
        </div>
        <div class="col s3" style="display:none;" id="edit-container">
            <h3 class="center-align">編輯</h3>
            <div class="input-field col s12">
                <input id="edit-title" placeholder="原因" type="text" class="validate">
            </div>
            <div class="input-field col s12">
                <input id="edit-desc" placeholder="描述" type="text" class="validate">
            </div>
            <div class="input-field col s12">
                <input id="edit-phone" placeholder="電話" type="text" class="validate">
            </div>
            <a class="waves-effect waves-light btn blue darken-3" id="edit-update">更新</a>
            <a class="waves-effect waves-light btn grey" id="edit-close">取消</a>
        </div>
    </div>
</div>

@endsection