{config_load file=admin.conf section=kscommander}
{if $view_url!=''}{assign var="isAjax" value="1"}{else}{assign var="isAjax" value="0"}{/if}
{strip}<ul>
{$dirtree.templates.sub.yellow.name}
	{foreach from=$dirtree item=oItem key=oKey}
		{if ($oItem.Size==0)}
		{else}	
		<li>
			<img height="13" width="13" alt="icon" src="{#images_path#}/icons_menu/plus.gif" onclick="ksCommander.LoadSubDirs('{$oItem.Full_path}',ksCommander.SearchTreeLeaf('{$oItem.Full_path}'));"/>
			{if ($oItem.Type=='zip')}
				<img height="20" width="20" alt="icon" src="{#images_path#}/icons2/zip_folder.gif"/>
			{else}
				{if ($oItem.is_open==1)}
					<img height="20" width="20" alt="icon" src="{#images_path#}/icons2/folder_open.gif"/>		
				{else}
					<img height="20" width="20" alt="icon" src="{#images_path#}/icons2/folder.gif"/>
				{/if}
			{/if}
			<a href="{get_url _CLEAR="p CKS_ACTION CKS_object page" CKS_path=$oItem.Full_path}" onclick="return ksCommander.OpenFolder('{$oItem.Full_path}')">{$oItem.Name}</a>

			{if $oItem.sub!=''}
				{include file='admin/kscommander_tree.tpl' dirtree=$oItem.sub}
			{/if}
		</li>
		{/if}
	{/foreach}
</ul>{/strip}
