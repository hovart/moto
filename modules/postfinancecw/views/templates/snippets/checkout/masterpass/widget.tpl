<div class="postfinance-masterpass-widget">
	<a href="{$redirectUrl}"> 
	<img src="https://www.mastercard.com/mc_us/wallet/img/{$languageCode}/{$countryCode}/mcpp_wllt_btn_chk_180x042px.png"
		alt="{$altText}" />
	</a>
	<div clsss="learn-more-text" style="margin-top: 5px;"><a target="_blank" href="https://www.mastercard.com/mc_us/wallet/learnmore/{$languageCode}/{$countryCode}"  data-toggle="cw-modal" data-modal-dismiss="true" data-modal-css="postfinance-modal-masterpass-learnmore">{$learnMoreText}</a></div>
</div>

<style type="text/css">
{$modalCss}

{literal}
@media (min-width: 774px) {
	.postfinance-modal-masterpass-learnmore {
		width: 704px;
		left: 50%;
		margin-left: -351px;
	}
}
{/literal}
</style>
<script type="text/javascript">
{$modalJavascript}
</script>