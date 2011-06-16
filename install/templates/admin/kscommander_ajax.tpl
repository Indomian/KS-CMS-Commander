{config_load file=admin.conf section=kscommander}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta name="description" content="description" />
    <meta name="keywords" content="keywords" />
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
    <title> 
    Панель администрирования KS-CMS
    </title>
    <link rel="stylesheet" href="/uploads/templates/admin/css/adminmain.css" type="text/css" />
 	<link rel="stylesheet" href="/uploads/templates/admin/css/interface.css" type="text/css" />
    <!--[if lt IE 8]><link rel=stylesheet href="css/adminmain_ie.css"><![endif]-->
    <script type="text/javascript">
   	function dis(sender,obj) {ldelim}
		if (document.getElementById(obj).style.display == 'none') 
	  	{ldelim}
   			document.getElementById(obj).style.display = 'block';
        	document.getElementById(sender).className = 'menu_arrow_up';
		{rdelim}
        else 
        {ldelim}
            document.getElementById(obj).style.display = 'none';
            document.getElementById(sender).className = 'menu_arrow_down';
        {rdelim}
	{rdelim}
    </script>
    <script language="javascript" type="text/javascript" src="/js/tiny_mce/tiny_mce_popup.js"></script>
    {literal}
    <script type="text/javascript">
    var FileBrowserDialogue = {
	    init : function () {
	        // Here goes your code for setting your custom things onLoad.
	    },
	   	mySubmit : function (img) {
	        var URL = img;
	        var win = tinyMCEPopup.getWindowArg("window");
	
	        // insert information now
	        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;
	
	        // for image browsers: update image dimensions
	        /*if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();
	        if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(URL);*/
	
	        // close popup window
	        tinyMCEPopup.close();
	    }
	}

	tinyMCEPopup.onInit.add(FileBrowserDialogue.init, FileBrowserDialogue);
    </script>
    {/literal}
</head>
<body>
<script type="text/javascript" src="/js/jquery/jquery.js"></script>
<script type="text/javascript" src="/js/kscommander/interface.js"></script>
<script type="text/javascript">
	ksCommander.path='{$statistics.cur_path}';
	ksCommander.totalSelected=0;
	ksCommander.Mode='{$type}';
	ksCommander.isAjax=1;
</script>
      
<div class="tree_cont" style="margin-top: 0;bottom:150px;left:0px;top:50px;position:absolute;height:auto;">
	<div class="tree" style="width: 800px;" id="dirTree">
		<!-- ВАЖНО! Тут надо будет скриптом подставлять ширину, в зависимости от уровней вложенности  -->
		{include file="admin/kscommander_tree.tpl" dirtree=$dirlist view_url="onclick=\"FileBrowserDialogue.mySubmit(" view_url_end=");return false;\""}
	</div>
</div>  
<div class="logo_cont" id="filePreview" style="bottom:0px;height:140px;left:0px;position:absolute;">
	{include file="admin/kscommander_preview.tpl"}
</div>

<div class="manage" id="manageBar" style="position:absolute;left:0px;top:0px;height:30px;right:0px;">
	<table class="layout">
    	<tr>
			<td>
	          	<form action="{get_url CKS_ACTION=newdir_form}" method="GET">
	          	<noscript>
	          	<input type="hidden" name="CKS_path" value="{$statistics.cur_path}"/>
	          	</noscript>
	          	<input type="hidden" name="module" value="kscommander"/>
	          	<input type="hidden" name="CKS_ACTION" value="newdir_form"/>
	          	<input type="hidden" name="mode" value="ajax"/> 
	          	<input type="submit" value="Создать каталог" class="add_div"/></div>
	          	</form>
			</td>
			<td><div style="width:10px;">&nbsp;</div></td>
	        <td>
	          	<form action="{get_url CKS_ACTION=upload_form}" method="GET">
	          	<noscript>
	          	<input type="hidden" name="CKS_path" value="{$statistics.cur_path}"/>
	          	</noscript>
	          	<input type="hidden" name="module" value="kscommander"/>
	          	<input type="hidden" name="CKS_ACTION" value="upload_form"/>
	          	<input type="hidden" name="mode" value="ajax"/> 
	          	<div><input class="upload" type="submit" value="Загрузить" /></div>
	          	</form>
	        </td>
	        <td>
	          	<form action="{get_url}" method="POST" onsubmit="if(ksCommander.path) return ksCommander.LoadFiles(ksCommander.path); else return ksCommander.LoadFiles('{$statics.cur_path}');">
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
        		<div class="upload_line" id="navChain" style="border:0px;">
					{include file="admin/kscommander_navchain.tpl"}
				</div>   
      		</td>
		</tr>
    </table>
</div>
<div class="action" id="actionBar" style="display:none;z-index:1000;position:absolute;left:220px;bottom:50px;right:0px;">
	<table class="layout">
		<tr>
			<td><a href="#" id="cutButton" class="inactive" onclick="return ksCommander.CutFiles(this);"><img src="{#images_path#}/icons5/01.gif" alt="icon" height="16" width="16" /> <span>Вырезать</span></a></td>
			<td><a href="#" id="copyButton" class="inactive" onclick="return ksCommander.CopyFiles(this);"><img src="{#images_path#}/icons5/02.gif" alt="icon" height="16" width="16" /> <span>Копировать</span></a></td>
			<td><a href="#" id="pasteButton" onclick="return ksCommander.pasteFiles();"><img src="{#images_path#}/icons5/03.gif" alt="icon" height="16" width="16" /> <span>Вставить</span></a></td>
			<td><a href="#" id="editButton" class="inactive"><img src="{#images_path#}/icons5/04.gif" alt="icon" height="16" width="16" /> <span>Править</span></a></td>
			<td><a href="#" id="renameButton" class="inactive" onclick="return ksCommander.RenameFiles(this)"><img src="{#images_path#}/icons5/05.gif" alt="icon" height="16" width="16" /> <span>Переименовать</span></a></td>
			<td><a href="#" id="deleteButton" class="inactive" onclick="return ksCommander.DeleteFiles(this)"><img src="{#images_path#}/icons5/06.gif" alt="icon" height="16" width="16" /> <span>Удалить</span></a></td>
			<td><a href="#" id="downloadButton" class="inactive" onclick="return ksCommander.DownloadFile(this)"><img src="{#images_path#}/icons5/07.gif" alt="icon" height="16" width="22" /> <span>Скачать</span></a></td>
		</tr>
	</table>
</div>
<div class="content_arrow_down" onclick="toggleActionBar(this)" style="position:absolute;left:220px;bottom:44px;z-index:300;height:10px;right:0px;">&nbsp;</div>
			  
<div class="users" style="margin-right: 20px;position:absolute;left:220px;top:50px;right:0px;width:auto;">  
	<table class="layout">
		<col width="5%" />
		<col width="30%" />
		<col width="10%" />
		<col width="10%" />
		<col width="20%" />
		<tr>{strip}	
			<th><input type="checkbox" onclick="selectAll(this)" id="totalChecker"/></th>
			<th><a href="{get_url _CLEAR="p" order='name' dir=$order.newdir}" onclick="return ksCommander.ChangeSort('name',this.parentNode)">Имя</a>
				<img src="{#images_path#}/arrows/{if $order.curdir=='asc'}06{else}05{/if}.gif" {if $order.field=='name'}{else}style="display:none;"{/if}>
			</th>
			<th><a href="{get_url _CLEAR="p" order='ext' dir=$order.newdir}" onclick="return ksCommander.ChangeSort('ext',this.parentNode)">Тип</a>
				<img src="{#images_path#}/arrows/{if $order.curdir=='asc'}06{else}05{/if}.gif" {if $order.field=='ext'}{else}style="display:none;"{/if}></th>
			<th><a href="{get_url _CLEAR="p" order='size' dir=$order.newdir}" onclick="return ksCommander.ChangeSort('size',this.parentNode)">Размер</a>
				<img src="{#images_path#}/arrows/{if $order.curdir=='asc'}06{else}05{/if}.gif" {if $order.field=='size'}{else}style="display:none;"{/if}></th>
			<th><a href="{get_url _CLEAR="p" order='date' dir=$order.newdir}" onclick="return ksCommander.ChangeSort('date',this.parentNode)">Изменен</a>
				<img src="{#images_path#}/arrows/{if $order.curdir=='asc'}06{else}05{/if}.gif" {if $order.field=='date'}{else}style="display:none;"{/if}></th>
		{/strip}</tr>
	</table>
</div>  
<div class="offers_in_div" style="position:absolute;left:220px;top:82px;right:0px;width:auto;height:auto;bottom:60px;">
	<form action="{get_url}" method="post" id="fileSelector">
		<div class="users offers_scroll" id="fileList" style="bottom:0px;top:0px;position:absolute;left:0px;right:0px;height:auto;">  
			{include file='admin/kscommander_list.tpl' view_url="onclick=\"FileBrowserDialogue.mySubmit(" view_url_end=");return false;\""}
		</div> 
	</form>
</div>  
			<br />
<div class="pages" id="pagesBar" style="position:absolute;left:220px;bottom:0px;height:30px;right:5px;">
	{include file='admin/kscommander_pages.tpl'}
</div>     
 <script type="text/javascript">
$(document).ready(function()
{ldelim}
{strip}ksCommander.ShowMessage('{$statistics.message}',10000);
ksCommander.LoadFiles('{$statistics.cur_path}');
{/strip}      
{rdelim});
</script>
</body>
</html>