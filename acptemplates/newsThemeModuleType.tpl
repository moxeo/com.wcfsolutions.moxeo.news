<fieldset>
	<legend>{lang}moxeo.acp.theme.module.news.data{/lang}</legend>

	<div class="formElement{if $errorField == 'newsArchiveIDs'} formError{/if}" id="newsArchiveIDsDiv">
		<div class="formFieldLabel">
			<label for="newsArchiveIDs">{lang}moxeo.acp.theme.module.news.newsArchiveIDs{/lang}</label>
		</div>
		<div class="formField">
			<select name="newsArchiveIDs[]" id="newsArchiveIDs" multiple="multiple" size="5">
				{htmloptions options=$newsArchiveOptions selected=$newsArchiveIDs}
			</select>
			{if $errorField == 'newsArchiveIDs'}
				<p class="innerError">
					{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
				</p>
			{/if}
		</div>
		<div class="formFieldDesc hidden" id="newsArchiveIDsHelpMessage">
			<p>{lang}moxeo.acp.theme.module.news.newsArchiveIDs.description{/lang}</p>
			<p>{lang}wcf.global.multiSelect{/lang}</p>
		</div>
	</div>
	<script type="text/javascript">//<![CDATA[
		inlineHelp.register('newsArchiveIDs');
	//]]></script>

	<div class="formElement" id="newsItemsPerPageDiv">
		<div class="formFieldLabel">
			<label for="newsItemsPerPage">{lang}moxeo.acp.theme.module.news.newsItemsPerPage{/lang}</label>
		</div>
		<div class="formField">
			<input type="text" class="inputText" id="newsItemsPerPage" name="newsItemsPerPage" value="{@$newsItemsPerPage}" />
		</div>
		<div class="formFieldDesc hidden" id="newsItemsPerPageHelpMessage">
			<p>{lang}moxeo.acp.theme.module.news.newsItemsPerPage.description{/lang}</p>
		</div>
	</div>
	<script type="text/javascript">//<![CDATA[
		inlineHelp.register('newsItemsPerPage');
	//]]></script>
</fieldset>