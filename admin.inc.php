<?php

/**
 * KS Engine ADMIN SYSTEM

File: KSCommander.php
Original Code by BlaDe39 (c) 2008
Назначение: управление файлами

Добавлена работа с сессиями, это связанно с необходимостью хранить промежуточные
значения при работе аякс запросов.
\version 2.1
*/
if( !defined('KS_ENGINE') ){  die("Hacking attempt!");}

global $KS_URL;

$module_name='kscommander';

if($USER->GetLevel($module_name)>9) throw new CAccessError('KSCOMMANDER_ACCESS_DENIED');

/* Обработка работы сессии */
session_name('kscommander');
session_start();

/* Обработка действий с различными блоками */
if (($_GET['page'] != '') && (preg_match('#^[a-zA-Z_]+$#', $_GET['page'])))
{
	$pagefilename = MODULES_DIR . '/kscommander/pages/' . $_GET['page'] . '.php';
	if (file_exists($pagefilename))
		include $pagefilename;
}
else
{
	
	/* Блок не нашли, значит, работаем по старинке */
	include_once MODULES_DIR.'/kscommander/libs/CKSCommander.php';
	global $ks_db;
	$Commander=new CKSCommander();
	if(!array_key_exists('count',$_GET))$_GET['count']=$_SESSION['count'];else$_SESSION['count']=$_GET['count'];
	if(!array_key_exists('p',$_GET))$_GET['p']=$_SESSION['p'];else$_SESSION['p']=$_GET['p'];
	/*Обработка сортировки*/
	$arSortFields=array('name','ext','size');
	$sOrderField=(in_array($_REQUEST['order'],$arSortFields))?$_REQUEST['order']:'id';
	if($_REQUEST['dir']=='asc'):$sOrderDir='asc';$sNewDir='desc';else: $sOrderDir='desc';$sNewDir='asc';endif;
	$arOrder=array(
		$sOrderField=>$sOrderDir,
	);
	$showList=true;
	
	$statistics=Array(
	"cur_path"=>$Commander->cur_path,
	"object"=>$_REQUEST['CKS_object']);
	
	$size = disk_free_space(ROOT_DIR);
	$a = array("Байт", "КБайт", "МБайт", "ГБайт");
	$pos = 0;while($size >= 1024){$size /= 1024;$pos++;}
	$statistics['total_free_space'] = number_format($size,2,',',' ')." ".$a[$pos]; 
    $size = disk_total_space(ROOT_DIR);
	$pos = 0;while($size >= 1024){$size /= 1024;$pos++;}
	$statistics['total_disk_space'] = number_format($size,2,',',' ')." ".$a[$pos]; 
	$size = disk_total_space(ROOT_DIR)-disk_free_space(ROOT_DIR);
	$pos = 0;while($size >= 1024){$size /= 1024;$pos++;}
	$statistics['total_occup_space'] = number_format($size,2,',',' ')." ".$a[$pos];    
	
	if (array_key_exists('CKS_ACTION',$_REQUEST))
	{
		$action=$_REQUEST['CKS_ACTION'];
		if ($action=='upload_form')
		{
			$page='_upload';
			$showList=false;
		}
		if ($action=='newdir_form')
		{
			$inpt['message']='Название:<br/><small>Допустимо использование только латинских букв A-Z, цифр 0-9, знаков - и _</small>';
			$inpt['inptname']='CKS_newdir';
			$inpt['vars']=Array('CKS_path'=>$Commander->cur_path,'CKS_ACTION'=>'newdir');
			$smarty->assign('input',$inpt);
			$page='_inptbox';
			$showList=false;
		}
		if ($action=='newdir')
		{
			try
			{
				if(!preg_match('#^[a-zA-Z0-9\-_]+$#',$_POST['CKS_newdir'])) throw new CError('KSCOMMANDER_WRONG_DIR_NAME');
				$result=$Commander->Newdir($_POST['CKS_newdir']);
				$statistics['message']='<div style="width:300px;border:1px solid #a0afa0;background-color:#F0FFF0;padding:4px;">';
				if ($result)
				{
					$statistics['message'].='Каталог  '.$_REQUEST['CKS_newdir'].' <font color=#00AA00><b>успешно создан</b></font>';
				}
				else
				{
					$statistics['message'].='Каталог  '.$_REQUEST['CKS_newdir'].' <font color=#FF0000><b>создать не удалось</b></font>';
				}
				$statistics['message'].='</div>';
			}
			catch (CError $e)
			{
				$statistics['message']='<div style="width:300px;border:1px solid #a0afa0;background-color:#F0FFF0;padding:4px;">';
				$statistics['message'].='Каталог  '.$_REQUEST['CKS_newdir'].' <font color=#FF0000><b>создать не удалось</b></font> причина: '.$e->GetErrorText();
				$statistics['message'].='</div>';	
			}	
		}
		if ($action=='upload')
		{
			$result=$Commander->Upload();
			$smarty->assign('uplist',$result);
			$statistics['message']=$smarty->fetch('admin/kscommander_uploadres.tpl');
		}
		if ($action=='delete')
		{
			$smarty->assign('message','Вы действительно хотите удалить файл: '.$_REQUEST['CKS_object'].'?');
			$page='_msgbox';
			$showList=false;
		}
		if ($action=='delete_confirm')
		{
			$result=$Commander->Del();
			$statistics['message']='<div style="width:300px;border:1px solid #a0afa0;background-color:#F0FFF0;padding:4px;">';
			if ($result)
			{
				$statistics['message'].='Файл  '.$_REQUEST['CKS_object'].' <font color=#00AA00><b>успешно удален</b></font>';
			}
			else
			{
				$statistics['message'].='Файл  '.$_REQUEST['CKS_object'].' <font color=#FF0000><b>удалить не удалось</b></font>';
			}
			$statistics['message'].='</div>';
		}
	}
	
	if($showList)
	{
		$obPages=new CPageNavigation($Commander,false,$_GET['count']);
		$Commander->SetSort($sOrderField,$sOrderDir);
		try
		{
			$totalFiles=$Commander->Count(array('path'=>$Commander->cur_path));
			$list=$Commander->GetList(array($sOrderField=>$sOrderDir),array('path'=>$Commander->cur_path),$obPages->GetLimits($totalFiles));
			/*Обработка дерева файлов слева*/
			$arDirs=$Commander->GetList(array('name'=>'asc'),array('path'=>'/uploads/','type'=>'dir'),array(0,1000));
			if($Commander->cur_path!='/')
			{
				$arPath=ClearArray(explode('/',$Commander->cur_path));
				$arRPath=array_reverse(ClearArray($arPath));
				$i=0;
				$ar=array();
				$olditem='';
				foreach($arRPath as $item)
				{
					if($item!='')
					{
						$path=join('/',array_slice($arPath,0,count($arPath)-$i,true));
						$i++;
						$Commander->Count(array('path'=>'/'.$path.'/','type'=>'dir'));
						$arList=$Commander->GetList(array('name'=>'desc'),array('path'=>'/'.$path.'/','type'=>'dir'),array(0,1000));
						if(count($ar)>0)
						{
							$arList[$olditem]['is_open']=1;
							$arMyPath[]=$arList[$olditem];
							$arList[$olditem]=array_merge($arList[$olditem],$ar[$olditem]);
							unset($ar[$olditem]);
						}
						if($path!='uploads')
						{
						    $ar[$item]=array_merge($ar,array('sub'=>array_reverse($arList)));
						}
						else
						{
						  $ar[$item]=array_merge($ar,$arList);
						}
						$olditem=$item;

					}
				}
				$ar[$path]['is_open']=1;
				$arMyPath[]=$ar[$path];
				$arMyPath[]=array('Name'=>'uploads','Full_path'=>'/uploads/');

				$arDirs=$ar[$path];
				$arMyPath=array_reverse($arMyPath);
			}
			$_SESSION['cur_path']=$Commander->cur_path;
			$smarty->assign('navPath',$arMyPath);
			$smarty->assign('currentPath',$Commander->cur_path);
			$smarty->assign('dirlist',$arDirs);
			$smarty->assign('pages',$obPages->GetPages($totalFiles));
			$smarty->assign('num_visible',$obPages->GetVisible());
			$smarty->assign('order',Array('newdir'=>$sNewDir,'curdir'=>$sOrderDir,'field'=>$sOrderField));
			$smarty->assign('list',$list);
		}
		catch (CError $e)
		{
			$smarty->assign('last_error',$e);
			if($e->error==145)
			{
				$_SESSION['cur_path']='/uploads/';
			}
			else
			{
				throw $e;
			}
		}
	}
	else
	{
		try
		{
			/*Обработка дерева файлов слева*/


			$arDirs=$Commander->GetList(array('name'=>'asc'),array('path'=>'/uploads/','type'=>'dir'),array(0,1000));
			if($Commander->cur_path!='/')
			{
				$arPath=ClearArray(explode('/',$Commander->cur_path));
				$arRPath=array_reverse(ClearArray($arPath));
				$i=0;
				$ar=array();
				$olditem='';
				foreach($arRPath as $item)
				{
					if($item!='')
					{
						$path=join('/',array_slice($arPath,0,count($arPath)-$i,true));
						$i++;
						$Commander->Count(array('path'=>'/'.$path.'/','type'=>'dir'));
						$arList=$Commander->GetList(array('name'=>'asc'),array('path'=>'/'.$path.'/','type'=>'dir'),array(0,1000));
						if(count($ar)>0)
						{
							$arList[$olditem]['is_open']=1;
							$arMyPath[]=$arList[$olditem];
							$arList[$olditem]=array_merge($arList[$olditem],$ar[$olditem]);
							unset($ar[$olditem]);
						}
						if($path!='uploads')
						{
						    $ar[$item]=array_merge($ar,array('sub'=>$arList));
						}
						else
						{
						  $ar[$item]=array_merge($ar,$arList);
						}
						$olditem=$item;
					}
				}
					
				$arMyPath[]=$ar[$path];
				$arMyPath[]=array('Name'=>'uploads','Full_path'=>'/uploads/');

				$arDirs=$ar[$path];
				$arMyPath=array_reverse($arMyPath);
			}
			$_SESSION['cur_path']=$Commander->cur_path;
			$smarty->assign('navPath',$arMyPath);
			$smarty->assign('currentPath',$Commander->cur_path);
			$smarty->assign('dirlist',$arDirs);
		}
		catch (CError $e)
		{
			$smarty->assign('last_error',$e);
			if($e->error==145)
			{
				$_SESSION['cur_path']='/uploads/';
			}
			else
			{
				throw $e;
			}
		}
	}
	
	$smarty->assign('statistics',$statistics);
	if($_GET['mode']=='ajax')
	{
		//if (isset($_GET['ksMode']))
		//	$smarty->assign('type', $_GET['ksMode']);
		if (isset($_GET['type']))
			$smarty->assign('type', $_GET['type']);
		$smarty->assign('page',$page);
		$smarty->display('admin/kscommander_ajax'.$page.'.tpl');
		die();
	}
}


?>