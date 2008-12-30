<div class="formElement" id="projectGlassfishDiv">
	<div class="formField">
		<label><input type="checkbox" name="projectGlassfish" id="projectGlassfish" value="1" {if $projectGlassfish == 1}checked="checked" {/if}/> {lang}wcf.acp.group.projectGlassfish{/lang}</label>
	</div>
	<div class="formFieldDesc hidden" id="projectGlassfishHelpMessage">
		{lang}wcf.acp.group.projectGlassfish.description{/lang}
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
projectOptions.push('projectGlassfish');
//]]>
</script>
