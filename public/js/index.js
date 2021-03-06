var calendar = document.getElementById("calendar-table");
var gridTable = document.getElementById("table-body");
var currentDate = new Date();
var selectedDate = currentDate;
var selectedDayBlock = null;
var globalEventObj = {};

var sidebar = document.getElementById("sidebar");

let ws = new WebSocket('ws://localhost:3000')

ws.onopen = () => {
    console.log('open connection')
}

ws.onclose = () => {
    
}

// 接收 Server 發送的訊息
ws.onmessage = event => {
    M.toast({html: event.data});
}


$(function() {

    selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), new Date().getDate());

    axios.get('/get/rental/' + selectedDate.getTime() / 1000)
        .then(function(res) {

            if (res.data.length == 0) {
                $("#sidebarEvents").html('<div class="eventCard"><div class="eventCard-header">立即成為本日第一位預約的人</div></div>');
                
                $("#mobile-rental").html('<li class="collection-header"><h4 class="align center">尚無租借紀錄</h4></li>');
                
                return false;
            }

            let period = ['32400', '34200', '36000', '37800', '39600', '41400', '43200', '45000', '46800', '48600', '50400', '52200', '54000', '55800', '57600', '59400', '61200', '63000', '64800', '66600', '68400', '70200', '72000', '73800', '75600', '77400'];
            let str_period = ['9:00', '9:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30'];

            $.each(res.data, function (key, val) {
                let start = val.period.substr(0, 5);
                let end = val.period.substr(-5);
                
                $.each(period, function (key, val) {
                    if (start == val) {
                        start = str_period[key];
                    }
                    if (end == val) {
                        end = str_period[key];
                    }
                });
                addEvent(val.title, " 資管 " + val.room + " 從 " + start + " - " + end);
            });

        showEvents();
    });
    
    if (sessionStorage.getItem("cyimRentToken") == undefined || sessionStorage.getItem("cyimRentToken") == null) {
        return false;
    }
    loadEvents();
    
});

function createCalendar(date, side) {
    var currentDate = date;

    var startDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);

    var monthTitle = document.getElementById("month-name");
    var monthName = currentDate.toLocaleString("zh-Hans-TW", {
        month: "long"
    });
    var yearNum = currentDate.toLocaleString("zh-Hans-TW", {
        year: "numeric"
    });
    monthTitle.innerHTML = `${monthName} ${yearNum}`;

    if (side == "left") {
        gridTable.className = "animated fadeOutRight";
    } else {
        gridTable.className = "animated fadeOutLeft";
    }

    gridTable.innerHTML = "";

    var newTr = document.createElement("div");
    newTr.className = "row";
    var currentTr = gridTable.appendChild(newTr);

    for (let i = 1; i < startDate.getDay(); i++) {
        let emptyDivCol = document.createElement("div");
        emptyDivCol.className = "col empty-day";
        currentTr.appendChild(emptyDivCol);
    }

    var lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
    lastDay = lastDay.getDate();

    for (let i = 1; i <= lastDay; i++) {
        if (currentTr.getElementsByTagName("div").length >= 7) {
            currentTr = gridTable.appendChild(addNewRow());
        }
        let currentDay = document.createElement("div");
        currentDay.className = "col";
        if (selectedDayBlock == null && i == currentDate.getDate() || selectedDate.toDateString() == new Date(currentDate.getFullYear(), currentDate.getMonth(), i).toDateString()) {
            selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), i);

            document.getElementById("eventDayName").innerHTML = selectedDate.toLocaleString("zh-Hans-TW", {
                month: "long",
                day: "numeric",
                year: "numeric"
            });

            selectedDayBlock = currentDay;
            setTimeout(() => {
                currentDay.classList.add("focus");
            }, 900);
        }
        currentDay.innerHTML = i;
        currentTr.appendChild(currentDay);
    }

    for (let i = currentTr.getElementsByTagName("div").length; i < 7; i++) {
        let emptyDivCol = document.createElement("div");
        emptyDivCol.className = "col empty-day";
        currentTr.appendChild(emptyDivCol);
    }

    setTimeout(() => {
        if (side == "left") {
            gridTable.className = "animated fadeInLeft";
        } else {
            gridTable.className = "animated fadeInRight";
        }
    }, 270);

    function addNewRow() {
        let node = document.createElement("div");
        node.className = "row";
        return node;
    }
}

createCalendar(currentDate);

var todayDayName = document.getElementById("todayDayName");
todayDayName.innerHTML = "今天是" + currentDate.toLocaleString("zh-Hans-TW", {
    weekday: "long",
    day: "numeric",
    month: "short"
});

var prevButton = document.getElementById("prev");
var nextButton = document.getElementById("next");

prevButton.onclick = changeMonthPrev;
nextButton.onclick = changeMonthNext;

function changeMonthPrev() {
    currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1);
    createCalendar(currentDate, "left");
}

function changeMonthNext() {
    currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1);
    createCalendar(currentDate, "right");
}

function addEvent(title, desc) {

    if (!globalEventObj[selectedDate.toDateString()]) {
        globalEventObj[selectedDate.toDateString()] = {};
    }
    if (desc != null) {
        globalEventObj[selectedDate.toDateString()][title] = desc;
    } else {
        globalEventObj[selectedDate.toDateString()][title] = "";
    }
}

function showEvents() {
    let sidebarEvents = document.getElementById("sidebarEvents");
    let objWithDate = globalEventObj[selectedDate.toDateString()];

    sidebarEvents.innerHTML = "";

    if (objWithDate) {
        let eventsCount = 0;
        for (key in globalEventObj[selectedDate.toDateString()]) {
            let eventContainer = document.createElement("div");
            let eventHeader = document.createElement("div");
            eventHeader.className = "eventCard-header";

            let eventDescription = document.createElement("div");
            eventDescription.className = "eventCard-description";

            eventHeader.appendChild(document.createTextNode(key));
            eventContainer.appendChild(eventHeader);

            eventDescription.appendChild(document.createTextNode(objWithDate[key]));
            eventContainer.appendChild(eventDescription);

            let markWrapper = document.createElement("div");
            markWrapper.className = "eventCard-mark-wrapper";
            let mark = document.createElement("div");
            mark.classList = "eventCard-mark";
            markWrapper.appendChild(mark);
            eventContainer.appendChild(markWrapper);

            eventContainer.className = "eventCard";

            sidebarEvents.appendChild(eventContainer);

            eventsCount++;
        }
    } else {
        let emptyMessage = document.createElement("div");
        emptyMessage.className = "empty-message";
        sidebarEvents.appendChild(emptyMessage);
        let emptyFormMessage = document.getElementById("emptyFormTitle");

    }
    $("#sidebarEvents").prepend(
        '<div class="row" id="recordTitle">' + 
            '<div class="col s12">' + 
                '<h6 class="center-align" id="todayRental">本日租借紀錄</h6>' + 
                ''+
            '</div>' + 
        '</div>');
}

gridTable.onclick = function(e) {

    if (!e.target.classList.contains("col") || e.target.classList.contains("empty-day")) {
        return;
    }

    if (selectedDayBlock) {
        if (selectedDayBlock.classList.contains("focus")) {

            selectedDayBlock.classList.remove("focus");
        }
    }
    selectedDayBlock = e.target;

    selectedDayBlock.classList.add("focus");

    selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), parseInt(e.target.innerHTML));

    if (selectedDate.getDay() == 0 || selectedDate.getDay() == 6) {
        swal("假日不開放", "假日借用請洽系辦公室", "error", {
            buttons: "知道了",
        });
        return false;
    }
        
    axios.get('/get/rental/' + selectedDate.getTime() / 1000)
        .then(function(res) {

            $("#mobile-rental").html('');

            if (res.data.length == 0) {
                $("#sidebarEvents").html('<div class="eventCard"><div class="eventCard-header">立即成為本日第一位預約的人</div></div>');
            
                $("#mobile-rental").html('<li class="collection-header align center"><h4>尚無租借紀錄</h4></li>');

                return false;
            }

            let period = ['32400', '34200', '36000', '37800', '39600', '41400', '43200', '45000', '46800', '48600', '50400', '52200', '54000', '55800', '57600', '59400', '61200', '63000', '64800', '66600', '68400', '70200', '72000', '73800', '75600', '77400'];
            let str_period = ['9:00', '9:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30'];

            $.each(res.data, function (key, val) {
                let start = val.period.substr(0, 5);
                let end = val.period.substr(-5);
                
                $.each(period, function (key, val) {
                    if (start == val) {
                        start = str_period[key];
                    }
                    if (end == val) {
                        end = str_period[key];
                    }
                });
                addEvent(val.title, " 資管 " + val.room + " 從 " + start + " - " + end);
                $("#mobile-rental").append('<li class="collection-item">' + val.title + ' 在資管 ' + val.room + ' 從 ' + start + ' 到 ' + end + '</li>');
            });

        showEvents();
    });

    document.getElementById("eventDayName").innerHTML = selectedDate.toLocaleString("zh-Hans-TW", {
        month: "long",
        day: "numeric",
        year: "numeric"
    });

    // if (sessionStorage.getItem("cyimRentToken") != null) {
    //     $("#modal1").modal('open');
    // }
}

$(".booking").on('click', function() {
    
    new Audio('/audio/click.mp3').play();

    if (sessionStorage.getItem("cyimRentToken") == null) {
        $("#loginModal").modal('open');
        $("#itouchUsername").focus();
    } else {
        $("#modal1").modal('open');
    }
});

$("#agree").click(function () {
    $("#terms").modal("close");
    $("#course").modal('open');
});

$("#agree-course").click(function () {
    $("#course").modal("close");
    $("#modal1").modal('open');
});

$(".open-course").click(function () {
    $("#course").modal("open");
    $("#agree-course").hide();
    $("#agree-course-alert").show();
});

$("#agree-course-alert").click(function () {
    $("#course").modal("close");
});

$(".nav-about").click(function () {
    $("#about-modal").modal("open");
});

$("#okay").click(function () {
    $("#about-modal").modal("close");
});

$(".open-broadcast").click(function () {
    $("#broadcast").modal("open");
});

$("#exit-broadcast").click(function () {
    $("#broadcast").modal("close");
});


$('#broadcast-content').keydown(function (event) {
    var keypressed = event.keyCode || event.which;
    if (keypressed == 13 && sessionStorage.getItem('cyimRentToken') != undefined) {
        ws.send(sessionStorage.getItem('cyimRentName') + "：" + $("#broadcast-content").val());
        $("#broadcast-content").val('');
    }
});

$("#send-broadcast").click(function () {

    if (sessionStorage.getItem('cyimRentToken') == undefined) {
        return false;
    }
    ws.send(sessionStorage.getItem('name') + "：" + $("#broadcast-content").val());
    $("#broadcast-content").val('');
});

// 選擇時段
var selectCount = 0;
var selectedTime = new Array();
var seconds = new Array();
var rentalRes = null;
var rentalRenter = null;
var rentaOriginal = null;
var round = 0;
var selectRoom = null;

$(".selectTime").click(function() {

    if ($(this).prop('checked')) {

        let timestamp = $(this).attr("id");
        let breakSelect = true;

        if (selectCount == 0) {

            let self = parseInt(timestamp.substr(1).slice(0, -1)) + 1800;
            let pre = parseInt(timestamp.substr(1).slice(0, -1)) - 1800;

            if ($.inArray(timestamp.substr(1).slice(0, -1), rentaOriginal) >= 0 && $.inArray(self.toString(), rentaOriginal) >= 0 && !$("#t" + pre + "t").prop("disabled")) {

                swal("糟糕惹", "不能在這個時間點作為開始", "error", {
                    buttons: "知道了",
                });

                reset();
                breakSelect = false;
                return false;
            }
            

            if ($.inArray(timestamp.substr(1).slice(0, -1), rentaOriginal) == -1 && $.inArray(self.toString(), rentaOriginal) >= 0) {
                $(".selectTime").prop("disabled", true);
                $("#t" + timestamp.substr(1).slice(0, -1) + "t").prop("disabled", false);
                $("#t" + self + "t").prop("disabled", false);
            }

        } else {
            for (let i = parseInt(seconds[0]) + 1800; i < parseInt(timestamp.substr(1).slice(0, -1)); i += 1800) {

                if ($.inArray(i.toString(), rentaOriginal) >= 0) {
                    swal("糟糕惹", "不能橫跨他人租借時間，下次請早", "error", {
                        buttons: "知道了",
                    });
                    reset();
                    breakSelect = false;
                    return false;
                }
            }
        }

        $.each(rentalRes, function(key, val) {

            let self = parseInt(timestamp.substr(1).slice(0, -1)) + 1800;

            if (timestamp.substr(1).slice(0, -1) < val) {
                round = ((val - 1800) - (timestamp.substr(1).slice(0, -1))) / 1800;
                return false;
            } else {
                round = (77400 - parseInt(timestamp.substr(1).slice(0, -1))) / 1800;
            }

            $(".selectTime").prop("disabled", true);
        });

        if (breakSelect) {

            for (let i = 32400; i < parseInt(timestamp.substr(1).slice(0, -1)); i += 1800) {
                if (i != parseInt(timestamp.substr(1).slice(0, -1))) {
                    $("#t" + i + "t").prop("disabled", true);
                }

            }

            let timestampSecode = parseInt(timestamp.substr(1).slice(0, -1));

            if ((selectCount == 0 && round == 0 && rentalRes.length != 0) || (selectCount == 0 && round == 0 && timestampSecode >= 77400)) {

                timePointError();

                return false;
            }

            if (round != 0) {
                for (var i = 0; i <= round; i++) {

                    $("#t" + timestampSecode + "t").prop("disabled", false);

                    timestampSecode += 1800;
                }

                for (let j = 0; j < rentalRenter.length; j++) {
                    $("#t" + rentalRenter[j][rentalRenter[j].length - 1] + "t").prop("disabled", true);
                }

            }

            let self = parseInt(timestamp.substr(1).slice(0, -1)) + 1800;
            let pre = parseInt(timestamp.substr(1).slice(0, -1)) - 1800;

            if (($("#t" + pre + "t").prop("disabled") || $("#t" + self + "t").prop("disabled")) && $.inArray(self.toString(), rentaOriginal) >= 0) {
                $(".selectTime").prop("disabled", true);
                $("#t" + self + "t").prop("disabled", false);
                $("#t" + timestamp.substr(1).slice(0, -1) + "t").prop("disabled", false);

            }

            selectCount++;

            selectedTime[selectCount - 1] = $(this).siblings('span')[0].textContent;

            let timeFormatStart = $(this).siblings('span')[0].textContent.split(':');
            seconds.push((+timeFormatStart[0]) * 60 * 60 + (+timeFormatStart[1]) * 60);

            $("#clearTime").removeClass("disabled");

            if (selectCount == 2) {
                // exchange
                if (seconds[0] > seconds[1]) {
                    let temp = seconds[1];
                    seconds[0] = seconds[1];
                    seconds[1] = temp;
                }

                if ( (parseInt(seconds[1]) - parseInt(seconds[0])) > 10800 ) {
                    swal("糟糕惹", "最多只能借用三小時唷", "error", {
                        buttons: "知道了",
                    });
                    reset();
                    return false;
                }

                $(".selectTime").not(':checked').prop("disabled", true);
                $("#rent").removeClass("disabled");
                $(".previewTime").html(timeConvert(seconds[0]).h + ":" + (timeConvert(seconds[0]).m == 0 ? "00" : "30") + " 到 " + timeConvert(seconds[1]).h + ":" + (timeConvert(seconds[1]).m == 0 ? "00" : "30"));
                $(".selectTime").prop("disabled", true);
            }
        }
    } else {

        selectCount--;
        selectedTime.splice(selectedTime.indexOf($(this).siblings('span')[0].textContent), 1);

        let timeFormatStart = $(this).siblings('span')[0].textContent.split(':');

        seconds.splice(seconds.indexOf((+timeFormatStart[0]) * 60 * 60 + (+timeFormatStart[1]) * 60), 1);

        $("#confirmTime").addClass("disabled");

        reset();
    }
});


// 清除選擇時段
$("#clearTime").on('click', function() {
    reset();
});

// 跳出確定租借視窗
$("#rent").click(function() {
    $('#modal2').modal('open');
    $("#eventTitleInput").focus();
    $('#modal1').modal('close');

    $(".previewTime").prepend(selectedDate.toLocaleString("zh-Hans-TW", {
        month: "long",
        day: "numeric",
        year: "numeric"
    }) + " ");
});

// 送出租借請求
$("#confirmRent").click(function() {

    let title = document.getElementById("eventTitleInput").value.trim();
    let phone = document.getElementById("eventPhoneInput").value.trim();
    let desc = document.getElementById("eventDescInput").value.trim();

    if (!title || !phone || !selectRoom || seconds.length < 2 || sessionStorage.getItem("cyimRentToken") == undefined) {
        swal("抓到了", "有些欄位填錯或沒填", "error", {
            buttons: "好",
        });
        return false;
    }

    if (title.length > 15 || desc.length > 100 || phone.length > 15) {
        swal("在幹嘛", "寫作文阿？描述一下就好了", "error", {
            buttons: "知道了",
        });
        return false;
    }

    let today = new Date();
    let now = new Date(today.getFullYear(), (today.getMonth()), today.getDate());

    if (parseInt(selectedDate.getTime()) >= parseInt(now.getTime())) {

        let itemDate = moment.unix(selectedDate.getTime() / 1000).format("YYYY-MM-DD");
        let current = today.toISOString().substring(0, 10);
        let timeText = null;

        if (itemDate > current) {
            moment.duration(moment(itemDate).diff(current)).distance();
            timeText = moment.duration(moment(itemDate).diff(current)).distance();

        } else {
            moment.duration(moment(current).diff(itemDate)).distance();
            timeText = moment.duration(moment(current).diff(itemDate)).distance();
        }
        // addEvent(title + " - " + timeText + "在資管" + selectRoom, desc);
        
    } else {
        swal("不給預約", "過去就讓它過吧，難道你是時空旅行者？", "error", {
            buttons: "不是",
        });
        $("#eventTitleInput, #eventPhoneInput, #eventDescInput").val('');
        $("#modal2").modal("close");
        $(".previewTime").html('');
        return false;
    }

    axios.post('/set/rental', {
            title: title,
            phone: phone,
            description: desc,
            room: selectRoom,
            username: sessionStorage.getItem("cyimRentUsername"),
            name: sessionStorage.getItem("cyimRentName"),
            rentDate: selectedDate.getTime() / 1000,
            period: JSON.stringify(seconds),
            token: sessionStorage.getItem("cyimRentToken")
        })
        .then(function(res) {

            if (!res.data.status) {
                swal("糟糕惹", "token 已失效，請重新登入", "error", {
                    buttons: "好",
                });
                $("#loginModal").modal("open");
                $("#navbar").hide();
                return false;
            }

            if (res.data.exists) {

                switch (res.data.code) {
                    case 1:
                        swal("母湯哦", "所選的時間與其他已預約教室衝突", "error", {
                            buttons: "好，我來看看",
                        });
                        break;
                    case 2:
                        swal("母湯哦", "同間教室當日只能借一個時段", "error", {
                            buttons: "好，多交點朋友",
                        });
                    case 3:
                        swal("母湯哦", "該時段已被他人預約", "error", {
                            buttons: "好，橋好再來",
                        });
                        break;
                    default:
                        break;
                }
                return false;
            }

            if (res.data.error) {
                swal("糟糕惹", "似乎有什麼意外，請再試一次", "error", {
                    buttons: "好",
                });
                return false;
            }

            // 增加紀錄
            showEvents();

            if (!selectedDayBlock.querySelector(".day-mark")) {
                selectedDayBlock.appendChild(document.createElement("div")).className = "day-mark";
            }

            // add to my rental
            let itemDate = moment.unix(selectedDate.getTime() / 1000).format("YYYY-MM-DD");
            let current = moment(new Date()).format('YYYY-MM-DD');
            let timeText = null;

            if (itemDate > current) {
                moment.duration(moment(itemDate).diff(current)).distance();
                timeText = moment.duration(moment(itemDate).diff(current)).distance();

            } else {
                moment.duration(moment(current).diff(itemDate)).distance();
                timeText = moment.duration(moment(current).diff(itemDate)).distance();
            }
            
            $("#myRentalRecord").append(
                '<li class="collection-item avatar" id="rentalRecord_' + res.data.id + '"><img id="rental-' + res.data.id + '" src="https://i.imgur.com/xIER1kc.png" alt="" class="circle rent-cancel"><span class="title my-rental-title">' + title + '</span><p>' + timeText + '在資管 ' + selectRoom + '</p></li>'
            );

            $(".empty-message").remove();

            $(".sidebar-events").append(
                '<div class="eventCard"><div class="eventCard-header">' + title + '</div><div class="eventCard-description">' + timeText + '在資管 ' + selectRoom + '</div><div class="eventCard-mark-wrapper"><div class="eventCard-mark"></div></div></div>'
            );

            swal("預約完成", "請在預約時間點確實使用教室", "success", {
                buttons: "好",
            });

            $(".modal").modal('close');
            $(".previewTime").html('');
            $("#eventTitleInput, #eventPhoneInput, #eventDescInput").val('');
        });
});

// 關閉視窗清除資料
$(".modal-close").click(function() {
    clearSelect();
    reset();
    $("#selectTimeTable").hide();
});


$("#room").on('change', function() {

    $(".badge").remove();
    clearSelect();

    $("select").formSelect();
    let instance = M.FormSelect.getInstance($(this));
    selectRoom = instance.getSelectedValues()[0];

    axios.get('/get/rental/' + selectedDate.getTime() / 1000 + '/' + instance.getSelectedValues())
        .then(function(response) {

            $("#instruction").html('選擇開始與結束時間 <span style="color:#ad1457;" class="previewTime"></span>');
            rentalRes = response.data.period;
            rentaOriginal = response.data.original;
            rentalRenter = response.data.renter;

            $("#selectTimeTable").show();

            for (var i = 0; i < rentalRes.length; i++) {
                if ($("#t" + rentalRes[i] + "t")[0].id == "t" + rentalRes[i] + "t") {
                    $("#t" + rentalRes[i] + "t").prop("disabled", true);
                }
            }

            if ($.inArray("32400", rentaOriginal) >= 0) {
                $("#t32400t").prop("disabled", true);
            }

            if ($.inArray("77400", rentaOriginal) >= 0) {
                $("#t77400t").prop("disabled", true);
            }

            let index = 0;
            for (let j = 0; j < rentalRenter.length; j++) {
                let color = randColor();
                for (let k = 0; k < rentalRenter[j].length; k++) {
                    $("#t" + rentalRenter[j][k] + "t").parents().eq(1).siblings("td")[0].innerHTML += ' <span class="new badge rentUserLabel" style="background-color:' + color + ';" data-badge-caption="' + response.data.user[index] + '"></span> ';
                    index++;
                }
                $("#t" + rentalRenter[j][0] + "t").prop("disabled", true);
            }
        });
});

var lockLogin = false;

$("#login").click(function() {

    if ($("#itouchUsername").val() == "" || $("#itouchUsername").val() == "") {
        return false;
    }

    lockLogin = true;
    $("#login").attr('disabled', true);

    axios.post('/login', {
            username: $("#itouchUsername").val(),
            password: $("#itouchPassword").val()
        })
        .then(function(res) {

            if (res.data.status != 1) {
                swal("糟糕惹", "怎麼連愛觸摸帳密都忘記 ヽ(#`Д´)ﾉ", "error", {
                    buttons: "對不起",
                });
                $("#login").attr('disabled', false);
                return false;
            }


            new Audio('/audio/online.mp3').play();

            // 10 分鐘自動登出
            setTimeout(function () {
                sessionStorage.removeItem("cyimRentToken");
                if (sessionStorage.getItem("cyimRentToken") == undefined || sessionStorage.getItem("cyimRentToken") == null) {
                    ws.send(sessionStorage.getItem("cyimRentName") + "被踢下線了");
                    ws.close();
                    $("#navbar").hide();
                    $("#myRentalRecord").html('');
                    $("#sidebarEvents").html('');
                    swal("閒置自動登出", "謝謝使用", "info", {
                        buttons: "謝謝系統",
                    });
                }
            }, 600000);

            lockLogin = false;
            $("#loginModal").modal('close');
            $("#navbar").show();
            $(".my").attr('data-tooltip', res.data.name);
            $("#itouchUsername").val('');
            $("#itouchPassword").val('');
            sessionStorage.setItem("cyimRentToken", res.data.token);
            sessionStorage.setItem("cyimRentUsername", res.data.username);
            sessionStorage.setItem("cyimRentName", res.data.name);
            ws.send(res.data.name + '上線啦！');
            $("#login").attr('disabled', false);
            $("#terms").modal('open');
        });
});

$(".logout").click(function() {
    sessionStorage.removeItem("cyimRentToken");
    if (sessionStorage.getItem("cyimRentToken") == undefined || sessionStorage.getItem("cyimRentToken") == null) {
        ws.send(sessionStorage.getItem("cyimRentName") + "下線了");
        ws.close();
        $("#navbar").hide();
        $("#myRentalRecord").html('');
        $("#sidebarEvents").html('');
        swal("登出成功", "謝謝使用", "info", {
            buttons: "好哦",
        });
    }
});


$("#doNotAgree").click(function() {
    $("#terms").modal("close");
    sessionStorage.removeItem("cyimRentToken");
    ws.send(sessionStorage.getItem("cyimRentName") + "下線了");
    ws.close();
    $("#navbar").hide();
    $("#sidebarEvents").html('');
    return false;
});

$("#itouchUsername, #itouchPassword").keypress(function(e) {
    code = (e.keyCode ? e.keyCode : e.which);

    if (code == 13 && !lockLogin) {
        $("#login").click();
    }
});

$(".my").click(function () { 
    $("#myRental").modal("open");
});

function timeConvert(secs) {
    var hours = Math.floor(secs / (60 * 60));

    var divisor_for_minutes = secs % (60 * 60);
    var minutes = Math.floor(divisor_for_minutes / 60);

    var obj = {
        "h": hours,
        "m": minutes,
    };
    return obj;
}

function clearSelect() {
    $(".selectTime").prop("disabled", false).prop("checked", false);
    $("#rent").addClass("disabled");
    selectCount = 0;
    $(".previewTime").html("");
    selectedTime.length = 0;
    seconds.length = 0;
}

function reset() {

    $(".selectTime").prop("disabled", false).prop("checked", false);
    $("#rent").addClass("disabled");
    $("#clearTime").addClass("disabled");
    $(".previewTime").html("");
    selectCount = 0;
    selectedTime.length = 0;
    seconds.length = 0;

    if ($.inArray("32400", rentaOriginal) >= 0) {
        $("#t32400t").prop("disabled", true);
    }

    if ($.inArray("77400", rentaOriginal) >= 0) {
        $("#t77400t").prop("disabled", true);
    }

    if (rentalRenter != null) {
        for (let j = 0; j < rentalRenter.length; j++) {
            $("#t" + rentalRenter[j][0] + "t").prop("disabled", true);
        }
    }

    if (rentalRes != null) {
        for (var i = 0; i < rentalRes.length; i++) {
            if ($("#t" + rentalRes[i] + "t")[0].id == "t" + rentalRes[i] + "t") {
                $("#t" + rentalRes[i] + "t").prop("disabled", true);
            }
        }
    }
}

function randColor() {
    return "#" + ((1 << 24) * Math.random() | 0).toString(16);
}

function timePointError() {

    swal("糟糕惹", "不能在這個時間點作為開始", "error", {
        buttons: "知道了",
    });
    reset();
}

function loadEvents() {

    axios.get('/get/user/rental/' + sessionStorage.getItem("cyimRentToken"))
        .then(function(res) {

            if (res.data.status) {

                let rentalRes = res.data.rent;

                // set username and name of storage
                if (res.data.login.length != 0) {
                    $("#my").attr('data-tooltip', "租借紀錄 (Alt+R)");
                    $("#navbar").show();
                    sessionStorage.setItem("username", res.data.login.username);
                    sessionStorage.setItem("name", res.data.login.name);
                }

                if (rentalRes.length > 0) {

                    if (!selectedDayBlock.querySelector(".day-mark")) {
                        selectedDayBlock.appendChild(document.createElement("div")).className = "day-mark";
                    }
                    globalEventObj = {};
                    for (var i = 0; i < rentalRes.length; i++) {

                        let itemDate = moment.unix(rentalRes[i].rentDate).format("YYYY-MM-DD");
                        let current = moment(currentDate).format('YYYY-MM-DD');
                        let timeText = null;

                        if (itemDate > current) {
                            moment.duration(moment(itemDate).diff(current)).distance();
                            timeText = moment.duration(moment(itemDate).diff(current)).distance();

                        } else {
                            moment.duration(moment(current).diff(itemDate)).distance();
                            timeText = moment.duration(moment(current).diff(itemDate)).distance();
                        }
                        
                        $("#myRentalRecord").append(
                            '<li class="collection-item avatar" id="rentalRecord_' + rentalRes[i].id + '"><img id="rental-' + rentalRes[i].id + '" src="https://i.imgur.com/xIER1kc.png" class="circle rent-cancel"><span class="title my-rental-title">' + rentalRes[i].title + '</span><p>' + timeText + '在資管 ' + rentalRes[i].room + '</p></li>'
                        );
                    }
                }
            }
        });
}

$(document).on("click",".rent-cancel",function(){ 

    swal({
        title: "要取消預約?",
        text: "取消後視同於刪除這筆預約紀錄",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        buttons: ["考慮", "給我取消！"],
    })
    .then((willDelete) => {
        if (willDelete) {

            let focus_id = $(this).attr('id').substr(7);

            axios.post('/set/remove/user/rental', {
                token: sessionStorage.getItem('cyimRentToken'),
                id: focus_id
            })
            .then(function(response) {
                
                if (response.data !== 0) {
                    $("#rentalRecord_" + focus_id).remove();
                    swal(
                        '已取消預約',
                        '你失去了一個空間',
                        'success'
                    );
                    return;
                }
                swal(
                    '取消預約失敗',
                    '重來試試看',
                    'error'
                );
            });
        }
    });   
});

$(".country").click(function () {
    let country = $(this).attr('alt')
    switch (country) {
        case "cn":
            text = "给我双击666";
            $(".translate")[0].innerText = "资管系教室预约系统";
            $(".translate")[1].innerText = "预约";
            $(".translate")[1].innerText = "选择借用教室";
            break;
        case "us":
            text = "Error service.";
            break;
        case "jp":
            text = "やばい！";
            break;
        case "kr":
            text = "큰일났어";
            break;
            default:
            break;
    }
    return swal(
        text,
        '',
        'info'
    );
});