function deleteImgDiv(img_div_id)
{
    $(img_div_id).remove();
}

function getImgsInDiv(div_id)
{
    var pics = [];
    $('#' + div_id + ' img').each(function() {
        pics.push($(this).attr('src'));
    });

    return pics;
}

function deleteDiv(div_id)
{
    $('#' + div_id).remove();
}
/*  ----------------------------------------------------------------------------------------------------------------------  */
function batchMute(obj, child_name)
{
    $('input[name="' + child_name + '"]').each(function(){
        $(this).prop('checked', obj.checked);
    });
}

function closeBatch(obj, parent_id)
{
    if (!obj.checked) {
        $('#' + parent_id).prop('checked', false);
    }
}

function getBatchIDs(child_name)
{
    var selected = [];
    $("input[name='" + child_name + "']:checked").each(function(){
        selected.push(this.value);
    });
    
    return selected;
}

function getCheckboxIDs(div_id)
{
    var selected = [];
    $('#' + div_id + ' input:checked').each(function(){
        selected.push(this.value);
    });
    
    return selected;
}
/*  ----------------------------------------------------------------------------------------------------------------------  */