jQuery('div.misc-pub-section.curtime').after('<div class="misc-pub-section" id="hl"><a href="http://hl.fm/exchange/promote_this" target="_blank" class="hl_promote_this_button" data-message="YOUR MESSAGE HERE"><img src="http://hl.fm/exchange/images/promotethis.png" width="276" height="58" alt="Promote This" style="border: none" /></a><script>(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="http://hl.fm/exchange/javascripts/promote_this_widget.min.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","hl_promote_this_widget"));</script></div>')

function updatePR_link(){
  var promo_text = "YOUR MESSAGE HERE.";
  var promo_link = jQuery('#sample-permalink').text();
  if (promo_link !='undefined') {
  	var excerpt = jQuery('#excerpt').val();
  	if (excerpt!="") {
  		promo_text=excerpt+ " " +promo_link;
  	}else{
  		promo_text=promo_link;
  	}
  }
  jQuery('.hl_promote_this_button').attr('data-message',promo_text)
};

var intervalID = window.setInterval(updatePR_link,100);
