<div class="box">
	<p class="cheque-indent">
		{if $result eq 'success'}
			<strong class="dark">{l s='Su orden on %s fue completada con exito' sprintf=$shop_name mod='vposintegration'}</strong>
		{/if}
		{if $result eq 'error'}
			<strong class="dark">{l s='Su orden on %s no pudo ser completada' sprintf=$shop_name mod='vposintegration'}</strong>
		{/if}

	</p><br>
</div>
