<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

$library_path = JPATH_COMPONENT.'/libraries';

JLoader::register('APIController', $library_path.'/controller.php');
JLoader::register('APIModel', $library_path.'/model.php');
JLoader::register('APIView', $library_path.'/view.php');
JLoader::register('APIPlugin', $library_path.'/plugin.php');
JLoader::register('APIError', $library_path.'/error.php');
JLoader::register('APICache', $library_path.'/cache.php');
JLoader::register('APIAuthentication', $library_path.'/authentication.php');
JLoader::register('APIAuthenticationKey', $library_path.'/authenticationkey.php');

$view	= JRequest::getCmd('view', '');
if ($view) :
	$c	= $view;
else :
	$c	= JRequest::getCmd('c', 'http');
endif;

$c_path	= JPATH_COMPONENT.'/controllers/'.strtolower($c).'.php';
if (file_exists($c_path)) :
	include_once $c_path;
	$c_name	= 'ApiController'.ucwords($c);
else :
	JError::raiseError(404, JText::_('COM_API_CONTROLLER_NOT_FOUND'));
endif;

$command = JRequest::getCmd('task', 'display');

$controller = new $c_name();
$controller->execute($command);
$controller->redirect();