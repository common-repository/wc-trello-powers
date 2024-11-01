<?php
include_once($the_path . "include/class-wtp-trello.php"); 
class Wootrellopowers_Front {
	private $trello;
	function __construct() {
		$this->trello = new Wootrellopowers_Trello;
		$this->wtp_load_action();

	}

	private function wtp_load_action() {
		$triggers = get_option( 'wtp_triggers', '' );
		if ($triggers !='') {
			$triggers = json_decode($triggers);
			foreach ($triggers as $trigger) {
				$listID = $trigger[1];
				switch ($trigger[0]) {
					case 'woocommerce_new_order':
					case 'woocommerce_order_status_completed':
					case 'woocommerce_order_status_processing':
						add_action( $trigger[0], function ($orderId) use ($listID) {
							$this->wtp_cria_card_order($listID,$orderId);
						}, 10 ,1 );
					break;
					case 'comment_post':
						add_action( $trigger[0], function ($comment_ID, $comment_approved, $commentdata) use ($listID) {
							$this->wtp_cria_card_comment($listID,$comment_ID, $comment_approved, $commentdata);
						}, 10 ,3);
					break;
				}
				
			}

		}
	}

	public function wtp_cria_card_order($listId,$orderId) {
		$order = wc_get_order($orderId);
		$nome = '#'.$orderId.' '.$order->billing_last_name.' '.$order->get_date_created()->format("d/m/Y");
		$itens = $order->get_items();
		$descricao = '**Nome: ** '.$order->billing_first_name.' '.$order->billing_last_name." \n";
		$descricao .= '**Endereço de Cobrança: ** '.$order->get_billing_first_name().' - '.$order->get_billing_address_1().', '.$order->get_billing_address_2().' - '.$order->get_billing_city().', '.$order->get_billing_state()." \n";
		$descricao .= '**Endereço de Entrega: ** '.$order->get_shipping_first_name().' - '.$order->get_shipping_address_1().', '.$order->get_shipping_address_2().' - '.$order->get_shipping_city().', '.$order->get_shipping_state()." \n";
		$descricao .= '**Nota do Pedido: ** '.$order->get_customer_note()." \n";
		$descricao .= "\n\n";
		$descricao .= '**Forma de Pagamento: ** '.$order->get_payment_method()." \n";
		$descricao .= '**Total: ** '.$order->get_total()." \n";
		$parsArr = array(
			'name' => $nome, 
			'desc' => $descricao, 
			'idList' => $listId,
			'keepFromSource' => 'all',
		);
		$newCard = $this->trello->creatCard($parsArr);
		if ($newCard !== FALSE) {
			$parsArr = array(
				'name' => 'Itens do Pedido',
				'idCard' => $newCard->id,
				'pos' => 'top',
			);
			$newCheck = $this->trello->creatCheckList($parsArr);
			if ($newCheck !== FALSE) {
				foreach ($itens as $item) {
					$parsArr = array(
						'name' => $item['name'],
						'id' => $newCheck->id,
						'pos' => 'top',
					);
					$this->trello->creatCheckListItem($parsArr);
				}
			}
		}
	}
	public function wtp_cria_card_comment($listId,$comment_ID, $comment_approved, $commentdata) {
		if (!is_array($commentdata) or $commentdata['comment_type'] != 'review') {
			return false;
		}
		$comment_approved = $comment_approved==1 ? 'Aprovado' : 'Pendente';
		$authorObj = get_user_by('id', $commentdata['user_ID']);
		$nome = 'Novo comentário de '. $commentdata['comment_author'].' - '.date("d/m/Y",strtotime($commentdata['comment_date']));
		$descricao = '';
		if ($authorObj === false) {
			$descricao .= '**Autor: ** '.$commentdata['comment_author']." \n";
		} else {
			$descricao .= '**Autor: ** '.$authorObj->display_name." \n";
		}

		$descricao .= '**Conteúdo: ** '."\n".$commentdata['comment_content']." \n";
		$descricao .= '**Status: ** '. $comment_approved ." \n";
		$parsArr = array(
			'name' => $nome, 
			'desc' => $descricao, 
			'idList' => $listId,
			'keepFromSource' => 'all',
		);
		$newCard = $this->trello->creatCard($parsArr);
		if ($newCard !== FALSE) {
			$postUrl = get_permalink($commentdata['comment_post_ID']);
			$parsArr = array(
				'id'=>$newCard->id,
				'name'=>'Produto',
				'url'=>$postUrl
			);
			$newAttachments = $this->trello->creatAttachments($parsArr);
		}
	}
}