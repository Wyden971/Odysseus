/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(function(){
    $(window).scroll(function(){
        var panel = $('#mainPanel');
        var top = panel.offset().top;
        var offsetTop = $(document).scrollTop();
        if(typeof panel.prop('defaultTopOffset') == "undefined"){
            panel.prop('defaultTopOffset', top);
        }
        top = panel.prop('defaultTopOffset');
        
        if(window.innerWidth < 768){
            panel.removeClass('locked');
        }else if(offsetTop > (top-10) ){
            if(!panel.hasClass('locked')){
                panel.addClass('locked');
            }
        }else if(panel.hasClass('locked')){
            panel.removeClass('locked');
        }
    }).resize(function(){
        var panel = $('#mainPanel');
        panel.width(panel.parent().width());
        $(window).trigger('scroll');
    }).trigger('resize');
})