{strip}
{foreach from=$navPath item=oItem key=oKey}
	{if $oKey==0}
	     <a href="{get_url _CLEAR="p CKS_ACTION CKS_object" CKS_path=$oItem.Full_path}" onclick="return ksCommander.OpenFolder('{$oItem.Full_path}')"><img src="/uploads/templates/admin/images/icons_menu/home.gif" alt="icon_home" height="13" width="13" />&nbsp;</a>/
	{elseif $oItem.Name}
	     <a href="{get_url _CLEAR="p CKS_ACTION CKS_object" CKS_path=$oItem.Full_path}" onclick="return ksCommander.OpenFolder('{$oItem.Full_path}')">{$oItem.Name}</a>/
	{/if}
{/foreach}
{/strip}