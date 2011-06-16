{config_load file=admin.conf section=kscommander}
<div class="logo_cont_name">
<img src="{#images_path#}/icons2/file_image.gif" alt="icon" height="20" width="20" /> 
<span id="preview_name">{$file.name|truncate:20}</span>
<img src="{#images_path#}/icons_menu/arrow_1.gif" alt="icon" height="16" width="16" />
</div>

<div class="logo_cont_img" id="imgcont">
<noscript>
<img src="{$file.href}" alt="logo"/>
</noscript>
</div>

<table class="logo_cont_table" class="layout_nw">
<tr>
<th>Тип:</th>  
<td>{$file.type}</td>

</tr>

<tr>
<th>Размер: </th>
<td>{$file.SizeText}</td>
</tr>
<tr>
<th>Изменен: </th>
<td>{$file.date|date_format:"%H:%M %d.%m.%Y"}</td>
</tr>
</table>
