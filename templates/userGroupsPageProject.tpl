<h3 class="subHeadline"><img src="{@RELATIVE_WCF_DIR}icon/projectsM.png" alt="" /> {lang}wcf.projects.log{/lang}</h3>

<form method="post" action="index.php?action=UserGroupsLog">
	<fieldset>
		<legend>{lang}wcf.projects.log{/lang}</legend>
	
		<div class="formElement">
			<div class="formField">
				<textarea rows="12" cols="40">{$log}</textarea>
			</div>
			<div class="formFieldDesc">
				<p>{lang}wcf.projects.log.description{/lang}</p>
			</div>
		</div>
		
		{if $this->user->getPermission('admin.user.canDeleteProjectLog')}
		<div class="formSubmit">
			<input type="submit" accesskey="s" value="{lang}"wcf.projects.log.delete{/lang}" />
			{@SID_INPUT_TAG}
		</div>
		{/if}
	</fieldset>
</form>
