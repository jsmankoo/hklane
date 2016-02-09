var wpServer = 'http://hklane2015.uptowncreativeinc.com/wp-json/wp/v2';

$.getJSON(wpServer + '/pages/186', function(data){
	var images = $(data.content.rendered);
	var slideShow = $("#slide-show");
	slideShow.append(images);
	slideShow.css({opacity: 1});
	slideShow.owlCarousel( {
                    pagination: false,
                    loop: true,
                    autoPlay: 5000,
                    singleItem: true,
                  });
	function cropSlide() {
		var wid = $(window).width();
		var ratio = 1600/602;
		var height = wid/ratio;

		if(wid > 1600){
			$('#slide-show .item').css({
				"width" : "100%",
				"height" : "602px"
			});
			$('#slide-show img').css({
				"width":"100%",
				"height":"auto",
				"margin": (602-height)/2 + "px 0px 0px 0px"
			});
		} else if( wid <= 1600 && wid > 992){
			$('#slide-show .item').css({
				"width" : "100%",
				"height" : "602px"
			});
			$('#slide-show img').css({
				"width":"1600px",
				"height":"602px",
				"margin": "0px 0px 0px " + (wid-1600)/2 + "px"
			});
		}else if( wid <= 992 && wid > 930){
			$('#slide-show .item').css({
				"width" : "100%",
				"height" : "350px"
			});
			$('#slide-show img').css({
				"width": wid+"px",
				"height": height+"px",
				"margin": (350-height)/2 + "px 0px 0px 0px"
			});
		}else if( wid <= 930){
			$('#slide-show .item').css({
				"width" : "100%",
				"height" : "350px"
			});
			$('#slide-show img').css({
				"width": (350*ratio)+"px",
				"height": "350px",
				"margin": "0px 0px 0px " + (wid-(350*ratio))/2 + "px"
			});
		}
	}

	cropSlide();

	$(window).resize(cropSlide);

	$(".loader").fadeOut("slow");
});
$.getJSON(wpServer + '/pages/5', function(data){
	var content = $(data.content.rendered);
	var section = $("#index-mid");
	section.append(content);
	section.css({opacity: 1});
});

$("#featuredProp").owlCarousel({
                    pagination: true,
                    loop: true,
                    autoPlay: 5000,
                    items: 3,
                    itemsMobile: [992,1] 

                  });