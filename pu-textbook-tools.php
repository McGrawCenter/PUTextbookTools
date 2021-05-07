<?php
/*
	Plugin Name: PU Textbook Tools
	Plugin URI: https://github.com/McGrawCenter/PUTextbookTools
	Description: Adds text, checkbox, radio button, and textarea fields for the creation of online textbooks/workbooks. Please note: this plugin requires some custom work to enable storage of student responses. Please contact Ben Johnston (benj@princeton.edu) for more information.
	Version: 1.0
	Author: Ben Johnston
*/




class PUTextbookTools {


    function __construct() {
       register_activation_hook( __FILE__,  	array( $this, 'create_db_table' ) );
       add_action( 'wp_enqueue_scripts', 	array( $this, 'enqueue_scripts' ) );
       add_filter( 'the_content', 		array( $this, 'content_filter' ) );
       add_action( 'wp_ajax_get_responses', 	array ($this, 'ajax_get_responses' ) );
       add_action( 'wp_ajax_save_responses', 	array ($this, 'ajax_save_responses' ) );
    }



	function enqueue_scripts()
	{
	  wp_register_style('language-tools-css', plugins_url('css/style.css',__FILE__ ));
	  wp_enqueue_style('language-tools-css');

	  wp_register_script('language-tools-js', plugins_url('/js/script.js', __FILE__), array('jquery'),'1.1', false);
	  wp_enqueue_script('language-tools-js');
	  wp_localize_script('language-tools-js', 'responsevars', 
	    array( 
	      'ajaxurl' => admin_url( 'admin-ajax.php' ), 
	      'pluginurl' => plugin_dir_url( __FILE__ ),
	      'userid' => get_current_user(),
	      'postid' => get_the_ID(),
	      'permalink' => get_permalink(get_the_ID())
	    )
	  );

	  wp_enqueue_style( 'dashicons' );

	}




	/*******************************************
	* If plugin is being activated, create db table
	*******************************************/
	function create_db_table() {
	     global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'responses';
		
		// if table name doesn't exist
		if(!$wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {

			$sql = "CREATE TABLE ".$table_name." (
			    ID int NOT NULL AUTO_INCREMENT,
			    userid varchar(120) NOT NULL,
			    postid varchar(120) NOT NULL,
			    data text NOT NULL,	    
			    initial_save varchar(50) NOT NULL,
			    last_save varchar(50) NOT NULL,
			    UNIQUE (ID)
			) ". $charset_collate.";";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

		}
	}




	/*******************************************
	* Check if current post/page has our shortcodes
	*******************************************/
	function content_filter($content) {
	  if( strstr($content,'[text') || strstr($content,'[sentence') || strstr($content,'[radio') || strstr($content,'[checkboxes') || strstr($content,'[dropdown') ) { 
	    $content = "<form class='responseform'>\n" . $content . "\n\n[save]\n</form>";
	  }
	  return $content;
	}
	



	/*******************************************
	* AJAX functions: send and recieve
	*******************************************/
	/****************************************
	* set responses
	****************************************/

	function ajax_save_responses() {
	
		if(isset($_POST) && count($_POST) > 1) {
		
			

			global $wpdb; 
			date_default_timezone_set('US/Eastern');

			$userid = $_POST['userid'];
			$postid = $_POST['postid'];
			$data = $_POST['data'];

			$sql1 = "SELECT * FROM {$wpdb->prefix}responses WHERE userid = '{$userid}' AND postid = '{$postid}'";

			if($hit = $wpdb->get_row($sql1)) {  
			  $id = $hit->ID;
			  $current_save = date("Y-m-d h:i:s a");
			  $sql2 = "UPDATE {$wpdb->prefix}responses SET data = '{$data}', last_save = '{$current_save}' WHERE ID = ".$id;
			}
			else { 
			  $initial_save = date("Y-m-d h:i:s a");
			  $sql2 = "INSERT into {$wpdb->prefix}responses VALUES ('','{$userid}','{$postid}','{$data}','{$initial_save}','{$initial_save}');";
			}

			$wpdb->query($sql2);
		
			die('saved '.date("G:i:s"));

			wp_die('saved'); // this is required to terminate immediately and return a proper response
		}
	}

	



	/****************************************
	* get responses
	****************************************/

	function ajax_get_responses() {
		global $wpdb;
		$userid = $_GET['userid'];
		$postid = $_GET['postid'];
		$sql = "SELECT data FROM {$wpdb->prefix}responses WHERE userid = '{$userid}' AND postid = '{$postid}'";
		if($result = $wpdb->get_row($sql)) {
		  header('Content-Type: application/json');
		  echo $result->data;
		  wp_die();
		}
		else { 
		  $data = array();
		  json_encode($data);
		  wp_die();
		}
	}












} // end class

new PUTextbookTools();
include('lib/shortcodes.php');
new PUTextbookToolsShortcodes();



