<?php
/**
 * \file getpreview.php
 * Файл формирует информацию о предварительном просмотре для выбранного файла
 * Файл проекта CMS-local.
 * 
 * Создан 20.12.2008
 *
 * \author blade39 <blade39@kolosstudio.ru>
 * \version 1.0
 * \todo
 */
/*Обязательно вставляем во все файлы для защиты от взлома*/ 
if( !defined('KS_ENGINE') ) {die("Hacking attempt!");}

/*Подключаем библеотеку*/
include_once MODULES_DIR.'/kscommander/libs/CKSCommander.php';
include_once MODULES_DIR.'/main/libs/json.php';
/*Устанавливаем режим работы*/
$mode=($_GET['mode']!='')?$_GET['mode']:'';
/*Создаем объект класса*/
$dirs=new CKSCommander();

$path=ROOT_DIR.$_GET['path'];
if(file_exists($path))
{
	if($dirs->IsVisible(dirname($path),basename($path)))
	{
		$action=$dirs->GetActionType($path);
		switch($action)
		{
			case 'CKS_view':
				$arResult=array(
					'name'=>basename($path),
					'href'=>$_GET['path'],
					'size'=>filesize($path),
					'type'=>substr($path,strrpos($path,".")),
					'date'=>filemtime($path),
				);
				$size = $arResult["size"];
				$a = array("b", "kb", "mb", "gb");
				$pos = 0;
				while($size >= 1024)
				{
     				$size /= 1024;
     				$pos++;
				}
				$arResult['SizeText'] = round($size,2)." ".$a[$pos]; 
			break;
		}
	}	
}
$smarty->assign('file',$arResult);
if($mode=='ajax')
{
	echo json_encode($arResult);
	die();
}

$page='_preview';
?>
