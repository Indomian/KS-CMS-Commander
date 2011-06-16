<?php
if($this->obUser->GetLevel($arModule['DIRECTORY'])==0)
{
	$this->AddMenuItem(MenuItem("KSCOMMANDER","MAIN2","module=kscommander","Файловый менеджер",'item.gif'),"GLOBAL");
}
?>