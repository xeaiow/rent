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
                currentDay.classList.add("pink");
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
        emptyMessage.innerHTML = "本日尚無租借紀錄";
        sidebarEvents.appendChild(emptyMessage);
        let emptyFormMessage = document.getElementById("emptyFormTitle");

    }
}

gridTable.onclick = function(e) {

    if (!e.target.classList.contains("col") || e.target.classList.contains("empty-day")) {
        return;
    }

    if (selectedDayBlock) {
        if (selectedDayBlock.classList.contains("pink") && selectedDayBlock.classList.contains("lighten-3")) {
            selectedDayBlock.classList.remove("pink");
            selectedDayBlock.classList.remove("lighten-3");
        }
    }
    selectedDayBlock = e.target;
    selectedDayBlock.classList.add("pink");
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


$("#addEventButton").click(function() {
    let title = document.getElementById("eventTitleInput").value.trim();
    let desc = document.getElementById("eventDescInput").value.trim();

    // 如果原因欄位是空的
    if (!title) {
        document.getElementById("eventTitleInput").value = "";
        document.getElementById("eventDescInput").value = "";
        let labels = addForm.getElementsByTagName("label");
        for (let i = 0; i < labels.length; i++) {
            labels[i].className = "";
        }
        return;
    }

    // 增加紀錄
    addEvent(title, desc);
    showEvents();

    if (!selectedDayBlock.querySelector(".day-mark")) {
        selectedDayBlock.appendChild(document.createElement("div")).className = "day-mark";
    }
});

// 選擇時段
var selectCount = 0;
var selectedTime = new Array();
var seconds = new Array();
var rentalRes = null;
var round = 0;
$(".selectTime").click(function() {

    if ($(this).prop('checked')) {
        
        let timestamp = $(this).attr("id");
        

        $.each(rentalRes, function (key, val) {
            $(".selectTime").prop("disabled", true);
            if (timestamp.substr(1).slice(0, -1) < val) {
                round = ((val-1800) - (timestamp.substr(1).slice(0, -1))) / 1800;
                return false;
            }
            else {
                round = (77400 - parseInt(timestamp.substr(1).slice(0, -1))) / 1800;
            }
            
        });

        let timestampSecode = parseInt(timestamp.substr(1).slice(0, -1));

        if ( (selectCount == 0 && round == 0 && rentalRes.length != 0 ) || ( selectCount == 0 && round == 0 && timestampSecode >= 77400 ) ) {
            reset();
            alert("不能在這個時段作為開始");
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
    } else {
  
        selectCount--;
        selectedTime.splice(selectedTime.indexOf($(this).siblings('span')[0].textContent), 1);

        let timeFormatStart = $(this).siblings('span')[0].textContent.split(':');

        seconds.splice(seconds.indexOf((+timeFormatStart[0]) * 60 * 60 + (+timeFormatStart[1]) * 60), 1);

        $(".selectTime").prop("disabled", false);
        $("#confirmTime").addClass("disabled");
        let timestamp = $(this).attr("id");

        $.each(rentalRes, function (key, val) {

            if (timestamp.substr(1).slice(0, -1) < val) {
                round = ((val-1800) - (timestamp.substr(1).slice(0, -1))) / 1800;
                return false;
            }
            else {
                round = (77400 - parseInt(timestamp.substr(1).slice(0, -1))) / 1800;
            }
        });

        let timestampSecode = parseInt(timestamp.substr(1).slice(0, -1));
        
        for (var i = 0; i <= round; i++) {
            
            $("#t" + timestampSecode + "t").prop("disabled", false);

            timestampSecode += 1800;
        }

        for (var i = 0; i < rentalRes.length; i++) {
            if ($("#t" + rentalRes[i]) + "t"[0].id == "t" + rentalRes[i] + "t") {
                $("#t" + rentalRes[i] + "t").prop("disabled", true);
            }
        }
    }
});


// 清除選擇時段
$("#clearTime").on('click', function() {
    reset();
});

// 跳出確定租借視窗
$("#rent").click(function() {
    $('#modal2').modal('open');
    $('#modal1').modal('close');

    $(".previewTime").prepend(selectedDate.toLocaleString("zh-Hans-TW", {
        month: "long",
        day: "numeric",
        year: "numeric"
    }) + " ");
});

// 租借
$("#rent").click(function() {
    $("#modal1").modal('close');
    $("#modal3").modal('open');
});

// 送出租借請求
$("#confirmRent").click(function() {
    let title = document.getElementById("eventTitleInput").value.trim();
    let desc = document.getElementById("eventDescInput").value.trim();

    // 如果原因欄位是空的
    if (!title) {
        document.getElementById("eventTitleInput").value = "";
        document.getElementById("eventDescInput").value = "";
        let labels = addForm.getElementsByTagName("label");
        for (let i = 0; i < labels.length; i++) {
            labels[i].className = "";
        }
        return;
    }

    // 增加紀錄
    addEvent(title, desc);
    showEvents();

    if (!selectedDayBlock.querySelector(".day-mark")) {
        selectedDayBlock.appendChild(document.createElement("div")).className = "day-mark";
    }

    $("select").formSelect();
    let instance = M.FormSelect.getInstance($("#room"));

    axios.post('/rent/public/set/rental', {
        title: title,
        description: desc,
        room: instance.getSelectedValues()[0],
        rentDate: selectedDate.getTime() / 1000,
        period: JSON.stringify(seconds)
    })
    .then(function(response) {
        $("#modal3").modal('close');
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
    
    clearSelect();

    $("select").formSelect();
    let instance = M.FormSelect.getInstance($(this));

    axios.get('/rent/public/get/rental/' + selectedDate.getTime() / 1000 + '/' + instance.getSelectedValues())
    .then(function (response) {

        rentalRes = response.data;

        $("#selectTimeTable").show();
        
        for (var i = 0; i < rentalRes.length; i++) {
            if ($("#t"+rentalRes[i] + "t")[0].id == "t" + rentalRes[i] + "t") {
                $("#t"+rentalRes[i] + "t").prop("disabled", true);
            }
        }
    });
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