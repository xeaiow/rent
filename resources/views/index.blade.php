@extends('layouts.layout')
 
@section('content')
    
    <nav id="navbar" class="grey darken-3">
        <div class="nav-wrapper">
            <a href="#!" class="title">資管系教室預約系統</a>
            <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li><a class="waves-effect waves-teal btn-flat nav-about">關於</a></li>
                <li><a><i data-tooltip="上課時段(Alt+D)" class="material-icons tooltipped open-course">announcement</i></a></li>
                <li><a><i data-tooltip="我(Alt+R)" class="material-icons tooltipped my">face</i></a></li>
                <li><a><i data-tooltip="登出 (Alt+Q)" class="material-icons tooltipped logout">directions_run</i></a></li>
            </ul>
        </div>
        
    </nav>

    <div class="center-align booking-margin mobile-booking">
        <a class="waves-effect waves-light btn booking">預定</a>
    </div>

    <ul class="sidenav" id="mobile-demo">
        <li class="my"><a><i data-tooltip="我 (Alt+R)" class="material-icons tooltipped">face</i>我</a></li>
        <li class="open-course"><a><i data-tooltip="上課時段 (Alt+D)" class="material-icons tooltipped">announcement</i>上課時段</a></li>
        <li class="nav-about"><a><i data-tooltip="登出 (Alt+Q)" class="material-icons tooltipped">child_care</i>關於</a></li>
        <li class="logout"><a><i data-tooltip="登出 (Alt+Q)" class="material-icons tooltipped">directions_run</i>登出</a></li>
    </ul>

    <div class="main-wrapper">
        <div class="sidebar-wrapper z-depth-2 side-nav fixed" id="sidebar">
            <div class="sidebar-title">
                <h5>資管系教室預約系統</h5>
                <h5 id="eventDayName"></h5>
            </div>
            <div class="center-align booking-margin">
                <a class="waves-effect waves-light btn booking">預定</a>
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
                <h4 id="instruction">請選擇借用教室 <span style="color:#ad1457;" class="previewTime"></span></h4>
                <div>
                    <div class="col s12">
                        <select id="room">
                            <option value="" disabled selected>請選擇</option>
                            <option value="103">資管 103</option>
                            <option value="104">資管 104</option>
                            <option value="203">資管 203</option>
                            <option value="205">資管 205</option>
                        </select>
                    </div>
                    <div class="col s12">
                        <table id="selectTimeTable">
                            <tbody>
                            <tr>
                            <td width="10%">
                                <label>
                                    <input type="checkbox" class="selectTime" id="t32400t" />
                                    <span>9:00</span>
                                </label>
                            </td>
                                <td width="90%"></td> 
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
                        <label for="eventTitleInput">原因*</label>
                    </div>
                    <div class="input-field col s3">
                        <input id="eventPhoneInput" type="tel" class="validate">
                        <label for="eventPhoneInput">聯絡電話*</label>
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
                <h4>登入 iTouch 方可預約 <i class="material-icons focusPoint tooltipped" data-tooltip="沒錯，就是你的 itouch 帳號跟密碼">help_outline</i></h4>
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
                <a class="modal-close waves-effect waves-green btn-flat" id="doNot">不要</a>
                <a class="btn waves-effect waves-light grey darken-4" id="login">登入</a>
            </div>
        </div>

        <div id="terms" class="modal modal-fixed-footer">
            <div class="modal-content">
                <h4>登記即代表您同意遵守</h4>
                <div>
                    <ul id="term-list">
                        <li>1. 請確實登記開始與結束的時間</li>
                        <li>2. 系上場地僅限資管系學生使的，若有外系同學，需有資管系學生在場，否則不的借用。</li>
                        <li>3. 開放借用之場地 102、103、104、203、205</li>
                        <li>4. 開放時間為週一至週五 9:00 ~ 21:35 (系辦上班日)，逾時不候</li>
                        <li>5. 借用時段：為顧及其他學生使用權益，嚴禁整天借用教室。平日每次以 3 小時為上限，除特殊情況請先洽詢系辦。</li>
                        <li>6. 優先次序：上課 → 系務會議 → 老師借用 → 已登記借用 → 其他</li>
                        <li>7. 限全系活動 (系學會)得延長借用時間及借用鑰匙外，其餘一律遵守開放借用時間。</li>
                        <li>8. 專題審查、SA、SE 競賽前一周起至競賽日，為求每組能公平使用，限以「組別」為單位借用，<u>最多3小時/天</u>，請事先登記預借教室。</li>
                        <li>9. 使用後，申請人需負責環境清潔，確實執行關燈、關冷氣、、窗戶及門皆需上鎖，並由資管系工讀生驗收，場地設備如有污損毀壞，由申請人依規定負責賠償。</li>
                        <li>10. 於借用時間未到達半小時以上，則取消當次借用資格，得以讓出場地給下一位使用。</li>
                        <li>11. 若提早使用完畢請告知系辦。</li>
                        <li>12. 以上規定如經系辦單位檢查，發現未遵守上述規定 (例如：借用時間、環境清潔等..)，則處罰申請人及其組別「停止使用一學期」</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <a class="waves-effect waves-green btn-flat" id="doNotAgree">不要</a>
                <a class="btn waves-effect waves-light grey darken-4" id="agree">同意並繼續</a>
            </div>
        </div>

        <div id="course" class="modal modal-fixed-footer">
            <div class="modal-content white">
                <h4>資管系上課時段</h4>
                <div>
                    <ul id="term-list">
                        <li>請參考以下課表自行避開上課時段，以免租借後發現當日為資管系正式課程時段。</li>
                        <li>自動避開的工程正在開發中，請見諒！</li>
                    </ul>
                    <div class="row">
                        <div class="col s12">
                            <ul class="tabs">
                                <li class="tab col s3"><a href="#103">103</a></li>
                                <li class="tab col s3"><a href="#104">104</a></li>
                                <li class="tab col s3"><a href="#203">203</a></li>
                                <li class="tab col s3"><a href="#205">205</a></li>
                            </ul>
                        </div>
                        <div id="103" class="col s12"><img class="materialboxed" src="https://i.imgur.com/WjGWCFW.jpg" alt="資管103"></div>
                        <div id="104" class="col s12"><img class="materialboxed" src="https://i.imgur.com/JZIg5J3.jpg" alt="資管104"></div>
                        <div id="203" class="col s12"><img class="materialboxed" src="https://i.imgur.com/9ZaWbTu.jpg" alt="資管203"></div>
                        <div id="205" class="col s12"><img class="materialboxed" src="https://i.imgur.com/zYYstJN.jpg" alt="資管205"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn waves-effect waves-light grey darken-4" id="agree-course">好，我會注意</a>
                <a class="btn waves-effect waves-light grey darken-4" id="agree-course-alert" style="display:none;">好，我會注意</a>
            </div>
        </div>

        <div id="about-modal" class="modal modal-fixed-footer">
            <div class="modal-content">
                <h4>About</h4>
                <div>
                    <div>Update at 2018/10/12</div>
                    <ul>
                        <li>1. 手機版體驗更加</li>
                        <li>2. 現在教師及職員也能預約了</li>
                        <li>3. 無須登入也能預覽預約狀況</li>
                        <li>4. 修復一些了蠕蟲</li>
                    </ul>
                    如有遇到任何錯誤或疑問，可至系辦或 E-mail 通報，感謝您<br />
                    E-mail: xeaiow@gmail.com<br />
                    關於我: <a target="_blank" href="https://www.cakeresume.com/xeee">https://www.cakeresume.com/xeee</a>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn waves-effect waves-light grey darken-4" id="okay">好</a>
            </div>
        </div>

        <div id="myRental" class="modal bottom-sheet">
            <div class="modal-content">
                <h4>我的預約<a class="modal-close waves-effect waves-green btn-flat">✕</a></h4>
                <div>
                    <ul class="collection with-header" id="myRentalRecord"></ul>
                </div>
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
        
    </div>

    <ul class="collection with-header" id="mobile-rental"></ul>

</div>
@endsection