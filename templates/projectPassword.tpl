{include file="documentHeader"}
<head>
	<title>{lang}wcf.projects.passwordActivation{/lang} - {lang}wcf.projects.title{/lang} - {PAGE_TITLE}</title>

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
		<img src="{@RELATIVE_WCF_DIR}icon/loginL.png" alt="" />
		<div class="headlineContainer">
			<h2> {lang}wcf.projects.passwordActivation{/lang}</h2>
			<p>{lang}wcf.projects.passwordActivation.description{/lang}</p>
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}
	
	{if $errorField}
		<p class="error">{lang}wcf.global.form.error{/lang}</p>
	{/if}
	
	{if $success|isset}
		<p class="success">{lang}wcf.projects.passwordActivation.success{/lang}</p>
	{else}
	
	<form method="post" action="index.php?form=ProjectPassword">
		<div class="border content">
			<div class="container-1">				
				<div class="formElement{if $errorField == 'password'} formError{/if}">
					<div class="formFieldLabel">
						<label for="loginPassword">{lang}wcf.user.password{/lang}</label>
					</div>
					<div class="formField">
						<input type="password" class="inputText" name="oldPassword" id="oldPassword" />
						{if $errorField == 'password'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								{if $errorType == 'false'}{lang}wcf.user.login.error.password.false{/lang}{/if}
							</p>
						{/if}
					</div>
				</div>
				
			</div>
		</div>
			
		<div class="formSubmit">
			{@SID_INPUT_TAG}
			<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
			<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		</div>
	</form>
	
	{/if}

</div>

{include file='footer' sandbox=false}
</body>
</html>
