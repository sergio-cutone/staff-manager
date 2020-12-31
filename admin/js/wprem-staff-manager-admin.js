(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	class StaffSettings {
		constructor(id, arg){
			this.id = id;
			this.arg = arg;
			if (!document.getElementById(this.id))
				return false;
			this.val = document.getElementById(this.id).value
		}

		get set() {
			if (this.val)
				return " "+this.go();
			else
				return "";
		}

		go(){
			return this.arg+'="'+this.val+'"';
		}
	}

	 $(document).ready( function(){

	 	if ($("#data #_data_order").length){
	 		$("#data #_data_order").prop("readonly", "readonly");
	 	}

	 	$("#sortable").sortable({
  			stop: get_sorted
		});
	 	$("#sortable").disableSelection();
	 	$( "#tabs" ).tabs();

	 	function get_sorted(){
	 		var sorted = '';
	 		$("#sortable li").each(function(){
	 			var id = $(this).attr("data-id");
	 			sorted = sorted+","+id;
	 		});
	 		$("#staff_sort").val(sorted.substr(1));
	 	}
	 	get_sorted();

	 	$(".edit-view").on("click",function(){
	 		var id = $(this).prevAll(".selected-view").val();
	 		console.log("id: "+id);
	 		window.location.href = '/wp-admin/post.php?post='+id+'&action=edit';
	 	});

	 });

	 $(document).on("click","#staff-insert", function(){
	 	staff_container();
	 });

	 function staff_container(){
		const id = new StaffSettings('wp_staff_id','id');
		const view = new StaffSettings('wp_staffview_id','view');
		const service = new StaffSettings('wp_allservices_id','service');
		const cat = new StaffSettings('wp_staff_cat','cat');
		window.send_to_editor("[wp_staff"+id.set+view.set+service.set+cat.set+"]");
	 }

	})( jQuery );

