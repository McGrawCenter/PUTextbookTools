jQuery( document ).ready(function() {


	if(vars.userid != 0) { // userid cannot be zero, must be logged in


		/**********************************************
		*  Repopulate fields on the page
		**********************************************/
/*
		var d = {
		    'action': 'get_responses',
		    'userid': vars.username,
		    'postid': vars.postid
		    
		};
*/

		var d  = {"uri":"https://www.princeton.edu"}
		var url = "http://ect-language.princeton.edu/responses";

		jQuery.get(url, d, function(data) {
			console.log(data);
			//data = jQuery.parseJSON(data);
			//console.log(data);

			jQuery.each(data, function(name, val) {

			  val = decodeURIComponent(val).replace(/\"/g, "'");
			
			  jQuery('#' + name).filter('input[type=text]').val(val);
			  jQuery('#' + name).filter('select').val(val);
			  jQuery('#' + name).filter('textarea').text(val);
			  jQuery('#wysiwyg' + name).html(val);
			  jQuery('input[name="'+name+'"]').filter('input[value="'+val+'"]').attr('checked','checked');
			  if(val == 'on') {
			    jQuery('input[name="'+name+'"]').attr('checked','checked');
			  }

			  // checkboxes are kind of a pain:
			  if(typeof(val) == 'object') { 
			   jQuery.each( val, function( index, value ){
			       	jQuery('input[name="'+name+'"]').filter('input[value="'+value+'"]').attr('checked','checked');
			   });
			  }

			});
		});
	
	/***************************************************
	* save
	***************************************************/	
	
      function save_user_data() {

	var formData = jQuery('.responseform').serializeObject();
	
	

	var data = {
	    'userid': vars.username,
	    'postid': vars.postid,
	    'data': formData,
	    'action': 'save_responses'
	};
	console.log(data);
	jQuery.post(vars.ajaxurl, data, function(response) {
		return true;
	});

      }
	
	
	
	
	// TRIGGERS TO SAVE USER DATA
	jQuery( "#langfuncsave").click(function(e) {
	   jQuery(this).text( "Saving" );
	   setTimeout(function() { jQuery("#langfuncsave").text( "Saved" );
		setTimeout(function() { jQuery("#langfuncsave").text( "Save" ); },1500);
	   },1500);
	   
	   save_user_data();
	   e.preventDefault();

	});




	/***************************************************
	* serialize object
	***************************************************/
	(function($){
	    jQuery.fn.serializeObject = function() {
		var o = {};
		var a = this.serializeArray();
		jQuery.each(a, function() {

		    //this.value = this.value.replace(/"/g, '\`');
		    //this.value = this.value.replace(/'/g, '\`');
		    this.value = encodeURIComponent(this.value);

		    if (o[this.name] !== undefined) {
		        if (!o[this.name].push) {
		            o[this.name] = [o[this.name]];
		        }
		        o[this.name].push(this.value || '');
		    } else {
		        o[this.name] = this.value || '';
		    }
		});
		return JSON.stringify(o);
	    };
	})(jQuery);






	} // end if userid != 0


});
