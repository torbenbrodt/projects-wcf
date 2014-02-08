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

<div class="border container-3" style="padding:10px">
<b>Was hat es mit der Projektintegration auf sich?</b><br />
Wir haben die Versionsverwaltung Subversion und das Projektmanagement-Tool Trac mit dem Woltlab Communiy Framework verbunden. Das sind beides Werkzeuge, die das Programmieren im Team sehr erleichtern.
Gruppenmanagement und Authentifizierung funktionieren dadurch komplett &uuml;ber die fertigen Werkzeuge vom WCF - und damit &uuml;ber dieses Forum.<br />
<br />

Vorerst behalte ich mir noch das Recht vor Projekte selbst zu gr&uuml;nden. Wenn ihr aber Projektideen habt und Subversion + TRAC Hosting sucht, dann meldet euch einfach bei mir. Mehr Informationen findet ihr im <a href="http://www.easy-coding.de/foren-projektintegration-t4320.html">offiziellen Statement</a>.
</div>

</div>

{include file='footer' sandbox=false}
</body>
</html>
