jQuery( document ).ready(function() {


	/*********************************
	* check for correct answers: radio, checkbox, select
	*********************************/
	jQuery('.response').click(function(e){
	
	  // dropdowns are a pain
	  if(jQuery(this).find('option:selected')) {
	     var x = jQuery(this).find('option:selected').attr('data-ans');
	     if(x=='true') {
	       jQuery(this).parent().find('.dashicons').remove();
	       jQuery(this).parent().append("<img class='correct-icon' src='"+responsevars.pluginurl+"images/correct.png'/>");
	     }
	     else { jQuery(this).parent().find('.dashicons').remove(); }
	  }
	
	  // on radios and checkboxes, set background color if correct
	  if(jQuery(this).attr('data-ans')) {
	     var x = jQuery(this).attr('data-ans');
	     if(x=='true') { jQuery(this).parent().addClass('correct'); }
	  }
	});
	
	
	
	
	    jQuery('.text').keyup(function(e){

	     if(typeof jQuery(this).attr('data-ans') !== 'undefined') {

	      var answer = jQuery(this).attr('data-ans').toLowerCase().replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g,"").normalize("NFD").replace(/[\u0300-\u036f]/g, "");
	      var value = jQuery(this).val().toLowerCase().replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g,"").normalize("NFD").replace(/[\u0300-\u036f]/g, "");

	      if(answer.includes('|')) {
		var split = answer.split('|');
		jQuery.each(split, function(i,v){
		  if(value==v) {
		    txtfield.css({'background-image':'url("'+responsevars.pluginurl+'images/correct.png")','background-repeat':'no-repeat','background-position':'right'});
		  }
		});
	      }
	      else {
		if(answer==value) {
		  jQuery(this).css({'background-image':'url("'+responsevars.pluginurl+'images/correct.png")','background-repeat':'no-repeat','background-position':'right'});
		}
	      }

	    } // end if not undefined


	    });


	    jQuery('.glossed').mouseover(function(event) {

		  var gloss_str = jQuery(this).attr('gls');
		  jQuery(this).parent().append("<div class='gloss'>"+gloss_str+"</div>");
		  event.preventDefault();
		});
	    jQuery('.glossed').mouseout(function(event) {
		  jQuery(this).parent().find('.gloss').remove();
		  event.preventDefault();
		});


	    jQuery('.sentence').keyup(function(e){
	      var ans = jQuery(this).attr('ans');
	      if (typeof ans !== typeof undefined && ans !== false) {
		var answer = ans.toLowerCase();
	      }
	      else { var answer = ""; }
	      var value = jQuery(this).val().toLowerCase();

	      if(answer==value) {
		jQuery(this).css({'background-image':'url("'+responsevars.pluginurl+'images/correct.png")','background-repeat':'no-repeat','background-position':'right'});
	      }
	    });





	/**********************************************
	*  repopulate
	**********************************************/
	
	
	if(responsevars.userid != 0) { 
	
		var d = {
		  "action": "get_responses",
		  "userid":responsevars.userid,
		  "postid":responsevars.postid
		  }

		jQuery.get(responsevars.ajaxurl, d, function(data) {
			
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
	}; // if userid==0 (mean user is not logged in)




	/***************************************************
	* save
	***************************************************/	
	
      function save_user_data() {
      
         if(responsevars.userid != 0) { 

		  var formData = jQuery('.responseform').serializeObject();

		  var d = {
		    'action': 'save_responses',
		    'userid': responsevars.userid,
		    'postid': responsevars.postid,
		    'data': formData
		  };
		
		
		  jQuery.post(responsevars.ajaxurl, d, function(response) {
			console.log(response);
			return true;
		  });
	  
	  };  // if userid==0 (mean user is not logged in)

       }
	


	/***************************************************
	* triggers to save
	***************************************************/
	
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
	
	
	
	
	


});
