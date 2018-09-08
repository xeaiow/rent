var calendar = document.getElementById("calendar-table");
var gridTable = document.getElementById("table-body");
var currentDate = new Date();
var selectedDate = currentDate;
var selectedDayBlock = null;
var globalEventObj = {};

var sidebar = document.getElementById("sidebar");

$(function() {

    if (localStorage.getItem("cyimRentToken") == undefined) {
        return false;
    }

    axios.get('/rent/public/get/user/rental/' + localStorage.getItem("cyimRentToken"))
    .then(function (res) {

        if (res.data.status) {
            let rentalRes = res.data.rent;

            // set username and name of storage
            if (res.data.login != undefined) {
                $("#navbar").show();
                localStorage.setItem("username", res.data.login.username);
                localStorage.setItem("name", res.data.login.name);
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
                        
                    }
                    else {
                        moment.duration(moment(current).diff(itemDate)).distance();
                        timeText = moment.duration(moment(current).diff(itemDate)).distance();
                    }
                    
                    addEvent(rentalRes[i].title + " - " + timeText + "在資管" + rentalRes[i].room, rentalRes[i].description);
                }
                showEvents();
            }
        }
    });
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
    }
    else {
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
        let emptyFormMessage = document.getElementById("emptyFormTitle");

    } else {
        let emptyMessage = document.createElement("div");
        emptyMessage.className = "empty-message";
        // emptyMessage.innerHTML = "尚無租借紀錄";
        sidebarEvents.appendChild(emptyMessage);
        let emptyFormMessage = document.getElementById("emptyFormTitle");

    }
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

    showEvents();

    document.getElementById("eventDayName").innerHTML = selectedDate.toLocaleString("zh-Hans-TW", {
        month: "long",
        day: "numeric",
        year: "numeric"
    });

    if (localStorage.getItem("cyimRentToken") == null) {
        $("#loginModal").modal('open');
        $("#itouchUsername").focus();
    }
    else {
        $("#modal1").modal('open');
    }
}

// 選擇時段
var selectCount = 0;
var selectedTime = new Array();
var seconds = new Array();
var rentalRes = null;
var round = 0;
var selectRoom = null;

$(".selectTime").click(function() {

    if ($(this).prop('checked')) {
        
        let timestamp = $(this).attr("id");
        let breakSelect = true;

        $.each(rentalRes, function (key, val) {

            $(".selectTime").prop("disabled", true);

            let self = parseInt(timestamp.substr(1).slice(0, -1)) + 1800;

            if ( $("#t" + self + "t").prop("disabled") == true && timestamp.substr(1).slice(0, -1) == "28800" ) {
                swal("糟糕惹", "不能在這個時間點作為開始", "error", {
                    buttons: "知道了",
                });
                reset();
                breakSelect = false;
                return false;
            }
            
            if (timestamp.substr(1).slice(0, -1) < val) {
                round = ((val-1800) - (timestamp.substr(1).slice(0, -1))) / 1800;
                return false;
            }
            else {
                round = (77400 - parseInt(timestamp.substr(1).slice(0, -1))) / 1800;
            }
            
        });

        if (breakSelect) {

            let timestampSecode = parseInt(timestamp.substr(1).slice(0, -1));

            if ( (selectCount == 0 && round == 0 && rentalRes.length != 0 ) || ( selectCount == 0 && round == 0 && timestampSecode >= 77400 ) ) {

                timePointError();
                return false;
            }

            if (round != 0) {
                for (var i = 0; i <= round; i++) {
                        
                    $("#t" + timestampSecode + "t").prop("disabled", false);

                    timestampSecode += 1800;
                }
            }
            selectCount++;
            

            selectedTime[selectCount - 1] = $(this).siblings('span')[0].textContent;

            let timeFormatStart = $(this).siblings('span')[0].textContent.split(':');
            seconds.push((+timeFormatStart[0]) * 60 * 60 + (+timeFormatStart[1]) * 60);

            $("#clearTime").removeClass("disabled");

            if (selectCount == 2) {
                $(".selectTime").not(':checked').prop("disabled", true);
                $("#rent").removeClass("disabled");
                if (seconds[0] > seconds[1]) {
                    $(".previewTime").html(timeConvert(seconds[1]).h + ":" + (timeConvert(seconds[1]).m == 0 ? "00" : "30") + " 到 " + timeConvert(seconds[0]).h + ":" + (timeConvert(seconds[0]).m == 0 ? "00" : "30"));
                } else {
                    $(".previewTime").html(timeConvert(seconds[0]).h + ":" + (timeConvert(seconds[0]).m == 0 ? "00" : "30") + " 到 " + timeConvert(seconds[1]).h + ":" + (timeConvert(seconds[1]).m == 0 ? "00" : "30"));
                }
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
    let desc = document.getElementById("eventDescInput").value.trim();

    // // 如果原因欄位是空的
    // if (!title) {
    //     document.getElementById("eventTitleInput").value = "";
    //     document.getElementById("eventDescInput").value = "";
    //     let labels = addForm.getElementsByTagName("label");
    //     for (let i = 0; i < labels.length; i++) {
    //         labels[i].className = "";
    //     }
    //     return;
    // }

    if (!title || !selectRoom || seconds.length < 2 || localStorage.getItem("cyimRentToken") == undefined) {
        swal("糟糕惹", "應該是有些欄位填錯了", "error", {
            buttons: "好",
        });
        return false;
    }

    if ( parseInt(selectedDate.getTime())+86399000 >= moment(currentDate) ) {

        let itemDate = moment.unix(selectedDate.getTime() / 1000).format("YYYY-MM-DD");
        let current = moment(currentDate).format('YYYY-MM-DD');
        let timeText = null;

        if (itemDate > current) {
            moment.duration(moment(itemDate).diff(current)).distance();
            timeText = moment.duration(moment(itemDate).diff(current)).distance();
            
        }
        else {
            moment.duration(moment(current).diff(itemDate)).distance();
            timeText = moment.duration(moment(current).diff(itemDate)).distance();
        }

        addEvent(title + " - " + timeText + "在資管" + selectRoom, desc);
    }
    else {
        swal("不給預約", "過去就讓它過吧，難道你是時空旅行者？", "error", {
            buttons: "不是",
        });
        $("#eventTitleInput").val('');
        $("#modal2").modal("close");
        $(".previewTime").html('');
        return false;
    }

    // 增加紀錄
    showEvents();

    if (!selectedDayBlock.querySelector(".day-mark")) {
        selectedDayBlock.appendChild(document.createElement("div")).className = "day-mark";
    }

    axios.post('/rent/public/set/rental', {
        title: title,
        description: desc,
        room: selectRoom,
        username: localStorage.getItem("cyimRentUsername"), 
        name: localStorage.getItem("cyimRentName"),
        rentDate: selectedDate.getTime() / 1000,
        period: JSON.stringify(seconds),
        token: localStorage.getItem("cyimRentToken")
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

        swal("預約完成", "請在預約時間點確實使用教室", "success", {
            buttons: "好",
        });
        $(".modal").modal('close');
        $(".previewTime").html('');
        $("#eventTitleInput").val('');
        $("#eventDescInput").val('');
    })
    .catch(function(error) {
        console.log(error);
    });
});

// 關閉視窗清除資料
$(".modal-close").click(function() {
    clearSelect();
    reset();
    $("#selectTimeTable").hide();
});


$("#room").on('change',function(){

    $(".badge").remove();
    clearSelect();

    $("select").formSelect();
    let instance = M.FormSelect.getInstance($(this));
    selectRoom = instance.getSelectedValues()[0];

    axios.get('/rent/public/get/rental/' + selectedDate.getTime() / 1000 + '/' + instance.getSelectedValues())
    .then(function (response) {

        rentalRes = response.data.period;

        $("#selectTimeTable").show();
        
        for (var i = 0; i < rentalRes.length; i++) {
            if ($("#t"+rentalRes[i] + "t")[0].id == "t" + rentalRes[i] + "t") {
                $("#t"+rentalRes[i] + "t").prop("disabled", true);
            }
        }
        
        let res = response.data.renter;
        let index = 0;
        for (let j = 0; j < res.length; j++) {
            let color = randColor();
            for (let k = 0; k < res[j].length; k++) {
                $("#t"+ res[j][k] + "t").siblings("span")[0].innerHTML +=  ' <span class="new badge" style="background-color:' + color + ';" data-badge-caption="' + response.data.user[index] + '"></span> ';
                index++;
            }        
        }
    });
});

$("#login").click(function () {
    
    $("#login").attr('disabled', true);
    axios.post('/rent/public/login', {
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
        
        $("#loginModal").modal('close');
        $("#modal1").modal('open');
        swal("登入成功", "可以開始預約教室了", "success", {
            buttons: "知道了",
        });
        $("#navbar").show();
        $("#itouchUsername").val('');
        $("#itouchPassword").val('');
        localStorage.setItem("cyimRentToken", res.data.token);
        localStorage.setItem("cyimRentUsername", res.data.username);
        localStorage.setItem("cyimRentName", res.data.name);
        $("#login").attr('disabled', false);
    });
});

$("#logout").click(function () {
    if (localStorage.removeItem("cyimRentToken") == undefined) {
        $("#navbar").hide();
        swal("登出成功", "謝謝使用", "info", {
            buttons: "好哦",
        });
    }
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

function reset () {
    
    $(".selectTime").prop("disabled", false).prop("checked", false);
    $("#rent").addClass("disabled");
    $("#clearTime").addClass("disabled");
    $(".previewTime").html("");
    selectCount = 0;
    selectedTime.length = 0;
    seconds.length = 0;

    if (rentalRes != null) {
        for (var i = 0; i < rentalRes.length; i++) {
            if ($("#t"+rentalRes[i] + "t")[0].id == "t" + rentalRes[i] + "t") {
                $("#t"+rentalRes[i] + "t").prop("disabled", true);
            }
        }
    }
}

function randColor() {
    return "#"+((1<<24)*Math.random()|0).toString(16);
}

function timePointError () {
    
    swal("糟糕惹", "不能在這個時間點作為開始", "error", {
        buttons: "知道了",
    });
    reset();
}