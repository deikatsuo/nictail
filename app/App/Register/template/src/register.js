$(document).ready(function(){
    $('select').material_select();
	$('#birthday').formatter({
          'pattern': '{{9999}}/{{99}}/{{99}}'
	});
});