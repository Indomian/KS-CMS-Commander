<?php
/**
 * \file getdirs.php
 * Файл модуля KsCommander. Строит дерево каталогов, может работать в режиме ajax запросов так и в обычном режиме
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
/*Устанавливаем режим работы*/
$mode=($_GET['mode']!='')?$_GET['mode']:'';
/*Создаем объект класса*/
$dirs=new CKSCommander();
/*Получаем список дерикторий по указанному пути*/
$path=$_GET['path'];
$dirs->SetSort('name','asc');
try
{
	$arDirs=$dirs->GetList(array('name'=>'desc'),array('path'=>$path,'type'=>'dir'),array(0,1000));
}
catch(CError $e)
{
	if($mode=='ajax')
	{
		echo json_encode();
		die();
	}
}
$smarty->assign('dirtree',$arDirs);
if($mode=='ajax')
{
	//$smarty->display('admin/kscommander_tree.tpl');
	echo json_encode(array_values($arDirs));
	die();
}
$page='_tree';
?>
