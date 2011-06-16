{config_load file=admin.conf section=kscommander}
<ul class="nav">
	<li><a href="/admin.php"><img src="{#images_path#}/icons_menu/home.gif" alt="icon_home" height="13" width="13" />&nbsp;<span>{#home#}</span></a></li>      
    <li><a href="/admin.php?module=kscommander"><img src="{#images_path#}/icons_menu/arrow.gif" alt="icon_arrow" height="13" width="13" />&nbsp;<span>{#title#}</span></a>
</ul>
<script type="text/javascript" src="/js/json.js"></script>
<script type="text/javascript" src="/js/kscommander/interface.js"></script>
<script type="text/javascript">
	ksCommander.path='{$statistics.cur_path}';
	ksCommander.visible={$num_visible};
	ksCommander.page={$pages.active};
</script>
<h1>{#title#}</h1>
<div class="upload_line" id="navChain">
{include file="admin/kscommander_navchain.tpl"}
</div>      
<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top">
			<div class="tree_cont" style="margin-top: 0;">
				<div class="tree" style="width: 800px;" id="dirTree">
					{include file="admin/kscommander_tree.tpl" dirtree=$dirlist}
				</div>
			</div>  
			<div class="logo_cont" id="filePreview">
				{include file="admin/kscommander_preview.tpl"}
			</div>
		</td> 
		<td valign="middle">
			<div style="width: 10px;">&nbsp;</div>  
		</td>  
		<td valign="top" width="100%">
			<div class="manage">
        		<table class="layout">
         			<tr>
         				<td>
          					<form action="{get_url CKS_ACTION=newdir_form}" method="GET">
	          					<noscript>
	          						<input type="hidden" name="CKS_path" value="{$statistics.cur_path}"/>
	          					</noscript>
	          					<input type="hidden" name="module" value="kscommander"/>
	          					<input type="hidden" name="CKS_ACTION" value="newdir_form"/><div> 
	          					<input type="submit" value="Создать каталог" class="add_div"/></div>
	          				</form>
          				</td>
          				<td>
          					<form action="{get_url CKS_ACTION=upload_form}" method="GET">
					          	<noscript>
					          		<input type="hidden" name="CKS_path" value="{$statistics.cur_path}"/>
					          	</noscript>
					          	<input type="hidden" name="module" value="kscommander"/>
					          	<input type="hidden" name="CKS_ACTION" value="upload_form"/><div> 
					          	<div><input class="upload" type="submit" value="Загрузить" /></div>
          					</form>
          				</td>
          				<td>
          					<form action="{get_url}" method="POST" onsubmit="if(ksCommander.path) return LoadFiles(ksCommander.path); else return LoadFiles('{$statics.cur_path}');">
          						<div><input class="refresh" type="submit" value="Обновить" /></div>
          					</form>
          				</td>
          				<td width="100%">
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
					</tr>
        		</table>
      		</div>
      		<div class="action">
				<table class="layout">
					<tr>
						<td><a href="#" id="cutButton" class="inactive" onclick="return ksCommander.CutFiles(this);"><img src="{#images_path#}/icons5/01.gif" alt="icon" height="16" width="16" /> <span>Вырезать</span></a></td>
						<td><a href="#" id="copyButton" class="inactive" onclick="return ksCommander.CopyFiles(this);"><img src="{#images_path#}/icons5/02.gif" alt="icon" height="16" width="16" /> <span>Копировать</span></a></td>
						<td><a href="#" id="pasteButton" onclick="return ksCommander.pasteFiles(this);"><img src="{#images_path#}/icons5/03.gif" alt="icon" height="16" width="16" /> <span>Вставить</span></a></td>
						<td><a href="#" id="editButton" class="inactive"><img src="{#images_path#}/icons5/04.gif" alt="icon" height="16" width="16" /> <span>Править</span></a></td>
						<td><a href="#" id="renameButton" class="inactive" onclick="return ksCommander.RenameFiles(this)"><img src="{#images_path#}/icons5/05.gif" alt="icon" height="16" width="16" /> <span>Переименовать</span></a></td>
						<td><a href="#" id="deleteButton" class="inactive" onclick="return ksCommander.DeleteFiles(this)"><img src="{#images_path#}/icons5/06.gif" alt="icon" height="16" width="16" /> <span>Удалить</span></a></td>
						<td><a href="#" id="downloadButton" class="inactive" onclick="return ksCommander.DownloadFile(this)"><img src="{#images_path#}/icons5/07.gif" alt="Скачать" height="16" width="22" /> <span>Скачать</span></a></td>
 					</tr>
				</table>
			</div>
			<div class="users" style="margin-right: 20px;">  
				<table class="layout">
					<col width="5%" />
					<col width="30%" />
					<col width="10%" />
					<col width="10%" />
					<col width="20%" />
 					<tr>{strip}
  						<th><input type="checkbox" onclick="ksCommander.selectAll(this)" id="totalChecker"/></th>
						<th><a href="{get_url _CLEAR="p" order='name' dir=$order.newdir}" onclick="return ksCommander.ChangeSort('name',this.parentNode)">Имя</a>
							<img src="{#images_path#}/arrows/{if $order.curdir=="asc"}06{else}05{/if}.gif" {if $order.field=="name"}{else}style="display:none;"{/if}>
  						</th>
						<th><a href="{get_url _CLEAR="p" order='ext' dir=$order.newdir}" onclick="return ksCommander.ChangeSort('ext',this.parentNode)">Тип</a>
  	  						<img src="{#images_path#}/arrows/{if $order.curdir=="asc"}06{else}05{/if}.gif" {if $order.field=="ext"}{else}style="display:none;"{/if}></th>
  						<th><a href="{get_url _CLEAR="p" order='size' dir=$order.newdir}" onclick="return ksCommander.ChangeSort('size',this.parentNode)">Размер</a>
  	  						<img src="{#images_path#}/arrows/{if $order.curdir=="asc"}06{else}05{/if}.gif" {if $order.field=="size"}{else}style="display:none;"{/if}></th>
  						<th><a href="{get_url _CLEAR="p" order='date' dir=$order.newdir}" onclick="return ksCommander.ChangeSort('date',this.parentNode)">Изменен</a>
  	  						<img src="{#images_path#}/arrows/{if $order.curdir=="asc"}06{else}05{/if}.gif" {if $order.field=="date"}{else}style="display:none;"{/if}></th>
						{/strip}</tr>
				</table>
			</div>  
			<div class="offers_in_div">
				<form action="{get_url}" method="post" id="fileSelector">
					<div class="users offers_scroll" id="fileList">  
						{include file='admin/kscommander_list.tpl'}
					</div> 
				</form>
			</div>  
			<br />
			<div class="pages" id="pagesBar">
				{include file='admin/kscommander_pages.tpl'}
			</div>      
		</td>
	</tr>
</table>
<br />

{strip}
<dl class="def" style="background:#FFF6C4 url('{#images_path#}/big_icons/doc.gif') left 50% no-repeat;{if $smarty.cookies.showHelpBar==1}display:none;{/if}">
<dt>Файловый менеджер</dt>
  <dd>Данный модуль позволяет управлять файлами на Вашем сайте.</dd>
 </dl> 
<div class="content_arrow_{if $smarty.cookies.showHelpBar==1}down{else}up{/if}" onclick="ToggleHelpBar(this)" style="cursor:pointer;">&nbsp;</div>
{/strip} 

<script type="text/javascript">
$(document).ready(function()
{ldelim}
{strip}ksCommander.ShowMessage('{$statistics.message}',10000);
//ksCommander.LoadFiles('{$statistics.cur_path}');
{/strip}      
{rdelim});
</script>