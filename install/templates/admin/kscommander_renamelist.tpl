{config_load file=admin.conf section=kscommander}
<table class="layout">
	<col width="5%" />
	<col width="30%" />
	<col width="10%" />
	<col width="10%" />
	<col width="20%" />
	{foreach from=$list item=oItem key=oKey name=fList}{strip}
    	<tr {if $smarty.foreach.fList.iteration is even}class="odd"{/if}>
    		<td></td>
    		<td class="namet"><img src="{#images_path#}/{$oItem.Icon}" border=0>&nbsp;
    		{if $oItem.editName==1}
    			<input type="text" name="rename[{$oItem.Name}]" value="{$oItem.Name|htmlspecialchars:2:"UTF-8":false}"/>
    		{else}
    			{if $oItem.Action_type=='CKS_view'}
	    			<a href="{$oItem.Full_path}" target="_blank" {if $view_url!=''}{$view_url}'{$oItem.Full_path}'{$view_url_end}{/if} onclick="return LoadPreview('{$oItem.Full_path}');">{$oItem.Name}</a><br>
    				<font style="font-size:10px;color:#A7A7A7">Полное имя файла: {$oItem.Full_path}</font>
    			{elseif $oItem.Action_type=='CKS_open'}
    				<a href="{get_url _CLEAR="p CKS_ACTION CKS_object mode page" CKS_path=$oItem.Full_path}" onclick="return LoadFiles('{$oItem.Full_path}','{get_get _CLEAR="p CKS_ACTION CKS_object mode mode page" CKS_path=$oItem.Full_path}')">{$oItem.Name}</a>
    			{else}
    				{$oItem.Name}
    			{/if}
    		{/if}
    		</td>
    		<td>{if $oItem.Extension==""}{$oItem.Type}{else}{$oItem.Extension}{/if}</td>
    		<td>{$oItem.SizeText}</td>
    		<td>{$oItem.Date|date_format:"%H:%M:%S %d.%m.%Y"}</td>
	    </tr>
	{/strip}
	{/foreach}
</table>