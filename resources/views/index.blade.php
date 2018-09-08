@extends('layouts.layout')
 
@section('title', '選擇')
 
@section('content')
    <div class="mobile-header z-depth-1">
        <div class="row">
            <div class="col-2">
                <a href="#" data-activates="sidebar" class="button-collapse">
                    <i class="material-icons">menu</i>
                </a>
            </div>
            <div class="col">
                <h5>資管系教室預約系統</h5>
                <h6>(不太會 RWD 請見諒)</h6>
            </div>
        </div>
    </div>

    
    <nav id="navbar">
        <div class="nav-wrapper">
            <a href="#!" class="brand-logo">Logo</a>
            <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li><a><i data-tooltip="我" class="material-icons tooltipped">face</i></a></li>
                <li><a><i data-tooltip="登出" id="logout" class="material-icons tooltipped">directions_run</i></a></li>
            </ul>
        </div>
    </nav>

  <ul class="sidenav" id="mobile-demo">
    <li><a href="sass.html">Sass</a></li>
    <li><a href="badges.html">Components</a></li>
    <li><a href="collapsible.html">Javascript</a></li>
    <li><a href="mobile.html">Mobile</a></li>
  </ul>

    <div class="main-wrapper">
        <div class="sidebar-wrapper z-depth-2 side-nav fixed" id="sidebar">
            <div class="sidebar-title">
                <h5>資管系教室預約系統</h5>
                <h5 id="eventDayName">SIDEBAR SUB-TITLE</h5>
            </div>
            <div class="sidebar-events" id="sidebarEvents">
                <div class="empty-message"></div>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="container">

                <div class="calendar-wrapper z-depth-2">

                    <div class="header-background">
                        <div class="calendar-header">

                            <a class="prev-button" id="prev">
                                <i class="material-icons">keyboard_arrow_left</i>
                            </a>
                            <a class="next-button" id="next">
                                <i class="material-icons">keyboard_arrow_right</i>
                            </a>

                            <div class="row header-title">

                                <div class="header-text">
                                    <h3 id="month-name">February</h3>
                                    <h5 id="todayDayName">Today is Friday 7 Feb</h5>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="calendar-content">
                        <div id="calendar-table" class="calendar-cells">
                            <div id="table-header">
                                <div class="row">
                                    <div class="col">一</div>
                                    <div class="col">二</div>
                                    <div class="col">三</div>
                                    <div class="col">四</div>
                                    <div class="col">五</div>
                                    <div class="col">六</div>
                                    <div class="col">日</div>
                                </div>
                            </div>
                            <div id="table-body" class="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- confirm modal -->
        <div id="modal1" class="modal modal-fixed-footer">
            <div class="modal-content">
                <h4>選擇教室及時間 <span style="color:#ad1457;" class="previewTime"></span></h4>
                <div>
                    <div class="col s12">
                        <select id="room">
                            <option value="" disabled selected>請選擇</option>
                            <option value="102">資管 102</option>
                            <option value="104">資管 104</option>
                            <option value="201">資管 201</option>
                            <option value="203">資管 203</option>
                            <option value="205">資管 205</option>
                        </select>
                    </div>
                    <div class="col s12">
                        <table id="selectTimeTable">
                            <tbody>
                            <tr>
                                <td width="50%" >
                                    <label>
                                        <input type="checkbox" class="selectTime" id="t28800t" />
                                        <span>8:00</span>
                                    </label>
                                </td>
                                <td width="50%"></td> 
                            </tr>
                            <tr>
                                <td>
                                    <label>
                                        <input type="checkbox" class="selectTime" id="t30600t" />
                                        <span>8:30</span>
                                    </label>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t32400t" />
                                    <span>9:00</span>
                                </label>
                            </td>
                                <td></td> 
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t34200t" />
                                    <span>9:30</span>
                                </label>
                            </td>
                                <td></td>  
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t36000t" />
                                    <span>10:00</span>
                                </label>
                            </td>
                                <td></td> 
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t37800t" />
                                    <span>10:30</span>
                                </label>
                            </td>
                                <td></td>  
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t39600t" />
                                    <span>11:00</span>
                                </label>
                            </td>
                                <td></td>
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t41400t" />
                                    <span>11:30</span>
                                </label>
                            </td>
                                <td></td>  
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t43200t" />
                                    <span>12:00</span>
                                </label>
                            </td> 
                                <td></td>
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t45000t" />
                                    <span>12:30</span>
                                </label>
                            </td> 
                                <td></td>
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t46800t" />
                                    <span>13:00</span>
                                </label>
                            </td> 
                                <td></td> 
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t48600t" />
                                    <span>13:30</span>
                                </label>
                            </td>
                                <td></td>
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t50400t" />
                                    <span>14:00</span>
                                </label>
                            </td>
                                <td></td>
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t52200t" />
                                    <span>14:30</span>
                                </label>
                            </td>
                                <td></td> 
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t54000t" />
                                    <span>15:00</span>
                                </label>
                            </td>
                                <td></td>  
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t55800t" />
                                    <span>15:30</span>
                                </label>
                            </td>  
                                <td></td>
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t57600t" />
                                    <span>16:00</span>
                                </label>
                            </td> 
                                <td></td> 
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t59400t" />
                                    <span>16:30</span>
                                </label>
                            </td>
                                <td></td> 
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t61200t" />
                                    <span>17:00</span>
                                </label>
                            </td>
                                <td></td> 
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t63000t" />
                                    <span>17:30</span>
                                </label>
                            </td>
                                <td></td>   
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t64800t" />
                                    <span>18:00</span>
                                </label>
                            </td>
                                <td></td>  
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t66600t" />
                                    <span>18:30</span>
                                </label>
                            </td>
                                <td></td>
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t68400t" />
                                    <span>19:00</span>
                                </label>
                            </td>   
                                <td></td>
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t70200t" />
                                    <span>19:30</span>
                                </label>
                            </td>
                                <td></td> 
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t72000t" />
                                    <span>20:00</span>
                                </label>
                            </td>
                                <td></td> 
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t73800t" />
                                    <span>20:30</span>
                                </label>
                            </td>
                                <td></td>
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t75600t" />
                                    <span>21:00</span>
                                </label>
                            </td>  
                                <td></td> 
                            </tr>
                            <tr>
                            <td>
                                <label>
                                    <input type="checkbox" class="selectTime" id="t77400t" />
                                    <span>21:30</span>
                                </label>
                            </td>
                                <td></td> 
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="modal-close waves-effect waves-green btn-flat">考慮</a>
                <a class="btn waves-effect waves-light grey disabled" id="clearTime">重來</a>
                <a class="btn waves-effect waves-light blue darken-3 disabled" id="rent">繼續</a>
            </div>
        </div>

        <!-- choose info -->
        <div id="modal2" class="modal modal-fixed-footer">
            <div class="modal-content">
                <h4>填寫租借資料</h4>
                <div>
                    <div class="input-field col s3">
                        <span style="color:#ad1457;" class="previewTime"></span>
                    </div>
                    <div class="input-field col s3">
                        <input id="eventTitleInput" type="text" class="validate">
                        <label for="eventTitleInput">原因</label>
                    </div>
                    <div class="input-field col s3">
                        <input id="eventDescInput" type="text" class="validate">
                        <label for="eventDescInput">備註(可空)</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="modal-close waves-effect waves-green btn-flat">取消</a>
                <a class="btn waves-effect waves-light blue darken-3" id="confirmRent">確定預約</a>
            </div>
        </div>

        <!-- Modal Structure -->
        <div id="loginModal" class="modal">
            <div class="modal-content">
                <h4>登入 iTouch 方可預約 <i class="material-icons focusPoint tooltipped" data-tooltip="別懷疑，就是你的 itouch 帳號跟密碼">help_outline</i></h4>
                <form class="col s12">
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="itouchUsername" type="text" class="validate">
                            <label for="itouchUsername">帳號</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="itouchPassword" type="password" class="validate">
                            <label for="itouchPassword">密碼</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-close waves-effect waves-green btn-flat">不要</a>
                <a class="btn waves-effect waves-light grey darken-4" id="login">登入</a>
            </div>
        </div>

    </div>
</div>
@endsection