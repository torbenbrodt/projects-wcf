{include file="documentHeader"}
<head>
	<title>{$general.groupName} - {lang}wcf.projects.title{/lang} - {PAGE_TITLE}</title>

	{include file='headInclude' sandbox=false}
</head>
<body>
{include file='header' sandbox=false}

<div id="main">
	
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="icon/indexS.png" alt="" /> <span>{PAGE_TITLE}</span></a> &raquo;</li>
		<li><a href="index.php?page=ProjectList"><img src="wcf/icon/projectsS.png" alt="" /> <span>{lang}wcf.projects.title{/lang}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<img src="{@RELATIVE_WCF_DIR}icon/projectsL.png" alt="" />
		<div class="headlineContainer">
			<h2>{$general.groupName}</h2>
			<p>{$general.projectShortName}</p>
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}
	
	<div class="border content">
		<div class="container-1">
			<div class="formFieldLabel">
				<label>{lang}wcf.projects.projectWebsite{/lang}</label>
			</div>
			<div class="formField">
				<a href="{$general.projectWebsite}" style="padding:10px">{$general.projectWebsite}</a>
			</div>

			<div class="formFieldLabel">
				<label>{lang}wcf.projects.memberCount{/lang}</label>
			</div>
			<div class="formField">
				<span style="padding:10px">{$general.memberCount}</span>
			</div>
{if $general.firstActivity}
			<div class="formFieldLabel">
				<label>{lang}wcf.projects.firstActivity{/lang}</label>
			</div>
			<div class="formField">
				<span style="padding:10px">{'d.m.Y H:i'|gmdate:$general.firstActivity}</span>
			</div>
{/if}
{if $general.lastActivity}
			<div class="formFieldLabel">
				<label>{lang}wcf.projects.lastActivity{/lang}</label>
			</div>
			<div class="formField">
				<span style="padding:10px">{'d.m.Y H:i'|gmdate:$general.lastActivity}</span>
			</div>
{/if}
			<div class="formFieldLabel">
				<label>{lang}wcf.projects.revisionCount{/lang}</label>
			</div>
			<div class="formField">
				<span style="padding:10px">{$general.revisionCount}</span>
			</div>
	
	{if $members|count > 0}
	<fieldset>
		<legend><img src="{@RELATIVE_WCF_DIR}icon/membersM.png" alt="" /> {lang}wcf.projects.members{/lang}</legend>
		<table class="tableList">
		<thead>
		<tr class="tableHead">
			<th><div style="padding:5px">{lang}wcf.projects.members{/lang}</div></th>
			<th><div style="padding:5px">{lang}wcf.projects.revisions{/lang}</div></th>
		</tr>
		</thead>
		<tbody>
		{foreach from=$members item=member}
			<tr class="container-{cycle values='1,2'}">
				<td>
					<a href="index.php?page=User&amp;userID={$member.userID}">{$member.username}</a>
					{if $member.isLeader}<p>{lang}wcf.projects.leading{/lang}</p>{/if}
				</td>
				<td>{$member.commits}</td>
			</tr>
		{/foreach}
		</tbody>
		</table>
	</fieldset>
	{/if}
	
	{if $licenses|count > 0}
	<fieldset>
		<legend><img src="{@RELATIVE_WCF_DIR}icon/projectsLicenseM.png" alt="" /> {lang}wcf.projects.licenses{/lang}</legend>
		<table class="tableList">
		<thead>
		<tr class="tableHead">
			<th><div style="padding:5px">{lang}wcf.projects.licenses{/lang}</div></th>
		</tr>
		</thead>
		<tbody>
		{foreach from=$licenses item=license}
			<tr class="container-{cycle values='1,2'}">
				<td>
					<a href="{$license.licenseURL}">{$license.licenseName}</a>
				</td>
			</tr>
		{/foreach}
		</tbody>
		</table>
	</fieldset>
	{/if}

	{if $revisions|count > 0}
	<fieldset>
		<legend><img src="{@RELATIVE_WCF_DIR}icon/revisionsM.png" alt="" /> {lang}wcf.projects.revisions{/lang}</legend>
		<table class="tableList">
		<thead>
		<tr class="tableHead">
			<th><div style="padding:5px">{lang}wcf.projects.revision{/lang}</div></th>
			<th><div style="padding:5px">{lang}wcf.projects.author{/lang}</div></th>
			<th><div style="padding:5px">{lang}wcf.projects.timestamp{/lang}</div></th>
			<th><div style="padding:5px">{lang}wcf.projects.msg{/lang}</div></th>
		</tr>
		</thead>
		<tbody>
		{foreach from=$revisions item=revision}
			<tr class="container-{cycle values='1,2'}">
				<td>{$revision.revision}</td>
				<td>{if $revision.userID}<a href="index.php?page=User&amp;userID={$revision.userID}">{$revision.author}</a>{else}{$revision.author}{/if}</td>
				<td style="white-space: nowrap;">{'d.m.y H:i'|gmdate:$revision.timestamp}</td>
				<td>{$revision.msg}</td>
			</tr>
		{/foreach}
		</tbody>
		</table>
	</fieldset>
	{/if}

		</div>
	</div>
</div>

{include file='footer' sandbox=false}
</body>
</html>
