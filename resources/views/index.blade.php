@extends('layouts.layout')
 
@section('title', '選擇')
 
@section('content')
<div class="mobile-header z-depth-1">
        <div class="row">
            <div class="col-2">
                <a href="#" data-activates="sidebar" class="button-collapse" style="">
                    <i class="material-icons">menu</i>
                </a>
            </div>
            <div class="col">
                <h4>Events</h4>
            </div>
        </div>
    </div>

    <div class="main-wrapper">
        <div class="sidebar-wrapper z-depth-2 side-nav fixed" id="sidebar">
            <div class="sidebar-title">
                <h4>CYIM Rent</h4>
                <h5 id="eventDayName">SIDEBAR SUB-TITLE</h5>
            </div>
            <div class="sidebar-events" id="sidebarEvents">
                <div class="empty-message">請在右方選擇欲租借日期</div>
            </div>
            <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal</a>
        </div>

        <div class="content-wrapper grey lighten-3">
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

                    <div class="calendar-footer">
                        <div class="emptyForm" id="emptyForm">
                            <h4 id="emptyFormTitle">目前無人租借</h4>
                            <a class="addEvent" id="changeFormButton">我要租借</a>
                        </div>
                        <div class="addForm" id="addForm">
                            <div class="row">
                                <div class="col s6">
                                    <select>
                                        <option value="" disabled selected>欲租借教室</option>
                                        <option value="102">102</option>
                                        <option value="104">104</option>
                                        <option value="201">201</option>
                                        <option value="203">203</option>
                                        <option value="205">205</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s3">
                                    <input id="eventTitleInput" type="text" class="validate">
                                    <label for="eventTitleInput">原因</label>
                                </div>
                                <div class="input-field col s3">
                                    <input id="eventDescInput" type="text" class="validate">
                                    <label for="eventDescInput">備註(可空)</label>
                                </div>
                            </div>
                            <div class="addEventButtons">
                                <a class="waves-effect waves-light btn light-blue lighten-1" id="addEventButton">繼續</a>
                                <a class="waves-effect waves-light btn grey lighten-2" id="cancelAdd">取消</a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- Modal -->
        <div id="modal1" class="modal modal-fixed-footer">
            <div class="modal-content">
                <h4>選擇開始及結束的時段</h4>
                <table>
                    <tbody>
                    <tr>
                        <td>
                            <label>
                                <input type="checkbox" class="selectTime" />
                                <span>8:00</span>
                            </label>
                        </td>
                        <td width="80%"></td> 
                    </tr>
                    <tr>
                        <td>
                            <label>
                                <input type="checkbox" class="selectTime" />
                                <span>8:30</span>
                            </label>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>9:00</span>
                        </label>
                    </td>
                        <td></td> 
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>9:30</span>
                        </label>
                    </td>
                        <td></td>  
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>10:00</span>
                        </label>
                    </td>
                        <td></td> 
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>10:30</span>
                        </label>
                    </td>
                        <td></td>  
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>11:00</span>
                        </label>
                    </td>
                        <td></td>
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>11:30</span>
                        </label>
                    </td>
                        <td></td>  
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>12:00</span>
                        </label>
                    </td> 
                        <td></td>
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>12:30</span>
                        </label>
                    </td> 
                        <td></td>
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>13:00</span>
                        </label>
                    </td> 
                        <td></td> 
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>13:30</span>
                        </label>
                    </td>
                        <td></td>
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>14:00</span>
                        </label>
                    </td>
                        <td></td>
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>14:30</span>
                        </label>
                    </td>
                        <td></td> 
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>15:00</span>
                        </label>
                    </td>
                        <td></td>  
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>15:30</span>
                        </label>
                    </td>  
                        <td></td>
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>16:00</span>
                        </label>
                    </td> 
                        <td></td> 
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>16:30</span>
                        </label>
                    </td>
                        <td></td> 
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>17:00</span>
                        </label>
                    </td>
                        <td></td> 
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>17:30</span>
                        </label>
                    </td>
                        <td></td>   
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>18:00</span>
                        </label>
                    </td>
                        <td></td>  
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>18:30</span>
                        </label>
                    </td>
                        <td></td>
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>19:00</span>
                        </label>
                    </td>   
                        <td></td>
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>19:30</span>
                        </label>
                    </td>
                        <td></td> 
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>20:00</span>
                        </label>
                    </td>
                        <td></td> 
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>20:30</span>
                        </label>
                    </td>
                        <td></td>
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>21:00</span>
                        </label>
                    </td>  
                        <td></td> 
                    </tr>
                    <tr>
                    <td>
                        <label>
                            <input type="checkbox" class="selectTime" />
                            <span>21:30</span>
                        </label>
                    </td>
                        <td></td> 
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <span id="previewTime"></span>
                <button class="btn waves-effect waves-light grey" id="clearTime" type="submit">重填
                    <i class="material-icons right">clear</i>
                </button>
                <button class="btn waves-effect waves-light blue darken-3 disabled" id="confirmTime" type="submit">確定
                    <i class="material-icons right">check</i>
                </button>
            </div>
        </div>

    </div>
@endsection