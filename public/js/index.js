var calendar = document.getElementById("calendar-table");
var gridTable = document.getElementById("table-body");
var currentDate = new Date();
var selectedDate = currentDate;
var selectedDayBlock = null;
var globalEventObj = {};

var sidebar = document.getElementById("sidebar");

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
                currentDay.classList.add("blue");
                currentDay.classList.add("lighten-3");
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
todayDayName.innerHTML = "今天是 " + currentDate.toLocaleString("zh-Hans-TW", {
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
    globalEventObj[selectedDate.toDateString()][title] = desc;
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
        emptyMessage.innerHTML = "請在右方選擇欲租借日期";
        sidebarEvents.appendChild(emptyMessage);
        let emptyFormMessage = document.getElementById("emptyFormTitle");

    }
}

gridTable.onclick = function(e) {

    if (!e.target.classList.contains("col") || e.target.classList.contains("empty-day")) {
        return;
    }

    if (selectedDayBlock) {
        if (selectedDayBlock.classList.contains("blue") && selectedDayBlock.classList.contains("lighten-3")) {
            selectedDayBlock.classList.remove("blue");
            selectedDayBlock.classList.remove("lighten-3");
        }
    }
    selectedDayBlock = e.target;
    selectedDayBlock.classList.add("blue");
    selectedDayBlock.classList.add("lighten-3");

    selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), parseInt(e.target.innerHTML));

    showEvents();

    document.getElementById("eventDayName").innerHTML = selectedDate.toLocaleString("zh-Hans-TW", {
        month: "long",
        day: "numeric",
        year: "numeric"
    });
    $("#modal1").modal('open');
}





$("#addEventButton").click(function () {
    let title = document.getElementById("eventTitleInput").value.trim();
    let desc = document.getElementById("eventDescInput").value.trim();

    // 如果原因欄位是空的
    if (!title) {
        document.getElementById("eventTitleInput").value = "";
        document.getElementById("eventDescInput").value = "";
        let labels = addForm.getElementsByTagName("label");
        for (let i = 0; i < labels.length; i++) {
            console.log(labels[i]);
            labels[i].className = "";
        }
        return;
    }
    
    axios.post('/rent/public/set/rental', {
        title: title,
        description: desc,
        room: '資管203',
        period: selectedDate.getTime() / 1000
        // period: selectedDate.toLocaleString("zh-Hans-TW", {month: "long", day: "numeric",year: "numeric"})
    })
    .then(function (response) {
        console.log(response);
    })
    .catch(function (error) {
        console.log(error);
    });

    // 增加紀錄
    addEvent(title, desc);
    showEvents();

    if (!selectedDayBlock.querySelector(".day-mark")) {
        console.log("work");
        selectedDayBlock.appendChild(document.createElement("div")).className = "day-mark";
    }

    let inputs = addForm.getElementsByTagName("input");
    for (let i = 0; i < inputs.length; i++) {
        inputs[i].value = "";
    }
    let labels = addForm.getElementsByTagName("label");
    for (let i = 0; i < labels.length; i++) {
        labels[i].className = "";
    }
});
    
// 選擇時段
var selectCount = 0;
var selectedTime = new Array();
var seconds = new Array();

$(".selectTime").click(function () {

    if ($(this).prop('checked')) {
        console.log('checked');
        selectCount++;

        selectedTime[selectCount-1] = $(this).siblings('span')[0].textContent;

        let timeFormatStart = $(this).siblings('span')[0].textContent.split(':');
        seconds.push(( + timeFormatStart[0]) * 60 * 60 + ( + timeFormatStart[1]) * 60);

        if (selectCount == 2) {
            $(".selectTime").not(':checked').prop( "disabled", true );
            $("#confirmTime").removeClass("disabled");
            if (seconds[0] > seconds[1]) {
                $(".previewTime").html(timeConvert(seconds[1]).h + ":" + ( timeConvert(seconds[1]).m == 0 ? "00" : "30" ) + " 到 " + timeConvert(seconds[0]).h + ":" + ( timeConvert(seconds[0]).m == 0 ? "00" : "30" ));
            }
            else {
                $(".previewTime").html(timeConvert(seconds[0]).h + ":" + ( timeConvert(seconds[0]).m == 0 ? "00" : "30" ) + " 到 " + timeConvert(seconds[1]).h + ":" + ( timeConvert(seconds[1]).m == 0 ? "00" : "30" ));
            }
        }
    }
    else {
        console.log('Unchecked');
        selectCount--;
        selectedTime.splice(selectedTime.indexOf($(this).siblings('span')[0].textContent), 1);
        
        let timeFormatStart = $(this).siblings('span')[0].textContent.split(':');
        
        seconds.splice(seconds.indexOf( ( + timeFormatStart[0]) * 60 * 60 + ( + timeFormatStart[1]) * 60 ), 1);

        $(".selectTime").prop("disabled", false);
        $("#confirmTime").addClass("disabled");
    }
    console.log(selectedTime);
    console.log(seconds);
});

// 清除選擇時段
$("#clearTime").click(function(){
    clearSelect();
});

// 跳出確定租借視窗
$("#confirmTime").click(function (){
    $('#modal2').modal('open');
    $('#modal1').modal('close');

    $(".previewTime").prepend(selectedDate.toLocaleString("zh-Hans-TW", {
        month: "long",
        day: "numeric",
        year: "numeric"
    }) + " ");
});

// 租借
$("#rent").click(function () {
    $("#modal2").modal('close');
    $("#modal3").modal('open');
});

// 關閉視窗清除資料
$(".modal-close").click(function () {
    clearSelect();
});

function timeConvert(secs) {
    var hours = Math.floor(secs / (60 * 60));

    var divisor_for_minutes = secs % (60 * 60);
    var minutes = Math.floor(divisor_for_minutes / 60);

    var obj = {
        "h": hours,
        "m": minutes,
    };
    console.log(obj);
    return obj;
}

function clearSelect () {
    $(".selectTime").prop( "disabled", false ).prop( "checked", false );
    $("#confirmTime").addClass("disabled");
    selectCount = 0;
    $(".previewTime").html("");
    selectedTime.length = 0;
    seconds.length = 0;
}