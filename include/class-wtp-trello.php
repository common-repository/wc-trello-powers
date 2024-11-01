<?php
class Wootrellopowers_Trello {
	private $apikey;
	private $token;
 	private $urlBase;

 	public function __construct(){
		$this->urlBase = 'https://api.trello.com/1/';
 		$this->setKey();
 		$this->setToken();
 		
 	}

 	public function setKey(){
 		$this->apikey = get_option( 'wtp_key_code', '' );
 	}

 	public function setToken(){
 		$this->token = get_option( 'wtp_access_code', '' );
 	}

 	public function testaConexao(){
 		$resp = $this->getBoards();
 		if ($resp === FALSE) {
 			return false;
 		}
 		return true;
 	}

 	public function getBoards(){
 		$fields = '?a=a';
 		$url = 'members/me/boards'.$fields;
 		$resposta = $this->sendCurl($url);
 		$resposta[1] = json_decode($resposta[1]);
 		if (empty($resposta[0]) and json_last_error() === 0 ) {
 			return $resposta[1];
 		}
 		return false;
 	}

 	public function getBoardList($id){
 		$url = 'boards/'.$id.'/lists?a=a';
 		$resposta = $this->sendCurl($url);
 		$resposta[1] = json_decode($resposta[1]);
 		if (empty($resposta[0]) and json_last_error() === 0 ) {
 			return $resposta[1];
 		}
 		return false;
 	}

 	public function getCardbyId($id){
 		$fields = '?fields=name,desc,idList,idChecklists';
 		$url = 'cards/'.$id.$fields;
 		$resposta = $this->sendCurl($url);
 		$resposta = json_decode($resposta);
 		if (empty($resposta[0]) and json_last_error() === 0 ) {
 			return $resposta[1];
 		}
 		return false;
 	}

 	public function getCardsbyListId($id){
 		$fields = '?a=a';
 		$resposta = $this->sendCurl($url);
 		$resposta = json_decode($resposta);
 		if (empty($resposta[0]) and json_last_error() === 0 ) {
 			return $resposta[1];
 		}
 		return false;
 	}

 	public function creatCard($parsArr){
 		if (!is_array($parsArr) or !isset($parsArr['name']) or !isset($parsArr['idList'])) {
 			return FALSE;
 		}
 		$url = 'cards?'.http_build_query($parsArr);
 		$newCard = $this->sendCurl($url, $pars, 'POST');
 		if (!empty($newCard[0]) ) {
 			return FALSE;
 		}
 		return json_decode($newCard[1]);
 	}
 	
 	public function creatCheckList($parsArr){
 		if (!is_array($parsArr) or !isset($parsArr['name']) or !isset($parsArr['idCard'])) {
 			return FALSE;
 		}
 		$url = 'checklists?'.http_build_query($parsArr);
 		$new = $this->sendCurl($url, $pars, 'POST');
 		if (!empty($new[0]) ) {
 			return FALSE;
 		}
 		return json_decode($new[1]);
 	}

 	public function creatCheckListItem($parsArr){
 		if (!is_array($parsArr) or !isset($parsArr['name']) or !isset($parsArr['id'])) {
 			return FALSE;
 		}
 		$url = 'checklists/'.$parsArr['id'].'/checkItems?'.http_build_query($parsArr);
 		$new = $this->sendCurl($url, $pars, 'POST');
 		if (!empty($new[0]) ) {
 			return FALSE;
 		}
 		return json_decode($new[1]);
 	}

 	public function creatAttachments($parsArr){
 		if (!is_array($parsArr) or !isset($parsArr['name']) or !isset($parsArr['url']) or !isset($parsArr['id'])) {
 			return FALSE;
 		}
 		$url = 'cards/'.$parsArr['id'].'/attachments?'.http_build_query($parsArr);
 		$new = $this->sendCurl($url, $pars, 'POST');
 		if (!empty($new[0]) ) {
 			return FALSE;
 		}
 		return json_decode($new[1]);
 	}


 	private function sendCurl($url = '', $pars = '', $method = 'GET'){
 		$accessURL = '&key='.$this->apikey.'&token='.$this->token;
 		$url = $this->urlBase.$url.$accessURL;
 		switch ($method) {
 			case 'POST':
 				$args = array(
	                'timeout' => 30,
	                'body'    => $pars
	            );
 				$response = wp_remote_post($url);
 				break;
 			case 'PUT':
 				$args = array(
 					'method'      => 'PUT',
	                'timeout' => 30,
	                'body'    => $pars
	            );
 				$response = wp_remote_post($url);
 				break;
 			case 'GET':
 				$response = wp_remote_get($url);
 				break;
 		}
 		if ( !is_wp_error( $response ) && isset( $response['response']['code'] ) && $response['response']['code'] == 200 ) {
            $error = '';
            $output = $response['body'];
        } else {
            $error = $response['response']['code'];
        }
        return array($error, $output);

 	}
}
?>