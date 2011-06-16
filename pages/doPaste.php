<?php
/**
 * \file doPaste.php
 * Сюда сделать описание файла
 * Файл проекта CMS-local.
 * 
 * Создан 22.12.2008
 *
 * \author blade39
 * \version 
 * \todo
 */
/*Обязательно вставляем во все файлы для защиты от взлома*/ 
if( !defined('KS_ENGINE') ) {die("Hacking attempt!");}

/*Подключаем библеотеку*/
include_once MODULES_DIR.'/kscommander/libs/CKSCommander.php';
$Commander=new CKSCommander();
$path=$Commander->cur_path;
/*Устанавливаем режим работы*/
$mode=($_GET['mode']!='')?$_GET['mode']:'';

if(array_key_exists('pasteAction',$_SESSION))
{
	$ok=array();
	$wrong=array();
	$copied=0;
	if(count($_SESSION['pasteBuffer'])>0)
	{
		switch($_SESSION['pasteAction'])
		{
			case 'copy':
				foreach($_SESSION['pasteBuffer'] as $from)
				{
					if(file_exists(ROOT_DIR.$path.basename($from)))
					{
						$wrong[]=array('text'=>'Файл <b>'.basename($from).'</b> уже существует!','color'=>'#FF0000');
					}
					else
					{
						if($Commander->CopyFile($from,$path.basename($from)))
						{
							$copied++;
						}
					}
				}
				$ok[]=array('text'=>"Скопированно <b>$copied</b> файлов",'color'=>'#00AA00');
			break;
			case 'cut':
				foreach($_SESSION['pasteBuffer'] as $key=>$from)
				{
					if(file_exists(ROOT_DIR.$path.basename($from)))
					{
						$wrong[]=array('text'=>'Файл <b>'.basename($from).'</b> уже существует!','color'=>'#FF0000');
					}
					else
					{
//echo $path.basename($from);
//echo "**".$from;
					      if(!substr_count($path.basename($from),$from))
					      {
						if($Commander->CopyFile($from,$path.basename($from)))
						{
							if(!$Commander->Delete($from))
							{
								$wrong[]=array('text'=>"Файл <b>$from</b> удалить не удалось",'color'=>'#FF0000');
							}
							else
							{
								$copied++;
								unset($_SESSION['pasteBuffer'][$key]);								
							}
						}
						else
						{
							$wrong[]=array('text'=>"Файл <b>$from</b> вставить не удалось",'color'=>'#FF0000');
						}
					      }
					      else
					      {
						      $wrong[]=array('text'=>"Нельзя вырезать и вставить дирректорию <b>$from</b> саму в себя",'color'=>'#FF0000');
					      }
					}
				}
				$ok[]=array('text'=>"Скопированно <b>$copied</b> файлов",'color'=>'#00AA00');
			break;
		}
	}
	else
	{
	  echo json_encode(array(array('text'=>'В буфере обмена пусто','color'=>'#ff0000')));
	  die();
	}
	
	$smarty->assign('messages',array_merge($ok,$wrong));
	if($mode=='ajax')
	{
		echo json_encode(array_merge($ok,$wrong));
		die();
	}
}
else
{
	echo json_encode(array(array('text'=>'В буфере обмена пусто','color'=>'#ff0000')));
	die();
}
?>
