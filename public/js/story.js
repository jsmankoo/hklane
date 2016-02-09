var wpServer = 'http://hklane2015.uptowncreativeinc.com/wp-json/wp/v2';

$.getJSON(wpServer + '/pages/10', function(data){
	var content = $(data.content.rendered);
	var section = $("#index-mid");
	section.append(content);
	section.css({opacity: 1});
	$(".loader").fadeOut("slow");
});