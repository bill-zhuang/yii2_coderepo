$(document).ready(function () {
    ajaxIndex();
});

function ajaxIndex() {
    var $tblTbody = $('#tbl').find('tbody');
    var getUrl = '/index.php/person/finance-category/ajax-index';
    var getData = {
        "params": $('#formSearch').serializeObject()
    };
    var method = 'get';
    var successFunc = function (result) {
        $tblTbody.empty();
        if (typeof result.data != "undefined") {
            for (var i = 0; i < result.data.currentItemCount; i++) {
                $tblTbody.append(
                    $('<tr>')
                        .append($('<td>').text(result.data.startIndex + i))
                        .append($('<td>').text(result.data.items[i]['name']))
                        .append($('<td>').text(result.data.items[i]['parent']))
                        .append($('<td>').text(result.data.items[i]['weight']))
                        .append($('<td>').text(result.data.items[i]['update_time']))
                        .append($('<td>')
                            .append($('<a>', {href: '#', id: 'modify_' + result.data.items[i]['fcid'], text: '修改'})
                                .click(function () {
                                    modifyFinanceCategory(this.id);
                                })
                            )
                            .append('  ')
                            .append($('<a>', {href: '#', id: 'delete_' + result.data.items[i]['fcid'], text: '删除'})
                                .click(function () {
                                    deleteFinanceCategory(this.id);
                                })
                            )
                        )
                );
            }
            if (result.data.totalItems == 0) {
                $tblTbody.append($('<tr>')
                    .append(
                        $('<td>').text('对不起,没有符合条件的数据').addClass('bill_table_no_data').attr('colspan', 6)
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
    //load main category
    loadMainCategory('finance_category_parent_id');
}

/*  --------------------------------------------------------------------------------------------------------  */
$('#btn_add').on('click', function () {
    window.FinanceCategoryForm.reset();
    $('#finance_category_fcid').val('');
    $('#btn_submit_finance_category').attr('disabled', false);
    $('#FinanceCategoryModal').modal('show');
});

$('#FinanceCategoryForm').on('submit', (function (event) {
    event.preventDefault();

    var fcid = $('#finance_category_fcid').val();
    var type = (fcid == '') ? 'add' : 'modify';
    if (isValidInput()) {
        $('#btn_submit_finance_category').attr('disabled', true);
        var postUrl = '/index.php/person/finance-category/' + type + '-finance-category';
        var postData = {
            "params": $('#FinanceCategoryForm').serializeObject()
        };
        var method = 'post';
        var successFunc = function (result) {
            $('#FinanceCategoryModal').modal('hide');
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

function modifyFinanceCategory(modifyId) {
    var fcid = modifyId.substr('modify_'.length);
    var postUrl = '/index.php/person/finance-category/get-finance-category';
    var postData = {
        "params": {
            "fcid": fcid
        }
    };
    var method = 'get';
    var successFunc = function (result) {
        if (typeof result.data != 'undefined') {
            $('#finance_category_name').val(result.data.name);
            $('#finance_category_parent_id').val(result.data.parent_id);
            $('#finance_category_weight').val(result.data.weight);
            $('#finance_category_fcid').val(result.data.fcid);
            $('#btn_submit_finance_category').attr('disabled', false);
            $('#FinanceCategoryModal').modal('show');
        } else {
            alert(result.error.message);
        }
    };
    jAjaxWidget.additionFunc(postUrl, postData, successFunc, method);
}

function deleteFinanceCategory(deleteId) {
    if (confirm(alertMessage.DELETE_CONFIRM)) {
        var fcid = deleteId.substr('delete_'.length);
        var postUrl = '/index.php/person/finance-category/delete-finance-category';
        var postData = {
            "params": {
                "fcid": fcid
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
    var name = $.trim($('#finance_category_name').val());
    var weight = $.trim($('#finance_category_weight').val());
    if (name == '') {
        isVerified = false;
        alert(alertMessage.NAME_ERROR);
    } else if (!RegexWidget.isUnsignedInt(weight)) {
        isVerified = false;
        alert(alertMessage.WEIGHT_FORMAT_ERROR);
    }

    return isVerified;
}