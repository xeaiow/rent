$(function() {
    loadAllRental();
});

var focusId = null;
var selectRoom = null;

// timestamp 轉換成完整年月日
function timestampToYearMonDay (timestamp) {
    return new Date(timestamp * 1000).toLocaleString("zh-Hans-TW", {
        month: "long",
        day: "numeric",
        year: "numeric"
    });
}

function loadAllRental () {
    axios.get('/rent/public/pineapple/get/rental')
    .then(function(response) {

        let res = response.data;

        $.each(res, function (i, val) {
            $("#rental").html(  
                '<tr id="lists-' + val.id + '">' + 
                    '<td>' + val.name + '</td>' + 
                    '<td>' + val.username + '</td>' + 
                    '<td>' + val.title.substring(0, val.title.length-6) + '</td>' + 
                    '<td>' + ( val.description == null ? "無" : val.description ) + '</td>' + 
                    '<td>' + val.phone + '</td>' + 
                    '<td>' + timestampToYearMonDay(val.rentDate) + '</td>' + 
                    '<td>' + periodToClock(val.period) + '</td>' + 
                    '<td>'+
                        '<button class="btn waves-effect blue darken-3" type="button" onclick="edit(' + val.id + ')" id="edit-' + val.id + '">編輯<i class="material-icons right">edit</i></button> ' + 
                        '<button class="btn waves-effect pink darken-1" type="button" onclick="reject(' + val.id + ')">駁回<i class="material-icons right">clear</i></button>' + 
                    '</td>' + 
                '</tr>'  
            );
        });
    });
}

function periodToClock (period) {
    return (new Date(period.substr(0, 5) * 1000)).toUTCString().match(/(\d\d:\d\d)/)[0] + " - " + (new Date(period.substr(6, 11) * 1000)).toUTCString().match(/(\d\d:\d\d)/)[0];
}

// 載入租借紀錄編輯資料
function edit (id) {

    $("#edit-container").show();

    axios.get('/rent/public/pineapple/get/edit/rental/' + id)
    .then(function(response) {
        let res = response.data;
        $("#edit-title").val(res.title);
        $("#edit-desc").val(res.description);
        $("#edit-phone").val(res.phone);
    });
    focusId = id;
}

// 更新租借紀錄資料
$("#edit-update").click(function () {
    axios.post('/rent/public/pineapple/update/rental', {
        id: focusId,
        title: $("#edit-title").val(),
        description: $("#edit-desc").val(),
        phone: $("#edit-phone").val()
    })
    .then(function(response) {
        let lists = $("#lists-" + focusId).find("td");
        lists[2].textContent = $("#edit-title").val();
        lists[3].textContent = $("#edit-desc").val();
        lists[4].textContent = $("#edit-phone").val();

        swal("Good", "更新完成", "success", {
            buttons: "知道了",
        });
    });
});

// 駁回
function reject (id) {
    focusId = id;
    swal({
        title: "確定駁回?",
        text: "駁回後將不可復原，視同於刪除這筆預約紀錄",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        buttons: ["沒事", true],
    })
    .then((willDelete) => {
        if (willDelete) {
            swal("已駁回", {
                icon: "success",
            });
            axios.get('/rent/public/pineapple/reject/rental/' + id)
            .then(function(response) {
                $("#lists-" + focusId).remove();
                $("#edit-container").hide();
            });
        }
    });
}

$("#edit-close").click(function () {
    $("#edit-container").hide();
});

$("#add-rental").click(function () {
    $("#add-rental-modal").modal("open");
});

$("#room").on('change', function () {

    $("select").formSelect();
    let instance = M.FormSelect.getInstance($(this));
    selectRoom = instance.getSelectedValues()[0];
});

$("#add").click(function () {
    
    let time_start = $("#start").val().split(':');
    let start = (+time_start[0]) * 60 * 60 + ( + time_start[1]*60);
    let time_end = $("#end").val().split(':');
    let end = (+time_end[0]) * 60 * 60 + ( + time_end[1]*60);
    let period = start+","+end;
    let date = new Date($("#rentDate").val());
    let selectDate = new Date (date.getFullYear(), date.getMonth(), date.getDate());
    let rentDate = moment.unix(selectDate) / 1000000;
    
    axios.post('/rent/public/pineapple/add/rental', {
        username: $("#add-username").val(),
        name: $("#add-name").val(),
        title: $("#add-title").val(),
        description: $("#add-desc").val(),
        phone: $("#add-phone").val(),
        rentDate: rentDate,
        period: period,
        room: selectRoom
    })
    .then(function(response) {

        if (!response.data.status) {
            swal("Failed", "新增失敗，請檢查錯誤", "error", {
                buttons: "知道了",
            });
            return false;
        }
        
        swal("Good", "新增完成", "success", {
            buttons: "知道了",
        });

        $("#add-username, #add-name, #add-title, #add-desc, #add-phone, #rentDate, #start, #end").val('');
        $("#add-rental-modal").modal('close');

        let val = response.data.result;

        $("#rental").append(  
            '<tr id="lists-' + val.id + '">' + 
                '<td>' + val.name + '</td>' + 
                '<td>' + val.username + '</td>' + 
                '<td>' + val.title.substring(0, val.title.length-6) + '</td>' + 
                '<td>' + ( val.description == null ? "無" : val.description ) + '</td>' + 
                '<td>' + val.phone + '</td>' + 
                '<td>' + timestampToYearMonDay(val.rentDate) + '</td>' + 
                '<td>' + periodToClock(val.period) + '</td>' + 
                '<td>'+
                    '<button class="btn waves-effect blue darken-3" type="button" onclick="edit(' + val.id + ')" id="edit-' + val.id + '">編輯<i class="material-icons right">edit</i></button> ' + 
                    '<button class="btn waves-effect pink darken-1" type="button" onclick="reject(' + val.id + ')">駁回<i class="material-icons right">clear</i></button>' + 
                '</td>' + 
            '</tr>'  
        );
    });
});

$("#reloadList").click(function () {
    loadAllRental();
});