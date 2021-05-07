<?php

/****************** SHORTCODES *******************/


class PUTextbookToolsShortcodes{

	
	function __construct() {       
	   add_shortcode("textarea", 		array( $this, "insert_textarea" ) );
	   add_shortcode("text",  		array( $this, "insert_text" ) );
	   add_shortcode("sentence",  	array( $this, "insert_sentence" ) );
	   add_shortcode("dropdown",  	array( $this, "insert_dropdown" ) );
	   add_shortcode("checkboxes",  	array( $this, "insert_checkboxes" ) );
	   add_shortcode("radio",  		array( $this, "insert_radio" ) );
	   add_shortcode("save", 		array( $this, "insert_save" ) );
	}
	
	/**************************
	* USED AS A COUNTER FOR FORM ELEMENTS
	***************************/
	public function formfield_counter() {  
	  static $formfield_count=0; $formfield_count++; return $formfield_count;
	}	
	


	/**************************
	* TEXTAREA and TEXTBOX
	***************************/

	public function insert_textarea($atts) {
	  $returnStr = "";
	  $cnt = $this->formfield_counter();
	  
	  if(isset($atts['width'])) { $width=$atts['width']; } else { $width = '100%'; }
	  if(isset($atts['height'])) { $height=$atts['height']; } else { $height = '200px'; }
	  if(isset($atts['instructions'])) {
	   $returnStr .=  "<div class='textarea-instructions'>{$atts['instructions']}</div>";
	   }

	  $returnStr .=  "<textarea id='f{$cnt}' name='f{$cnt}' class='textarea response' style='width:{$width};height:{$height};'></textarea>";
	  return $returnStr;
	}



	/**************************
	  TEXT
	***************************/

	function insert_text($atts) {

	  $cnt = $this->formfield_counter();

	  if(isset($atts['width'])) {
		$width = $atts['width'];
		$style="style='width:{$width}px;'";
	  }
	  elseif(isset($atts['size'])) {
		$size = $atts['size'];
		$style="size='{$size};'";
	  }
	  else { $style="style='width:200px'"; }

	  $returnStr =  "<span class='textcontainer'>";

	  if(isset($atts['answer'])) { 
	    $ans_str = addslashes($atts['answer']);
	    $returnStr .=  "    <input type='text' id='f{$cnt}' name='f{$cnt}' class='text response' data-ans='{$ans_str}' style='padding-right:32px;' {$style} />";
	  }
	  elseif(isset($atts['gloss'])) { 

	    $gloss_str = htmlspecialchars($atts['gloss']);
	    $returnStr .=  "    <input type='text' id='f{$cnt}' name='f{$cnt}' class='text response glossed' gls=\"{$gloss_str}\" style='padding-right:32px;' {$style} />";
	  }
	  else {
	    $returnStr .=  "    <input type='text' id='f{$cnt}' name='f{$cnt}' class='text response' style='padding-right:32px;' {$style} />";
	  }
	  $returnStr .=  "</span>";
	  return $returnStr;
	}
	






	/**************************
	  SENTENCE
	***************************/

	function insert_sentence($atts) {
	  $cnt = $this->formfield_counter();

	  if(isset($atts['width'])) {
		$width = $atts['width'];
		$style="style='width:{$width}px'";
	  }

	  elseif(isset($atts['size'])) {
		$size = $atts['size'];
		if($size > 70) { $size = 70; }
		$style="size='{$size}'";
	  }
	 
	  else { $style="style='width:100%'"; }
	  
	  if(isset($atts['answer'])) { 
	    $ans_str = addslashes($atts['answer']);
	    $returnStr =  "<input type='text' id='f{$cnt}' name='f{$cnt}' class='sentence response' {$style} style='display:inline-block' data-ans='{$ans_str}' /> ";
	  }
	  elseif(isset($atts['gloss'])) {
	   $returnStr =  "<input type='text' id='f{$cnt}' name='f{$cnt}' class='sentence response glossed' gls='{$gloss_str}' {$style} style='display:inline-block' /> ";
	  }
	  else {
	    $returnStr =  "<input type='text' id='f{$cnt}' name='f{$cnt}' class='sentence response' {$style} style='display:inline-block' /> ";
	  }
	  
	  return $returnStr;
	}
	


	/**************************
	*  PROCESS CONTENT OF DROPDOWNS, RADIOS, CHECKBOXES
	***************************/

	function processItems($content) {

	  $returnobj = array();
	  $content = strip_tags($content);
	  $content = str_replace(array("<br />","<br/>"),"",trim($content));
	  $content = str_replace('+','*+', $content);
	  $items = preg_split('/\*/', $content, -1, PREG_SPLIT_NO_EMPTY);

	  foreach($items as $key=>$item) {
	    $o = new StdClass();
	    $o->item = trim($item);
	    $o->correct = false;
	    if(strstr($item, '+')) { 
	       $o->correct = true;
	    }
	    $o->item = str_replace("+","",$o->item);
	    $returnobj[] = $o;
	  }
	  return $returnobj;
	}





	/**************************
	  DROPDOWN
	***************************/

	function insert_dropdown($atts, $content) {

	  $cnt = $this->formfield_counter();

	  $items = $this->processItems($content);

	  $returnStr =  "<p class='responsecontainer'><select id='f{$cnt}' name='f{$cnt}' class='dropdown response'>";
	  $returnStr .=  "<option value=''></option>";
	  foreach($items as $item) {
	  
		  $cnt = $this->formfield_counter();

		  if($item->correct) {
		     $returnStr .= "<option value='{$item->item}' data-ans='true'>{$item->item}</option>";
		  }
		  else {
		     $returnStr .= "<option value='{$item->item}'>{$item->item}</option>";
		  }
	  }
	  $returnStr .= "</select></p>";
	  return $returnStr;
	}
	

	/**************************
	  CHECKBOXES
	***************************/

	function insert_checkboxes($atts, $content = null ) {

	  

	  $items = $this->processItems($content);

	  if(strstr($content, '<br')) { $returnStr =  "<p class='responsecontainer'>"; }
	  else { $returnStr =  "<p class='responsecontainer inline'>"; }

	  foreach($items as $item) {
	  
		  $cnt = $this->formfield_counter();

		  if($item->correct) {
		     $returnStr .= "<label for='r{$cnt}' class='checkbox'>
		        <input type='checkbox' id='r{$cnt}' name='r{$cnt}' value='{$item->item}' class='response' data-ans='true'> {$item->item}
		        </label>";
		  }
		  else {
		     $returnStr .= "<label for='r{$cnt}' class='checkbox'>
		        <input type='checkbox' id='r{$cnt}' name='r{$cnt}' value='{$item->item}' class='response'> {$item->item}
		        </label>";
		  } 

	  }
	  $returnStr .= "</p>";
	  return $returnStr;

	}
	

	/**************************
	  RADIO BUTTONS
	***************************/


	function insert_radio($atts, $content = null ) {

	  $cnt = $this->formfield_counter();
	  $items = $this->processItems($content);
	  if(strstr($content, '<br')) { $returnStr =  "<p class='responsecontainer'>"; }
	  else { $returnStr =  "<p class='responsecontainer inline'>"; }

	  foreach($items as $item) {
	  

		  if($item->correct) {
		     $returnStr .= "<label class='radio'>
		        <input type='radio' name='r{$cnt}' value='{$item->item}' class='response' data-ans='true'> {$item->item}
		        </label>";
		  }
		  else {
		     $returnStr .= "<label class='radio'>
		        <input type='radio' name='r{$cnt}' value='{$item->item}' class='response'> {$item->item}
		        </label>";
		  } 

	  }
	  $returnStr .= "</p>";
	  return $returnStr;

	}
	




	/**************************
	  SAVE
	***************************/


	public function insert_save($atts ) {
	  global $post;

	  if(!isset($_COOKIE['masquerade'])) { $user = wp_get_current_user();  }
	  else {  $user = get_user_by( 'login', $_COOKIE['masquerade'] );  }
	  
	  if(isset($atts['title'])) {   $title = $atts['title'];    } else { $title = "Save"; }
	  $returnStr = "<div class='save-button'><button id='langfuncsave' data-user='{$user->user_login}' data-id='{$post->ID}'>{$title}</button></div>";
	  return $returnStr;
	}



} // end class


