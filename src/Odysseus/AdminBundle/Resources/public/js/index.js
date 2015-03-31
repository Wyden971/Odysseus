/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
    
    $('.items-table tbody input[type=\"checkbox\"]').change(function(){
        var items = [];
        $('.items-table tbody input[type=\"checkbox\"]').each(function(){
            if(this.checked)
                items.push(this.value);
        })
        
        $('.items-table thead input[type=\"checkbox\"]').prop('checked', ($('.items-table tbody input[type=\"checkbox\"]').length == items.length));
        var url = '#';
        if(items.length > 0)
            url = $('.items-table').data('delete-path').replace('ids', items.join(','));
        console.log(url);
        $('a.delete-multiple').attr('href', url);
    });
    
    $('.items-table thead input[type=\"checkbox\"]').change(function(){
        $('.items-table tbody input[type=\"checkbox\"]').prop('checked', this.checked);
    });
    
    $(window).scroll(function(){
        var panel = $('#mainPanel');
        if(panel.length == 0)
            return;
            
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
        if(panel.length == 0)
            return;
        panel.width(panel.parent().width());
        $(window).trigger('scroll');
    }).trigger('resize');
})