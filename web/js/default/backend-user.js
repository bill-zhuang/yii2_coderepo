$(document).ready(function () {
    ajaxIndex();
});

function ajaxIndex() {
    var $tblTbody = $('#tbl').find('tbody');
    var getUrl = '/index.php/backend-user/ajax-index';
    var getData = {
        "params": $('#formSearch').serializeObject()
    };
    var method = 'get';
    var successFunc = function (result) {
        $tblTbody.empty();
        if (typeof result.data != "undefined") {
            var isDelete = ($('#tab_type').val() == 1);
            var operateName = isDelete ? '删除' : '恢复';
            for (var i = 0; i < result.data.currentItemCount; i++) {
                var operateID = (isDelete ? 'delete_' : 'recover_') + result.data.items[i]['buid'];
                var operateMethod = isDelete ? deleteBackendUser : recoverBackendUser;
                $tblTbody.append(
                    $('<tr>')
                        .append($('<td>').text(result.data.startIndex + i))
                        .append($('<td>').text(result.data.items[i]['name']))
                        .append($('<td>').text(result.data.items[i]['role']))
                        .append($('<td>')
                            .append($('<a>', {href: '#', id: 'modify_' + result.data.items[i]['buid'], text: '修改'})
                                .click(function () {
                                    modifyBackendUser(this.id);
                                })
                            )
                            .append('  ')
                            .append($('<a>', {href: '#', id: operateID, text: operateName})
                                .click(function () {
                                    operateMethod(this.id);
                                })
                            )
                        )
                );
            }
            if (result.data.totalItems == 0) {
                $tblTbody.append($('<tr>')
                    .append(
                        $('<td>').text('对不起,没有符合条件的数据').addClass('bill_table_no_data').attr('colspan', 4)
                    )
                );
            }
            //init pagination
            initPagination(result.data.totalPages, result.data.pageIndex);
        } else {
            alert(result.error.message);
        }
    };
    jAjaxWidget.additionFunc(getUrl, getData, successFunc, method);
}

$('#ul_tab_type').find('li').on('click', function () {
    var tabValue = parseInt($(this).attr('id').substr('li_tab_type_'.length));
    tabValue = isNaN(tabValue) ? 1 : tabValue;
    $('#tab_type').val(tabValue);
    $('#ul_tab_type').find('li').removeClass('active');
    $('#li_tab_type_' + tabValue).addClass('active');
    $('#current_page').val(1);
    ajaxIndex();
});

/*  --------------------------------------------------------------------------------------------------------  */
$('#btn_add').on('click', function () {
    window.formBackendUser.reset();
    $('#backend_user_buid').val('');
    $('#btn_submit_backend_user').attr('disabled', false);
    var getUrl = '/index.php/backend-role/get-all-roles';
    var getData = {
        'params': {}
    };
    var method = 'get';
    var successFunc = function (result) {
        if (typeof result.data != 'undefined') {
            var roleSelect = '';
            for (var brid in result.data) {
                roleSelect = roleSelect + '<option value="' + brid + '">' + result.data[brid] + '</option>';
            }
            $('#backend_user_brid').empty().append(roleSelect);
            $('#modalBackendUser').modal('show');
        } else {
            alert(result.error.message);
        }
    };
    jAjaxWidget.additionFunc(getUrl, getData, successFunc, method);

});

$('#formBackendUser').on('submit', (function (event) {
    event.preventDefault();

    var buid = $('#backend_user_buid').val();
    var type = (buid == '') ? 'add' : 'modify';
    if (isValidInput()) {
        $('#btn_submit_backend_user').attr('disabled', true);
        var postUrl = '/index.php/backend-user/' + type + '-backend-user';
        var postData = {
            "params": $('#formBackendUser').serializeObject()
        };
        var method = 'post';
        var successFunc = function (result) {
            $('#modalBackendUser').modal('hide');
            if (typeof result.data != 'undefined') {
                alert(result.data.message);
            } else {
                alert(result.error.message);
            }
            ajaxIndex();
        };
        jAjaxWidget.additionFunc(postUrl, postData, successFunc, method);
    }
}));

function modifyBackendUser(modifyId) {
    var buid = modifyId.substr('modify_'.length);
    var getUrl = '/index.php/backend-user/get-backend-user';
    var getData = {
        "params": {
            "buid": buid
        }
    };
    var method = 'get';
    var successFunc = function (result) {
        if (typeof result.data != 'undefined') {
            var roleSelect = '';
            for (var brid in result.data.roles) {
                roleSelect = roleSelect + '<option value="' + brid + '">' + result.data.roles[brid] + '</option>';
            }
            $('#backend_user_buid').val(result.data.buid);
            $('#backend_user_name').val(result.data.name);
            $('#backend_user_brid').empty().append(roleSelect).val(result.data.brid);
            $('#btn_submit_backend_user').attr('disabled', false);
            $('#modalBackendUser').modal('show');
        } else {
            alert(result.error.message);
        }
    };
    jAjaxWidget.additionFunc(getUrl, getData, successFunc, method);
}

function deleteBackendUser(deleteId) {
    if (confirm(alertMessage.DELETE_CONFIRM)) {
        var buid = deleteId.substr('delete_'.length);
        var postUrl = '/index.php/backend-user/delete-backend-user';
        var postData = {
            "params": {
                "buid": buid
            }
        };
        var method = 'post';
        var successFunc = function (result) {
            if (typeof result.data != 'undefined') {
                alert(result.data.message);
            } else {
                alert(result.error.message);
            }
            ajaxIndex();
        };
        jAjaxWidget.additionFunc(postUrl, postData, successFunc, method);
    }
}

function recoverBackendUser(recoverId) {
    if (confirm('确认恢复帐号？')) {
        var buid = recoverId.substr('recover_'.length);
        var postUrl = '/index.php/backend-user/recover-backend-user';
        var postData = {
            "params": {
                "buid": buid
            }
        };
        var method = 'post';
        var successFunc = function (result) {
            if (typeof result.data != 'undefined') {
                alert(result.data.message);
            } else {
                alert(result.error.message);
            }
            ajaxIndex();
        };
        jAjaxWidget.additionFunc(postUrl, postData, successFunc, method);
    }
}

function isValidInput() {
    var isVerified = true;
    var name = $.trim($('#backend_user_name').val());
    if (name === '') {
        alert('user name can\'t empty.');
        isVerified = false;
    }

    return isVerified;
}
