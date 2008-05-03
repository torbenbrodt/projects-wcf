<h3 class="subHeadline"><img src="{@RELATIVE_WCF_DIR}icon/projectsLicenseM.png" alt="" /> {lang}wcf.user.userGroups.leader.licensing{/lang}</h3>

<form method="post" action="index.php?action=UserGroupAdministrateLicense">
	<fieldset>
		<legend>{lang}wcf.user.userGroups.leader.administrate.licenses.add{/lang}</legend>
	
		<div class="formElement">
			<div class="formFieldLabel">
				<label for="licenses">{lang}wcf.user.userGroups.leader.administrate.licenses{/lang}</label>
			</div>
				
			<div class="formField">
				<input type="text" class="inputText" name="licenseAddString" value="" id="licenseAddString" />
				<script type="text/javascript">
					//<![CDATA[
					suggestion.setSource('index.php?page=PublicLicenseSuggest{@SID_ARG_2ND_NOT_ENCODED}');
					suggestion.init('licenseAddString');
					//]]>
				</script>
			</div>
			<div class="formFieldDesc">
				<p>{lang}wcf.user.userGroups.leader.administrate.licenses.description{/lang}</p>
			</div>
		</div>
		
		<div class="formSubmit">
			<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
			<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
			<input type="hidden" name="groupID" value="{@$groupID}" />
			{@SID_INPUT_TAG}
		</div>
	</fieldset>
</form>

{if $licenses|count > 0}
	<form method="post" action="index.php?action=UserGroupAdministrateLicense">
		<fieldset>
			<legend>{lang}wcf.user.userGroups.leader.administrate.licensesInGroup{/lang}</legend>
			<ul>
				{foreach from=$licenses item=license}
					<li>
						<input type="checkbox" name="licenseRemoveIDs[]" value="{$license['licenseID']}" />
						<a href="{$license['licenseURL']}">{$license['licenseName']}</a>
					</li>
				{/foreach}
			</ul>
		</fieldset>
		
		<div class="formSubmit">
			<input type="submit" accesskey="r" value="{lang}wcf.user.userGroups.leader.administrate.licenses.remove{/lang}" />
			<input type="hidden" name="groupID" value="{@$groupID}" />
			{@SID_INPUT_TAG}
		</div>
	</form>
{/if}
