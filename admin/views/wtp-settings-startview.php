<div class="wrap metabox-holder">
	<h2>WooTrello Powers - Configurações</h2>
	<div class="row postbox">
		<form id="form-token" action="#">
			<div class="col m3 s12">
				<p>Códigos de acesso do Trello: <br />
					<small>Saiba como pegar a key e o token <a target="_blank" href="http://felipepeixoto.tecnologia.ws/dicas/como-obter-a-key-e-token-de-um-usuario-no-trello/">aqui</a></small>
				</p>
			</div>	
			<div class="col m9 s12">
				<p id="tokenInput">
					<input size="40" autocomplete="no" id="key-input" placeholder="KEY" name="key" value="<?php echo get_option( 'wtp_key_code' ); ?>" type="text">  
					<input autocomplete="no" id="token-input" name="token" value="<?php echo get_option( 'wtp_access_code' ); ?>" type="password">
					<button class="button button-primary" type="submit" value="Testar">Testar</button>
				</p>				
			</div>	
		</form>
	</div>
	<div class="row postbox">
		<form action="" method="post">
			<h2 class="hndle"><span>Criar Card Quando ...</span></h2>
			<div id="painel" class="col s12">
				<table id="tabela-acoes" class="wp-list-table widefat fixed striped margin-top-bottom15">
					<thead>
						<tr>
							<th width="10">#</th>
							<th>Quando</th>
							<th>Onde</th>
							<th width="30"></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>
								<select class="selectacao" name="acao[]">
									<option>Escolha a Ação</option>
									<?php foreach ($this->acoes as $key => $value): ?>
									<option value="<?php echo $value ?>"><?php echo $key; ?></option>
									<?php endforeach ?>
								</select>
							</td>
							<td>
								<select class="selectlista" name="lista[]">
									<option disabled="disabled">Escolha a lista alvo</option>
									<?php foreach ($this->boardsLists as $boardKey => $boardValue): ?>
									<optgroup label="<?php echo $boardKey ?>">
										<?php foreach ($boardValue as $listKey => $listValue): ?>
										<option value="<?php echo $listKey ?>"><?php echo $listValue; ?></option>
										<?php endforeach ?>
									</optgroup>
									<?php endforeach ?>
								</select>
							</td>
							<td class="acao-linha"><a href="#"><span class="dashicons dashicons-no"></span></a></td>
						</tr>
					</tbody>
				</table>
				<div class="text-right"><button id="btn-adicionar-linha" class="margin-top-bottom15 button button-primary" >+ Adicionar</button></div>
			</div>	
			<input type="submit" class="margin-top-bottom15 button button-primary" value="Salvar" />
		</form>
	</div>
	<div class="footer">
		<p>
			Encontrou algum bug ou quer fazer um contário? <a href="https://wordpress.org/support/plugin/wc-trello-powers/" target="_blank">Entre em contato aqui</a> Gostou do plugin? Considere dar 5 estrelas em uma avaliação no <a href="https://wordpress.org/support/plugin/wc-trello-powers/reviews/#new-post" target="_blank">wordpress.org</a>. Obrigado! :)
		</p>
	</div>
	<input id="pluginurl" type="hidden" value="<?php echo plugin_dir_url( dirname( __FILE__ ) ); ?>">
	<input id="ajaxurl" type="hidden" value="<?php echo admin_url('admin-ajax.php'); ?>">
</div>