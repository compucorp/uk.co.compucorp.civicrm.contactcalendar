<?php

require_once 'CRM/Core/Page.php';

class CRM_Contactcalendar_Page_Calendar extends CRM_Core_Page {

  public $_contactId = null;

  function run() {
    
    $this->_contactId = CRM_Utils_Request::retrieve( 'cid', 'Positive', $this, true );
    $this->assign( 'contactId', $this->_contactId );

    // check logged in url permission
    require_once 'CRM/Contact/Page/View.php';
    CRM_Contact_Page_View::checkUserPermission( $this );
        
    $this->_action = CRM_Utils_Request::retrieve('action', 'String', $this, false, 'browse');
    $this->assign( 'action', $this->_action);
	
    parent::run();
  }
}
