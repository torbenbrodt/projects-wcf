{include file="documentHeader"}
<head>
	<title>{lang}wcf.projects.title{/lang} - {PAGE_TITLE}</title>

	{include file='headInclude' sandbox=false}
</head>
<body>
{include file='header' sandbox=false}

<div id="main">
	
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="icon/indexS.png" alt="" /> <span>{PAGE_TITLE}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<img src="{@RELATIVE_WCF_DIR}icon/projectsL.png" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.projects.title{/lang}</h2>
			<p>{lang}wcf.projects.description{/lang}</p>
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}
	
	{if $projects|count > 0}
		<div class="border">
			<div class="containerHead">
				<h3></h3>
			</div>
			<table class="tableList membersList">
				<thead>
					<tr class="tableHead">
						{foreach from=$header item=field}
							<th class="column{$field.field|ucfirst}{if $sortField == $field.field} active{/if}"><div><a href="index.php?page=ProjectList&amp;sortField={$field.field}&amp;sortOrder={if $sortField == $field.field && $sortOrder == 'ASC'}DESC{else}ASC{/if}{@SID_ARG_2ND}">{lang}{@$field.name}{/lang}{if $sortField == $field.field} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
						{/foreach}
					</tr>
				</thead>
				<tbody>
				{foreach from=$projects item=project}
					<tr class="container-{cycle values='1,2'}">
						{foreach from=$fields item=field}
							<td class="column{$field|ucfirst}">{@$project.$field}</td>
						{/foreach}
					</tr>
				{/foreach}
				</tbody>
			</table>
		</div>
	{else}
		<div class="subTabMenu">
			<div class="containerHead"><div> </div></div>
		</div>
		<div class="border tabMenuContent">
			<div class="container-1">
				{lang}wcf.projects.error.noProjects{/lang}
			</div>
		</div>
	{/if}

</div>

{include file='footer' sandbox=false}
</body>
</html>
