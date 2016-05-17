$(document).ready(function(){
    $(".button-collapse").sideNav();
    $(".left-menu-toggle").click(function(){
    	var left=$('.left-menu-toggle').css('left');
    	if(left == '0px') {
    		$(this).css('left','+=310');
    		$('.left-menu-toggle .material-icons').html('done');
    		$('.left-menu').removeClass('hide-on-med-and-down');
    	}
    	else {
    		$(this).css('left','-=310');
    		$('.left-menu-toggle .material-icons').html('menu');
    		$('.left-menu').addClass('hide-on-med-and-down');
    	}
    });
    $('select').material_select();
});