<?php 
/**
 * Plugin Name: Powers Triggers for	Woocommerce and Trello 
 * Plugin URI:	
 * Description:	Cria gatilhos para interação com o Woocommerce e trello
 * Version:		1.0.2
 * Author:		Felipe Peixoto
 * Author URI:	
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}
$the_path = plugin_dir_path(__FILE__);
if (is_admin()){
	require plugin_dir_path( __FILE__ ) . 'admin/class-wtp-admin.php';
	$settings = new Wootrellopowers_Admin();
}
require plugin_dir_path( __FILE__ ) . 'class-wtp-front.php';
new Wootrellopowers_Front();
?>