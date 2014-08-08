///// NOTIFICATION CLOSE BUTTON /////
jQuery(document).ready(function(){	
	jQuery('.notibar .close').click(function(){
		jQuery(this).parent().fadeOut(function(){
			jQuery(this).remove();
		});
	});
});

jQuery(document).ready(function() {
    jQuery("[href$='-process']").hide();
    jQuery("div.update-message:contains(There is a new version of Uni Level MLM Pro available.)").remove();
});