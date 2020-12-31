(function( $ ) {
	'use strict';

	 function valign(n){
	 	$(n).css("height","auto");
	 	var h = 0;
	 	$(n).each(function(){
	 		if ($(this).outerHeight() > h){
	 			h = $(this).outerHeight();
	 		}
	 	});
	 	$(n).height(h);
	 }

	 function staff_photos(){
	 	if ($(".wprem-staff-container .wprem-staff .staff-photo-square").length || $(".wprem-staff-container .wprem-staff .staff-photo-circle").length){
	 		$(".wprem-staff-container .wprem-staff .wprem-image").each(function(){
	 			$(this).height($(this).width());
	 		});
	 	}
	 }

	 $(document).on("click",".wprem-contact-staff", function(e){
	 	e.preventDefault();
	 	$("#staff-member-contact").show();
	 	$('html, body').animate({
        	scrollTop: $("#staff-member-contact").offset().top
    	}, 1000);
	 	var to_staff = $(this).attr("data-email");
	 	var to_name = $(this).attr("data-name");
	 	console.log("works "+$(this).attr("data-email"));
	 	setTimeout(function(){
			$("#staff-member-contact .staff-member-email input[type=text]").val(to_staff);
			$("#staff-member-contact .gform_title").html("Contact "+to_name);
	 	},50);
	 });

	 $(document).ready(function(){
	 	//valign('.wp_name_title');
	 	//valign('.wp_info');
	 	//valign('.wp_service');
		staff_photos();
	 });

	 $(window).resize(function(){
	 	//valign('.wp_name_title');
	 	//valign('.wp_info');
	 	//valign('.wp_service');
	 	staff_photos();
	 });

	 $(window).load(function(){
	 	// Show GF Confirmation Thank You on Singular Staff Member Page
	 	if ($(".gform_confirmation_message").length){
	 		$("#staff-member-contact").show();
	 	}
	 	$(".gf_readonly input").attr("readonly","readonly");
	 });

})( jQuery );
