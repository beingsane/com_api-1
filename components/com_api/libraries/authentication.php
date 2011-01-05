<?php 
defined('_JEXEC') or die;
jimport('joomla.application.component.model');

class ApiAuthentication extends JObject {
	
	protected	$auth_method		= null;
	protected	$domain_checking	= null;
	static		$auth_errors		= array();
	
	public function __construct($params) {
    	parent::__construct($config);
		$this->set('auth_method', $params->get('auth_method', 'key'));
		$this->set('domain_checking', $params->get('domain_checking', 1));
  	}
	
	public function authenticate() {
		// Must be overriden by child authentication class
		ApiError::raiseError(403, JText::_('COM_API_AUTHENTICATION_FAILED'));
	}
	
	public static function authenticateRequest() {
		$params			= JComponentHelper::getParams('com_api');
		$method			= $params->get('auth_method', 'key');
		$className 		= 'APIAuthentication'.ucwords($method);
		$auth_handler 	= new $className($params);
		
		$user_id		= $auth_handler->authenticate();
		
		if ($user_id === false) :
			self::setAuthError($auth_handler->getError());
			return false;
		else :
			$user	= JFactory::getUser($user_id);
			if (!$user->id) :
				self::setAuthError(JText::_("COM_API_USER_NOT_FOUND"));
				return false;
			endif;
			
			if ($user->block == 1) :
				self::setAuthError(JText::_("COM_API_BLOCKED_USER"));
				return false;
			endif;
			
			return $user;
			
		endif;
		
	}
	
	private static function setAuthError($msg) {
		self::$auth_errors[] = $msg;
		return true;
	}
	
	private static function getAuthError() {
		if (empty(self::$auth_errors)) :
			return false;
		endif;
		return array_pop(self::$auth_errors);
	}
	
}