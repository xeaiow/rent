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
            <div class="center-align">
                <ul>
                    <li id="reloadList"><a class="waves-effect waves-light btn transparent">預約列表</a></li>
                    <li id="add-rental"><a class="waves-effect waves-light btn transparent">新增預約</a></li>
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

<div id="add-rental-modal" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4>新增預約</h4>
        <div>
            <div class="input-field col s12">
                <input id="add-name" type="text" class="validate">
                <label >租借人</label>
            </div>
            <div class="input-field col s12">
                <input id="add-username" type="number" class="validate">
                <label >學號</label>
            </div>
            <div class="input-field col s12">
                <input id="add-title" type="text" class="validate">
                <label >原因</label>
            </div>
            <div class="input-field col s12">
                <input id="add-desc" type="text" class="validate">
                <label >描述</label>
            </div>
            <div class="input-field col s6">
                <input id="add-phone" type="text" class="validate">
                <label >聯絡電話</label>
            </div>
            <div class="col s12">
                <select id="room">
                    <option value="" disabled selected>請選擇</option>
                    <option value="102">資管 102</option>
                    <option value="103">資管 103</option>
                    <option value="104">資管 104</option>
                    <option value="203">資管 203</option>
                    <option value="205">資管 205</option>
                </select>
                <div class="add-container-text">日期</div>
                <input type="date" id="rentDate" value="2018-09-16" required />
                <div class="add-container-text">開始</div>
                <input type="time" id="start" min="9:00" max="21:30" required />
                <div class="add-container-text">結束</div>
                <input type="time" id="end" min="9:00 " max="21:30" required />
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="waves-effect waves-light btn" id="add">確定</a>
        <a class="modal-close waves-effect waves-light btn grey lighten-1">取消</a>
    </div>
</div>

@endsection