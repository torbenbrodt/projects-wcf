<div class="formElement" id="projectRailsDiv">
	<div class="formField">
		<label><input type="checkbox" name="projectRails" id="projectRails" value="1" {if $projectRails == 1}checked="checked" {/if}/> {lang}wcf.acp.group.projectRails{/lang}</label>
	</div>
	<div class="formFieldDesc hidden" id="projectRailsHelpMessage">
		{lang}wcf.acp.group.projectRails.description{/lang}
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
projectOptions.push('projectRails');
//]]>
</script>
