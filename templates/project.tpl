{include file="documentHeader"}
<head>
	<title>{$general.groupName} - {lang}wcf.projects.title{/lang} - {PAGE_TITLE}</title>

	{include file='headInclude' sandbox=false}
	<script src="http://simile.mit.edu/timeline/api/timeline-api.js" type="text/javascript"></script>
	<script type="text/javascript">
	//<![CDATA[
	{capture assign=timelineDate}{$general.lastActivity-345600}{/capture}
	var groupID = "{$general.groupID}";
	var timelineDate = "{'M d Y H:i:s'|gmdate:$timelineDate} GMT";
	//]]>
	</script>
	<script src="{@RELATIVE_WCF_DIR}js/projectTime.js" type="text/javascript"></script>
	<style type="text/css">
	.timeline-event-bubble-body {
	 font-size:9pt;
	}
	.timeline-event-bubble-body ul,  .timeline-event-bubble-body li{
	 margin:0px;
	 padding:0px;
	}
	.timeline-event-bubble-time { 
	 display:none;
	}
	</style>
</head>
<body onresize="timeResize();">
{include file='header' sandbox=false}

<div id="main" onresize="timeResize();">
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="icon/indexS.png" alt="" /> <span>{PAGE_TITLE}</span></a> &raquo;</li>
		<li><a href="index.php?page=ProjectList"><img src="{@RELATIVE_WCF_DIR}icon/projectsS.png" alt="" /> <span>{lang}wcf.projects.title{/lang}</span></a> &raquo;</li>
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
					{if $member.userID}<a href="index.php?page=User&amp;userID={$member.userID}">{$member.username}</a>
					{else}{$member.username}{/if}
					{if $member.isLeader}<p>{lang}wcf.projects.leading{/lang}</p>{/if}
				</td>
				<td>{$member.commits}</td>
			</tr>
		{/foreach}
		</tbody>
		</table>
	</fieldset>
	{/if}

	{if $revisions|count > 0}
	<fieldset>
		<legend><img src="{@RELATIVE_WCF_DIR}icon/revisionsM.png" alt="" /> {lang}wcf.projects.revisions{/lang}</legend>
		<div id="my-timeplot" style="height: 175px; border: 1px solid #aaa;"></div>
		<table class="tableList">
		<thead>
		<tr class="tableHead">
			<th><div style="padding:5px">{lang}wcf.projects.revision{/lang}</div></th>
			<th><div style="padding:5px">{lang}wcf.projects.author{/lang}</div></th>
			<th><div style="padding:5px">{lang}wcf.projects.timestamp{/lang}</div></th>
			<th><div style="padding:5px">{lang}wcf.projects.msg{/lang}</div></th>
		</tr>
		</thead>
		<tbody id="revisionbody">
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
	<script type="text/javascript">
	//<![CDATA[
	timeLoad();
	//]]>
	</script>
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
	
	{if $threads|count > 0}
	<script type="text/javascript" src="{@RELATIVE_WBB_DIR}js/ThreadMarkAsRead.class.js"></script>
	<fieldset>
		<legend><img src="icon/boardM.png" alt="" /> {lang}wbb.board.threads.normal{/lang}</legend>
		<table class="tableList">
		<thead>
		<tr class="tableHead">
			<th colspan="2" class="columnTopic">
				<div>{lang}wbb.board.threads.topic{/lang}</div>
			</th>
			{if THREAD_ENABLE_RATING}
				<th class="columnRating">
					<div>{lang}wbb.board.threads.rating{/lang}</div>
				</th>
			{/if}
			<th class="columnReplies">
				<div>{lang}wbb.board.threads.replies{/lang}</div>
			</th>
			<th class="columnViews">
				<div>{lang}wbb.board.threads.views{/lang}</div>
			</th>
			<th class="columnLastPost active">
				<div>{lang}wbb.board.threads.lastPost{/lang}</div>
			</th>
		</tr>
		</thead>
		<tbody>
		{foreach from=$threads item=thread}
		<tr class="container-{cycle values='1,2'}" id="threadRow{@$thread->threadID}">
			<td class="columnIcon">
				<img id="threadEdit{@$thread->threadID}" src="{@RELATIVE_WBB_DIR}icon/{@$thread->getIconName()}M.png" alt="" {if $thread->isNew()}title="{lang}wbb.thread.markAsReadByDoubleClick{/lang}" {/if}/>
				{if $thread->isNew()}
					<script type="text/javascript">
						//<![CDATA[
						threadMarkAsRead.init({@$thread->threadID});
						//]]>
					</script>
				{/if}
			</td>
			<td class="columnTopic"{if BOARD_THREADS_ENABLE_MESSAGE_PREVIEW && $board->getPermission('canReadThread')} title="{$thread->firstPostPreview}"{/if}>
				<div class="smallPages">
					{if $thread->subscribed}<img src="{@RELATIVE_WBB_DIR}icon/threadSubscribedS.png" alt="" title="{lang}wbb.board.threads.subscribed{/lang}" />{/if}
					{if $thread->polls}<img src="{@RELATIVE_WCF_DIR}icon/pollS.png" alt="" title="{lang}wbb.board.threads.polls{/lang}" />{/if}
					{if $thread->attachments}<img src="{@RELATIVE_WCF_DIR}icon/attachmentsS.png" alt="" title="{lang}wbb.board.threads.attachments{/lang}" />{/if}
					{if $thread->ownPosts}<img src="{@RELATIVE_WCF_DIR}icon/userS.png" alt="" title="{lang}wbb.board.threads.ownPosts{/lang}" />{/if}
				</div>
				
				<div id="thread{@$thread->threadID}" class="topic{if $thread->isNew()} new{/if}{if $thread->ownPosts || $thread->subscribed} interesting{/if}">
					{if $thread->isNew()}
						<a id="gotoFirstNewPost{@$thread->threadID}" href="index.php?page=Thread&amp;threadID={@$thread->threadID}&amp;action=firstNew{@SID_ARG_2ND}"><img class="goToNewPost" src="{@RELATIVE_WBB_DIR}icon/goToFirstNewPostS.png" alt="" title="{lang}wbb.index.gotoFirstNewPost{/lang}" /></a>
					{/if}
					
					<p id="threadTitle{@$thread->threadID}">
						<span{if $thread->boardID == $board->boardID} id="threadPrefix{@$thread->threadID}"{/if} class="prefix"><strong>{lang}{$thread->prefix}{/lang}</strong></span>
						<a href="index.php?page=Thread&amp;threadID={@$thread->threadID}{@SID_ARG_2ND}">{$thread->topic}</a>
					</p>
				</div>

				<p class="firstPost light">
					{lang}wbb.board.threads.postBy{/lang}
					{if $thread->userID}
						<a href="index.php?page=User&amp;userID={@$thread->userID}{@SID_ARG_2ND}">{$thread->username}</a>
					{else}
						{$thread->username}
					{/if}
					({@$thread->time|shorttime})
				</p>
			</td>
			{if THREAD_ENABLE_RATING}
				<td class="columnRating">{@$thread->getRatingOutput()}</td>
			{/if}
			<td class="columnReplies{if $thread->replies >= BOARD_THREADS_REPLIES_HOT} hot{/if}">{#$thread->replies}</td>
			<td class="columnViews{if $thread->views > BOARD_THREADS_VIEWS_HOT} hot{/if}">{#$thread->views}</td>
			<td class="columnLastPost">
				{if $thread->replies != 0}
					<div class="containerIconSmall">
						<a href="index.php?page=Thread&amp;threadID={@$thread->threadID}&amp;action=lastPost{@SID_ARG_2ND}"><img src="{@RELATIVE_WBB_DIR}icon/goToLastPostS.png" alt="" title="{lang}wbb.index.gotoLastPost{/lang}" /></a>
					</div>
					<div class="containerContentSmall">
						<p>{lang}wbb.board.threads.postBy{/lang} {if $thread->lastPosterID}<a href="index.php?page=User&amp;userID={@$thread->lastPosterID}{@SID_ARG_2ND}">{$thread->lastPoster}</a>{else}{$thread->lastPoster}{/if}</p>
						<p class="smallFont light">({@$thread->lastPostTime|shorttime})</p>
					</div>
				{else}
					<p class="smallFont light">{lang}wbb.board.threads.noReply{/lang}</p>
				{/if}
			</td>
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
