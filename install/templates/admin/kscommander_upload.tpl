{config_load file=admin.conf section=kscommander}
<ul class="nav">
	<li><a href="/admin.php"><img src="{#images_path#}/icons_menu/home.gif" alt="icon_home" height="13" width="13" />&nbsp;<span>{#home#}</span></a></li>      
    <li><a href="/admin.php?module=kscommander"><img src="{#images_path#}/icons_menu/arrow.gif" alt="icon_arrow" height="13" width="13" />&nbsp;<span>Файловый менеджер</span></a>
    <li><a href="{get_url}"><img src="{#images_path#}/icons_menu/arrow.gif" alt="icon_arrow" height="13" width="13" />&nbsp;<span>Загрузка файла</span></a>
</ul>

<script type="text/javascript" src="/js/file_loader.js"></script>
<script type="text/javascript" src="/js/jquery.js"></script>
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
  			{ksTabs NAME=ksc_upload head_class=tabs2 title_class=bold}
  				{ksTab NAME="Загрузить поштучно" selected="1"}
  					<div class="form">
  						<form action="{get_url _CLEAR=CKS_ACTION}" method="POST" enctype="multipart/form-data" name="fileForm" id="fileForm">
							<input type="hidden" name="module" value="kscommander">
							<input type="hidden" name="CKS_ACTION" value="upload">
							<input type="hidden" name="CKS_path" value="{$statistics.cur_path}">
							<input type="hidden" name="jNum" value="5">
						    <table class="layout" id="fileTable">
						    <tr class="titles">
						    <td width=50%><h3>Имя файла</h3></td>
						    <td width=50%><h3>Выбор файла</h3></td>
						    </tr>
						    <tr>
						    <td><input type="text" id="CKS_file_name_1" name="CKS_file_name_1" value="" style="width:70%;"></td>
						    <td><input type="file" id="CKS_file_1" name="CKS_file_1" style="width:100%" onchange="filechange(this);"></td>
						    </tr>
						    <tr>
						    <td><input type="text" id="CKS_file_name_2" name="CKS_file_name_2" value="" style="width:70%;"></td>
						    <td><input type="file" id="CKS_file_2" name="CKS_file_2" style="width:100%" onchange="filechange(this);"></td>
						    </tr>
							<tr>
						    <td><input type="text" id="CKS_file_name_3" name="CKS_file_name_3" value="" style="width:70%;"></td>
						    <td><input type="file" id="CKS_file_3" name="CKS_file_3" style="width:100%" onchange="filechange(this);"></td>
						    </tr>
						    <tr>
						    <td><input type="text" id="CKS_file_name_4" name="CKS_file_name_4" value="" style="width:70%;"></td>
						    <td><input type="file" id="CKS_file_4" name="CKS_file_4" style="width:100%" onchange="filechange(this);"></td>
						    </tr>
							<tr>
						    <td><input type="text" id="CKS_file_name_5" name="CKS_file_name_5" value="" style="width:70%;"></td>
						    <td><input type="file" id="CKS_file_5" name="CKS_file_5" style="width:100%" onchange="filechange(this);"></td>
						    </tr>
						
						
						    </table>
						    <div class="manage">
						    	<input type="submit" value="Сохранить"/>
						    	<input type="button" onclick="document.location='{get_url _CLEAR="CKS_ACTION" CKS_path=$statistics.cur_path}';" value="Отмена"/>
						    </div>
						</form>
					</div>
  				{/ksTab}
  			{/ksTabs}
		</td>
	</tr>
</table>

{strip}
<dl class="def" style="background:#FFF6C4 url('{#images_path#}/big_icons/doc.gif') left 50% no-repeat;{if $smarty.cookies.showHelpBar==1}display:none;{/if}">
 <dt>Файловый менеджер</dt>
  <dd>Загрузка файлов. На этой странице Вы можете загрузить Ваши файлы в указанную папку. Файлы можно загружать как поштучно, так и 
  сразу целым архивом, после чего архив будет сразуже расспакован в текущую папку.</dd> 
 </dl> 
<div class="content_arrow_{if $smarty.cookies.showHelpBar==1}down{else}up{/if}" onclick="ToggleHelpBar(this)" style="cursor:pointer;">&nbsp;</div>
{/strip} 