{config_load file=admin.conf section=kscommander}
{if $view_url!=''}{assign var="isAjax" value="1"}{else}{assign var="isAjax" value="0"}{/if}
<table class="layout">
	<col width="5%" />
	<col width="30%" />
	<col width="10%" />
	<col width="10%" />
	<col width="20%" />
	{foreach from=$list item=oItem key=oKey name=fList}{strip}
    	<tr {if $smarty.foreach.fList.iteration is even}class="odd"{/if}>
    		{if $oItem.Size>0}
    		<td onclick="ksCommander.SelectRow(this.parentNode);"{Highlight date=$oItem.date_add assign=highlight i=$smarty.foreach.fList.iteration}>
    			<input type="checkbox" name="select[]" value="{$oItem.Name}"/>
    		</td>
    		{else}
    		<td{Highlight date=$oItem.date_add assign=highlight i=$smarty.foreach.fList.iteration}></td>
    		{/if}
    		<td class="namet" {if $oItem.Size>0}onclick="ksCommander.SelectRow(this.parentNode);"{/if}{$highlight}><img src="{#images_path#}/{$oItem.Icon}" border="0" style="margin-right:10px;">
    		{if $oItem.Action_type=='CKS_view'}
    			<a href="{$oItem.Full_path}" target="_blank" onclick="return ksCommander.LoadPreview('{$oItem.Full_path}');">{$oItem.Name}</a><br>
    			<font style="font-size:10px;color:#A7A7A7">Полное имя файла: {$oItem.Full_path}</font>
    		{elseif $oItem.Action_type=='CKS_open'}
    			<a href="{get_url _CLEAR="p CKS_ACTION CKS_object mode page" CKS_path=$oItem.Full_path}" onclick="return ksCommander.OpenFolder('{$oItem.Full_path}')">{$oItem.Name}</a>
    		{else}
    			{$oItem.Name}
    		{/if}</td>
    		<td {if $oItem.Size>0}onclick="ksCommander.SelectRow(this.parentNode);"{/if}{$highlight}>{if $oItem.Extension==""}{$oItem.Type}{else}{$oItem.Extension}{/if}</td>
    		<td {if $oItem.Size>0}onclick="ksCommander.SelectRow(this.parentNode);"{/if}{$highlight}>{$oItem.SizeText}</td>
    		<td {if $oItem.Size>0}onclick="ksCommander.SelectRow(this.parentNode);"{/if}{$highlight}>
    		{if $view_url!=''}
    			{if $oItem.Action_type=='CKS_view'}
    			<input type="button" {$view_url}'{$oItem.Full_path}'{$view_url_end} value="Выбрать"/>
    			{/if}
    			{assign var="isAjax" value="1"}
    			{else}
    			{$oItem.Date|date_format:"%H:%M %d.%m.%Y"}
    		{/if}
			</td>
	    </tr>
	{/strip}
	{/foreach}
</table>