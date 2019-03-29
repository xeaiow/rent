$(function() {
    loadAllRental();
    loadRecipient();
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

function summary (str) {
    return ( str.length > 8 ? str.substr(0, 8) + "..." : str );
}

function loadAllRental () {
    axios.get('/pineapple/get/rental')
    .then(function(response) {

        let res = response.data;

        $.each(res, function (i, val) {
            $("#rental").append(  
                '<tr id="lists-' + val.id + '">' + 
                    '<td>' + val.name + '</td>' + 
                    '<td>' + val.username + '</td>' + 
                    '<td title="' + val.title + '">' + summary(val.title.substring(0, val.title.length-6)) + '</td>' + 
                    '<td class="hidden">' + ( val.description == null ? "無" : summary(val.description) ) + '</td>' + 
                    '<td>' + val.phone + '</td>' +
                    '<td>' + val.room + '</td>' + 
                    '<td>' + timestampToYearMonDay(val.rentDate) + '</td>' + 
                    '<td>' + periodToClock(val.period) + '</td>' + 
                    '<td  class="hidden">' + val.created_at + '</td>' +
                    '<td  class="hidden">'+
                    '<button class="ts icon button secondary" onclick="edit(' + val.id + ')" id="edit-' + val.id + '">'+
                        '<i class="edit icon"></i>'+
                    '</button>'+ 
                    '<button class="ts icon button secondary" onclick="reject(' + val.id + ')">'+
                        '<i class="ban icon"></i>'+
                    '</button>'+ 
                    '</td>' + 
                '</tr>'  
            );
        });
        $('#myTable').DataTable();
    });

    axios.get('/pineapple/get/statistics')
    .then(function(response) {
        console.log(response);
        $("#total").text(response.data.total);
        $("#book").text(response.data.book);
        $("#members").text(response.data.members);
    });
}

function periodToClock (period) {
    return (new Date(period.substr(0, 5) * 1000)).toUTCString().match(/(\d\d:\d\d)/)[0] + " - " + (new Date(period.substr(6, 11) * 1000)).toUTCString().match(/(\d\d:\d\d)/)[0];
}

// 載入租借紀錄編輯資料
function edit (id) {

    ts('#optionModal').modal({
        approve: '.positive, .approve, .ok',
        deny: '.negative, .deny, .cancel',
        onDeny: function() {
            
        },
        onApprove: function() {
            editUpdate();
        }
    }).modal("show");

    $("#edit-container").show();

    axios.get('/pineapple/get/edit/rental/' + id)
    .then(function(response) {
        let res = response.data;
        $("#edit-title").val(res.title);
        $("#edit-desc").val(res.description);
        $("#edit-phone").val(res.phone);

        let start_source = new Date(null);
        let end_source = new Date(null);

        start_source.setSeconds(parseInt(res.period.substr(0, 5)));

        end_source.setSeconds(parseInt(res.period.substr(6, 11)));

        let start = start_source.toISOString().substr(11, 8);
        let end = end_source.toISOString().substr(11, 8);

        $("#edit-start").val(res.period.substr(0, 5));
        $("#edit-end").val(res.period.substr(6, 11));
        $("#edit-start").val(start);
        $("#edit-end").val(end);
        $("#edit-name").text(res.name + "的租借紀錄");
    });
    focusId = id;
}

// 更新租借紀錄資料
function editUpdate () {

    var start = $("#edit-start").val().split(':');
    var end = $("#edit-end").val().split(':');

    let start_t = 0;
    let end_t = 0;
    
    start_t += start[0] * 3600;
    start_t += start[1] * 60;
    
    end_t += end[0] * 3600;
    end_t += end[1] * 60;
    
    axios.post('/pineapple/update/rental', {
        id: focusId,
        title: $("#edit-title").val(),
        description: $("#edit-desc").val(),
        phone: $("#edit-phone").val(),
        start: start_t,
        end: end_t
    })
    .then(function(response) {

        if (!response.data.status) {
            ts('.snackbar').snackbar({
                content: '更新失敗',
                actionEmphasis: 'positive'
            });
            return false;
        }

        let lists = $("#lists-" + focusId).find("td");
        lists[0].innerHTML = '<mark>' + lists[0].textContent + '</mark>';
        lists[2].textContent = summary($("#edit-title").val());
        lists[3].textContent = ( $("#edit-desc").val() == "" ? "無" : summary($("#edit-desc").val()) );
        lists[4].textContent = $("#edit-phone").val();

        ts('.snackbar').snackbar({
            content: '更新完成',
            actionEmphasis: 'positive'
        });
    });
}

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
            axios.get('/pineapple/reject/rental/' + id)
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

// $("#room").on('change', function () {

//     $("select").formSelect();
//     let instance = M.FormSelect.getInstance($(this));
//     selectRoom = instance.getSelectedValues()[0];
// });

function add () {
    
    let time_start = $("#start").val().split(':');
    let start = (+time_start[0]) * 60 * 60 + ( + time_start[1]*60);
    let time_end = $("#end").val().split(':');
    let end = (+time_end[0]) * 60 * 60 + ( + time_end[1]*60);
    let period = start+","+end;
    let date = new Date($("#rentDate").val());
    let selectDate = new Date (date.getFullYear(), date.getMonth(), date.getDate());
    let rentDate = moment.unix(selectDate) / 1000000;
    
    axios.post('/pineapple/add/rental', {
        username: $("#add-username").val(),
        name: $("#add-name").val(),
        title: $("#add-title").val(),
        description: $("#add-desc").val(),
        phone: $("#add-phone").val(),
        rentDate: rentDate,
        period: period,
        room: $("#room").val()
    })
    .then(function(response) {

        if (!response.data.status) {

            let errorText = "請查看錯誤";

            switch (response.data.error) {
                case 1:
                    errorText = "所選的教室不被允許";
                    break;
                case 2:
                    errorText = "開始或結束的時間不合法";
                    break;
                case 3:

                    $("#selectorConflict").html("<mark>" + periodToClock(period) + "</mark> 選取的時段已被預約");
                    $.each(response.data.result, function (i, v) {
                        $("#conflict").append(
                            '<tr><td>' + parseInt(i+1) + '</td><td>' + v.name + '</td><td>' + v.username + '</td><td>' + v.phone + '</td><td>' + timestampToYearMonDay(v.rentDate) + '</td><td><mark>' + periodToClock(v.period) + '</mark></td><td>' + v.title + '</td></tr>'
                        );
                    });

                    ts('#conflictModal').modal("show");

                    
                    return false;
                    break;
                default:
                    break;
            }

            swal("Failed", errorText, "error", {
                buttons: "知道了",
            });
            return false;
        }
        
        swal("Good", "新增完成", "success", {
            buttons: "知道了",
        });

        $("#add-username, #add-name, #add-title, #add-desc, #add-phone, #rentDate, #start, #end").val('');

        let val = response.data.result;

        $("#rental").append(  
            '<tr id="lists-' + val.id + '">' + 
                '<td>' + val.name + '</td>' + 
                '<td>' + val.username + '</td>' + 
                '<td>' + summary(val.title.substring(0, val.title.length-6)) + '</td>' + 
                '<td>' + ( val.description == null ? "無" : summary(val.description) ) + '</td>' + 
                '<td>' + val.phone + '</td>' + 
                '<td>' + timestampToYearMonDay(val.rentDate) + '</td>' + 
                '<td>' + periodToClock(val.period) + '</td>' + 
                '<td>'+
                    '<button class="ts icon button" onclick="edit(' + val.id + ')" id="edit-' + val.id + '">'+
                        '<i class="heart icon"></i>'+
                    '</button>'+ 
                    '<button class="btn waves-effect pink darken-1" type="button" onclick="reject(' + val.id + ')">駁回<i class="material-icons right">clear</i></button>' + 
                '</td>' + 
            '</tr>'  
        );
    });
}

$("#reloadList").click(function () {
    $("#rental").html('');
    loadAllRental();
});


$("#addBook").click(function () {
    
    ts('#addBookModal').modal({
        approve: '.positive, .approve, .ok',
        deny: '.negative, .deny, .cancel',
        onDeny: function() {
            
        },
        onApprove: function() {
            add();
        }
    }).modal("show");
});

function printData()
{
   var divToPrint=document.getElementById("myTable");
   $(".hidden").hide();
   newWin= window.open("");
   newWin.document.write(divToPrint.outerHTML);
   newWin.print();
   newWin.close();
   $(".hidden").show();
}

$("#print").on('click',function(){
    printData();
})

$("#backup").click(function () {
    ts('#settingModal').modal({
        approve: '.positive, .approve, .ok',
        deny: '.negative, .deny, .cancel',
        onDeny: function() {
            
        },
        onApprove: function() {
            addRecipient();
        },
    }).modal("show");
});

function addRecipient () {

    let name = $("#recipient-name").val();
    let email = $("#recipient-email").val();

    if (name == '' || email == '') {
        return swal("失敗", "E-mail 或稱呼沒有輸入", "error", {
            buttons: "知道了",
        }); 
    }

    axios.post('/pineapple/add/recipient', {
        name: name,
        email: email
    })
    .then(function(res) {

       if (res.data > 0) {

            swal("成功", "此信箱今天開始會收到資料庫備份", "success", {
                buttons: "知道了",
            });

            $("#recipientLists").append(
                '<div class="item">'+
                    '<img class="ts avatar image" src="https://tocas-ui.com/assets/img/5e5e3a6.png">'+
                    '<div class="content">'+
                        '<a class="header">' + name + '</a>'+
                        '<div class="description"><b>' + email + '</b></div>'+
                    '</div>'+
            '</div>'
            );
       }
    });
}

function loadRecipient () {
    
    axios.get('/pineapple/get/recipient')
    .then(function(response) {

        let res = response.data;

        $.each(res, function (i, val) {
            $("#recipientLists").append(
                '<div class="item">'+
                    '<img class="ts avatar image" src="https://tocas-ui.com/assets/img/5e5e3a6.png">'+
                    '<div class="content">'+
                        '<a class="header">' + val.name + '</a>'+
                        '<div class="description"><b>' + val.email + '</b></div>'+
                    '</div>'+
            '</div>'
            );
        });
    });
}

$("#backupTest").click(function () {
    axios.get('/sendmail')
    .then(function(res) {
    });
    swal("成功", "請至信箱查看備份", "success", {
        buttons: "知道了",
    });
});