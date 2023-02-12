<?php
/**
 * Plugin Name: FaceLog Plugin
 * Plugin URI: http://boscdelacoma.cat
 * Description: Pràctica MP07.
 * Version: 0.1
 * Author: ELTEUNOM
 * Author URI:  http://boscdelacoma.cat
 **/

 const FACELOG_DB_VERSION = '1.0';
 const FACELOG_VERSION= '1.0';
 
 // Allow subscribers to see Private posts and pages
 $subRole = get_role( 'subscriber' );
 $subRole->add_cap( 'read_private_posts' );
 $subRole->add_cap( 'read_private_pages' );
 

 function facelog_example(){
    return "Hola a tothom!";
 }

 add_shortcode('facelog', 'facelog_example');
