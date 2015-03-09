/**
 * Created by Administrator on 15-2-18.
 */
$(document).ready(function(){
    var li_content = '';
    var cookie_href = $.cookie('nav-top');
    if (typeof cookie_href == 'undefined') {
        li_content = $('ul.nav.navbar-nav li').find('.active');
    } else {
        $('ul.nav.navbar-nav li ul li').children('a').each(function(){
            if (cookie_href == $(this).attr('href')) {
                $(this).parent().parent().parent().addClass('active') //set current class active
                    .siblings().removeClass('active'); //remove brother class active
                //get active ul li
                li_content = $(this).parent().siblings().andSelf();
            }
        });
    }
    //console.log(li_content);
    if (li_content != '') {
        li_content.appendTo('#main > ul, ul.nav.navbar-nav li.dropdown.active ul.dropdown-menu');
        $('#main > ul li').children('a').each(function(){
            if (cookie_href == $(this).attr('href')) {
                $(this).css('color', 'red').css('text-decoration', 'underline');
            }
        });
    }
});

$('ul.nav.navbar-nav li ul li a').on('click', function(){
    var href = $(this).attr('href');
    $.cookie('nav-top', href, {expires : 1, path : '/'});
});