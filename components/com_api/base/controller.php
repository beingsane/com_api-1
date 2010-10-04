<?php

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class ApiController extends JController {
	
	public function __construct($config=array()) {
		parent::__construct($config);
		
		JModel::addIncludePath(JPATH_COMPONENT.'/models');
		JTable::addIncludePath(JPATH_COMPONENT.'/tables');
		
	}
	
}