</fieldset>
<fieldset>
<legend>{lang}wcf.acp.group.projectSettings{/lang}</legend>
<script type="text/javascript">
//<![CDATA[
var projectOptions = new Array('projectShortName','projectWebsite','projectSvn','projectIntern','projectTrac');
//]]>
</script>
<div class="formElement">
	<div class="formField">
		<label><input onclick="if (this.checked) enableOptions('projectShortName','projectWebsite','projectSvn','projectIntern'); else disableOptions('projectShortName','projectWebsite','projectSvn','projectIntern')" type="checkbox" name="project" id="project" value="1" {if $project == 1}checked="checked" {/if}/> {lang}wcf.acp.group.project{/lang}</label>
	</div>
</div>

<div class="formElement" id="projectShortNameDiv">
	<div class="formFieldLabel">
		<label for="projectShortName">{lang}wcf.acp.group.projectShortName{/lang}</label>
	</div>
	<div class="formField">
		<input type="text" class="inputText" id="projectShortName" name="projectShortName" value="{@$projectShortName}" />
	</div>
	<div class="formFieldDesc hidden" id="projectShortNameHelpMessage">
		{lang}wcf.acp.group.projectShortName.description{/lang}
	</div>
</div>
<div class="formElement" id="projectWebsiteDiv">
	<div class="formFieldLabel">
		<label for="projectWebsite">{lang}wcf.acp.group.projectWebsite{/lang}</label>
	</div>
	<div class="formField">
		<input type="text" class="inputText" id="projectWebsite" name="projectWebsite" value="{@$projectWebsite}" />
	</div>
	<div class="formFieldDesc hidden" id="projectWebsiteHelpMessage">
		{lang}wcf.acp.group.projectWebsite.description{/lang}
	</div>
</div>
<div class="formElement" id="projectSvnDiv">
	<div class="formFieldLabel">
		<label for="projectSvn">{lang}wcf.acp.group.projectSvn{/lang}</label>
	</div>
	<div class="formField">
		<input type="text" class="inputText" id="projectSvn" name="projectSvn" value="{@$projectSvn}" />
	</div>
	<div class="formFieldDesc hidden" id="projectSvnHelpMessage">
		{lang}wcf.acp.group.projectSvn.description{/lang}
	</div>
</div>
<div class="formElement" id="projectInternDiv">
	<div class="formField">
		<label><input type="checkbox" name="projectIntern" id="projectIntern" value="1" {if $projectIntern == 1}checked="checked" {/if}/> {lang}wcf.acp.group.projectIntern{/lang}</label>
	</div>
	<div class="formFieldDesc hidden" id="projectInternHelpMessage">
		{lang}wcf.acp.group.projectIntern.description{/lang}
	</div>
</div>
<div class="formElement" id="projectTracDiv">
	<div class="formField">
		<label><input type="checkbox" name="projectTrac" id="projectTrac" value="1" {if $projectTrac == 1}checked="checked" {/if}/> {lang}wcf.acp.group.projectTrac{/lang}</label>
	</div>
	<div class="formFieldDesc hidden" id="projectTracHelpMessage">
		{lang}wcf.acp.group.projectTrac.description{/lang}
	</div>
</div>
{if $additionalProjectOptions|isset}{@$additionalProjectOptions}{/if}

<script type="text/javascript">
	//<![CDATA[
	for(var i=0; i<projectOptions.length; i++) {
		inlineHelp.register(projectOptions[i]);
	}
	
	onloadEvents.push(function() { if ({$project}) enableOptions('projectShortName','projectWebsite','projectSvn','projectIntern','projectTrac'); else disableOptions('projectShortName','projectWebsite','projectSvn','projectIntern','projectTrac') });
	//]]>
</script>

</fieldset>
