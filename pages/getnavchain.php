<?php
/**
 * \file getnavchain.php
 * Файл отдает содержимое навигационной цепочки для текущего пути. Путь определяется по 
 * переменной сессии $_SESSION['cur_path'];
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
$Commander=new CKSCommander();
/*Устанавливаем режим работы*/
$mode=($_GET['mode']!='')?$_GET['mode']:'';
/*Создаем объект класса*/
$dirs=new CKSCommander();
/*Получаем список дерикторий по указанному пути*/
$mypath=$_SESSION['cur_path'];
try
{
	$arDirs=$Commander->GetList(array('name'=>'asc'),array('path'=>'/uploads/','type'=>'dir'),array(0,1000));
	if($mypath!='/')
	{
		$arPath=ClearArray(explode('/',$mypath));
		$arRPath=array_reverse(ClearArray($arPath));
		$i=0;
		$ar=array();
		$olditem='';
		$arMyPAth=array();
		foreach($arRPath as $item)
		{
			if($item!='')
			{
				$path=join('/',array_slice($arPath,0,count($arPath)-$i,true));
				$i++;
				$Commander->Count(array('path'=>'/'.$path.'/','type'=>'dir'));
				$arList=$Commander->GetList(array('name'=>'asc'),array('path'=>'/'.$path.'/','type'=>'dir'),array(0,1000));
				if($olditem!='')
				{
					$arMyPath[]=$arList[$olditem];
				}
				$olditem=$item;
			}
		}
		$arMyPath[]=array('Name'=>'uploads','Full_path'=>'/uploads/');
		$arMyPath=array_reverse($arMyPath);

	}

	$smarty->assign('navPath',$arMyPath);
}
catch (CError $e)
{
	if($e->error==145) $_SESSION['cur_path']='/uploads/';
	if($mode=='ajax')
	{
		 echo json_encode($e);
		 die();
	}
	else
	{
		$smarty->assign('last_error',$e);
	}
}

if($mode=='ajax')
{
	echo json_encode($arMyPath);
	die();
}

$page='_navchain';
?>
