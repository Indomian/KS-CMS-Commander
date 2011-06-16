{strip}
	<div style="width:300px;border:1px solid #a0afa0;background-color:#F0FFF0;padding:4px;">
	{foreach from=$uplist key=oKey item=oItem}
		{if $oKey!=''}
		{$oKey} - {if $oItem=="ok"}<font color="#00AA00"><b>успешно загружен</b>{elseif $oItem=="empty"}<font color="#FF0000">не загружен, так как является пустым</font>{elseif $oItem=="doesn't exist"}<font color="#FF0000">файл с таким именем уже существует</font>{elseif $oItem=="error"}<font color="#FF0000">ошибка при закачке</font>{/if}<br />
		{/if} 
	{/foreach}
	</div>
{/strip}