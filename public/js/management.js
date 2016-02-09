var wpServer = 'http://hklane2015.uptowncreativeinc.com/wp-json/wp/v2';

$.getJSON(wpServer + '/pages/12', function(data){
	var content = $(data.content.rendered);
	var section = $("#index-mid");
	section.append(content);
	section.css({opacity: 1});
	$(".loader").fadeOut("slow");
});

 $(document).ready(function(){
	//Chevron-toggle
	$('#harvey-toggle').click( function(){
		$(this).find('i').toggleClass('fa-chevron-up fa-chevron-down');
	});
});