<div class="formElement" id="projectDjangoDiv">
	<div class="formField">
		<label><input type="checkbox" name="projectDjango" id="projectDjango" value="1" {if $projectDjango == 1}checked="checked" {/if}/> {lang}wcf.acp.group.projectDjango{/lang}</label>
	</div>
	<div class="formFieldDesc hidden" id="projectDjangoHelpMessage">
		{lang}wcf.acp.group.projectDjango.description{/lang}
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
projectOptions.push('projectDjango');
//]]>
</script>
