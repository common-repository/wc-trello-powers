<?php
include_once($the_path . "include/class-wtp-trello.php");
class Wootrellopowers_Admin {

	private $trello;

	public $boardsLists = array();	

	public $acoes = array(
		'Um novo pedido for criado' => 'woocommerce_new_order',
		'Um pedido mudar o status para processando' => 'woocommerce_order_status_processing',
		'Um pedido mudar o status para concluido' => 'woocommerce_order_status_completed',
		'Um novo review criado' => 'comment_post',
	);

	function __construct() {

		$this->trello = new Wootrellopowers_Trello;

		//ajax
		add_action( 'wp_ajax_conecta_trello', array( $this, 'wtp_conecta_trello' ) );
		add_action( 'wp_ajax_load_trigger', array( $this, 'wtp_load_trigger' ) );


		add_action( 'admin_menu', array( $this, 'wtp_settings_add_menu' ) );
		
	
	}

	function wtp_settings_add_menu (){
		add_menu_page( 'Woo & Trello Powers', 'Woo & Trello Powers', 'manage_options', 'wootrellopowers', array( $this, 'wtp_settings_startview' ));		
	}

	function wtp_settings_startview (){
		if ($_POST) {
			$this->wtp_set_triggers();
		}
		wp_enqueue_script(  'wtp-trello-token', plugin_dir_url( __FILE__ ) . 'js/script.js', array('jquery'), rand(0,1000), true );
		wp_enqueue_style( 'wtp-trello-token', plugin_dir_url( __FILE__ ) . 'css/style.css', '', rand(0,1000), false );
		$this->wtp_load_boardsLists();
		require_once plugin_dir_path(dirname(__FILE__)).'admin/views/wtp-settings-startview.php';

	}

	function wtp_conecta_trello (){
		$up = update_option('wtp_key_code', sanitize_text_field( trim($_POST['wtp_key']) ),FALSE);
		if (!$up) {
			$up = add_option('wtp_key_code', sanitize_text_field( trim($_POST['wtp_key']) ));# code...
		}
		$up = update_option('wtp_access_code', sanitize_text_field( trim($_POST['wtp_token']) ),FALSE);
		if (!$up) {
			$up = add_option('wtp_access_code', sanitize_text_field( trim($_POST['wtp_token']) ));# code...
		}
		$this->trello->setToken();
		if ($this->trello->testaConexao()) {
		 	echo "true";
		} else{
			echo 'false';		
		}
		exit();
	}

	function wtp_load_boardsLists (){
		$cacheBoardList =  get_option( 'wtp_cacheBoardsLists ', '' );
		if ($cacheBoardList != '') {
			$cacheBoardList =  json_decode($cacheBoardList);
			if ($cacheBoardList[0] >= date("Ymd") ) {
				$this->boardsLists = $cacheBoardList[1];
				return;
			}
		}
		$boards = $this->trello->getBoards();
		if($boards===false){ return false; }
		$boardsLists = array();
		foreach ($boards as $b) {
			$boardLists = $this->trello->getBoardList($b->id);
			if ($boardLists !== FALSE) {
				foreach ($boardLists as $list) {
					$boardsLists[$b->name][$list->id] = $list->name;
				}
			}
		}
		$this->boardsLists = $boardsLists;
		$boardsLists = array(0 => date("Ymd", strtotime('+7 days')), 1 => $boardsLists );
		$boardsLists = json_encode($boardsLists);
		$up = update_option('wtp_cacheBoardsLists', $boardsLists,FALSE);
		if (!$up) {
			$up = add_option('wtp_cacheBoardsLists', $boardsLists);
		}
	}

	function wtp_load_trigger (){
		echo get_option( 'wtp_triggers', '' );
		exit();
	}

	function wtp_set_triggers (){
		if (isset($_POST['acao']) and isset($_POST['lista'])) {
			$triggers = array();
			foreach ($_POST['acao'] as $key => $value) {
				$triggers[] = array($value , sanitize_text_field(trim($_POST['lista'][$key])));
			}
			$triggers = json_encode($triggers);
			$up = update_option('wtp_triggers', $triggers,FALSE);
			if (!$up) {
				$up = add_option('wtp_triggers', $triggers);
			}
		}
	}


}