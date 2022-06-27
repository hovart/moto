<table>
	<tr>
		<td style="text-align: center; font-size: 6pt; color: #444">
            {if $available_in_your_account}
                {l s='An electronic version of this invoice is available in your account. To access it, log in to our website using your e-mail address and password (which you created when placing your first order).' pdf='true'}             
    			<br />
            {/if}
			{$shop_address|escape:'htmlall':'UTF-8'}
            
            {if isset($shop_details)}
                - {$shop_details|escape:'htmlall':'UTF-8'}<br />
			{else}
				<br />
            {/if}
			
			{if !empty($shop_phone) OR !empty($shop_fax)}
				{l s='For more assistance, contact Support:' pdf='true'}
				{if !empty($shop_phone)}
					Tel: {$shop_phone|escape:'htmlall':'UTF-8'}
				{/if}
				{if !empty($shop_fax)}
					Fax: {$shop_fax|escape:'htmlall':'UTF-8'}
				{/if}
			{/if}

            {if isset($free_text)}
    			<br />{$free_text|escape:'htmlall':'UTF-8'}
            {/if}
		</td>
	</tr>
</table>