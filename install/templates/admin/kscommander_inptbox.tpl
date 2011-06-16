{config_load file=admin.conf section=kscommander}
<ul class="nav">
	<li><a href="/admin.php"><img src="{#images_path#}/icons_menu/home.gif" alt="icon_home" height="13" width="13" />&nbsp;<span>{#home#}</span></a></li>      
    <li><a href="/admin.php?module=kscommander"><img src="{#images_path#}/icons_menu/arrow.gif" alt="icon_arrow" height="13" width="13" />&nbsp;<span>Файловый менеджер</span></a>
    <li><a href="{get_url}"><img src="{#images_path#}/icons_menu/arrow.gif" alt="icon_arrow" height="13" width="13" />&nbsp;<span>Создание папки</span></a>
</ul>

<script type="text/javascript" src="/js/file_loader.js"></script>
<script type="text/javascript" src="/js/jquery/jquery.js"></script>
<script type="text/javascript" src="/js/json.js"></script>
<script type="text/javascript" src="/js/kscommander/interface.js"></script>
<script type="text/javascript">document.ksPath='{$statistics.cur_path}';document.totalSelected=0;</script>

<h1>KS Commander</h1>
<div class="upload_line" id="navChain">
{include file="admin/kscommander_navchain.tpl"}
</div>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top">{strip}
			<div class="tree_cont" style="margin-top: 0;">
				<div class="tree" style="width: 800px;" id="dirTree">
					<!-- ВАЖНО! Тут надо будет скриптом подставлять ширину, в зависимости от уровней вложенности  -->
					{include file="admin/kscommander_tree.tpl" dirtree=$dirlist}
				</div>
			{/strip}</div>  
			<div class="spacebar">
				<div class="spacebar_cont">
					<div class="l1">
						<b class="space_taken"><i>{$statistics.total_occup_space}</i></b>
						<b class="space_ttl"><i>{$statistics.total_disk_space}</i></b>
						<div class="l2" style="width: 20%;">&nbsp;</div>
					</div>
				</div>
			</div>
		</td> 
  		<td valign="middle">
			<div style="width: 10px;">&nbsp;</div>  
  		</td>  
 		<td valign="top" width="100%">
 			{ksTabs NAME=ksc_input head_class=tabs2 title_class=bold}
 			{ksTab NAME="Имя папки" selected="1"}
 			<form action="{get_url _CLEAR=action}" method=post>
 			<div class="form">
				<input type="hidden" name="module" value="kscommander">
				{foreach from=$input.vars key=oKey item=oItem}
				<input type="hidden" name="{$oKey}" value="{$oItem}">
				{/foreach}
				<table class="layout">
				<tr><td>{$input.message}</td>
				<td><input type="text" name="{$input.inptname|htmlspecialchars:2:"UTF-8":false}" style="width:300px;"/></td>
				</tr>
				<tr><td colspan="2">
				</td></tr>
				</table>
			</div><br/>
				<div class="manage">
				<input type="submit" value="Создать" class="add_div2"/>
				<input type="button" onclick="document.location='{get_url _CLEAR="CKS_ACTION" CKS_path=$statistics.cur_path}'" value="Отменить">
				</div>
			</form>
			
			{/ksTab}
			{/ksTabs}
		</td>
	</tr>
</table>

{strip}
<dl class="def" style="background:#FFF6C4 url('{#images_path#}/big_icons/doc.gif') left 50% no-repeat;{if $smarty.cookies.showHelpBar==1}display:none;{/if}">
 <dt>Файловый менеджер</dt>
  <dd>Создание папки. При помощий данной формы Вы можете создать новую папку в текущем каталоге</dd>        
</dl> 
<div class="content_arrow_{if $smarty.cookies.showHelpBar==1}down{else}up{/if}" onclick="ToggleHelpBar(this)" style="cursor:pointer;">&nbsp;</div>
{/strip} 