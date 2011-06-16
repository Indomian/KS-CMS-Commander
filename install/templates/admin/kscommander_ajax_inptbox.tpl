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
    <link rel="stylesheet" href="/uploads/templates/admin/css/adminmain.css" type="text/css" />
 	<link rel="stylesheet" href="/uploads/templates/admin/css/interface.css" type="text/css" />
    <!--[if lt IE 8]><link rel=stylesheet href="css/adminmain_ie.css"><![endif]-->
</head>
<body>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/json.js"></script>
<script type="text/javascript" src="/js/kscommander/interface.js"></script>
<script type="text/javascript">document.ksPath='{$statistics.cur_path}';document.totalSelected=0;</script>
      
<div class="tree_cont" style="margin-top: 0;bottom:10px;left:0px;top:50px;position:absolute;height:auto;">
	<div class="tree" style="width: 800px;" id="dirTree">
		<!-- ВАЖНО! Тут надо будет скриптом подставлять ширину, в зависимости от уровней вложенности  -->
		{include file="admin/kscommander_tree.tpl" dirtree=$dirlist}
	</div>
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
	          	<form action="{get_url}" method="POST" onsubmit="if(document.ksPath) return LoadFiles(document.ksPath); else return LoadFiles('{$statics.cur_path}');">
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
			  
<div style="margin-right: 20px;position:absolute;left:220px;top:50px;right:0px;width:auto;">
			<form action="{get_url _CLEAR=action}" method=post>  
			<div class="form" style="border-top:1px solid #E5E5E5;">
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
			</div>
			<br/>
			<div class="manage">
				<input type="submit" value="Создать" class="add_div2"/>
				<input type="button" onclick="document.location='{get_url _CLEAR="CKS_ACTION" CKS_path=$statistics.cur_path}'" value="Отменить">
			</div>
			</form>
</div>  
</body>
</html>