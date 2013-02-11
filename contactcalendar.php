<?php

require_once 'contactcalendar.civix.php';

/**
*	hook calendar to tabs
*/
function contactcalendar_civicrm_tabs( &$tabs, $contactID ) {

  $url = CRM_Utils_System::url( 'civicrm/contact/calendar/',
                                 "reset=1&cid={$contactID}&snippet=1" );

  $tabs[] = array( 'id'    => 'calendarView',
                   'url'   => $url,
                   'title' => 'Calendar',
                   'weight' => 300 );

}

/**
 * Implementation of hook_civicrm_config
 */
function contactcalendar_civicrm_config(&$config) {
  _contactcalendar_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function contactcalendar_civicrm_xmlMenu(&$files) {
  _contactcalendar_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function contactcalendar_civicrm_install() {
  return _contactcalendar_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function contactcalendar_civicrm_uninstall() {
  return _contactcalendar_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function contactcalendar_civicrm_enable() {
  return _contactcalendar_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function contactcalendar_civicrm_disable() {
  return _contactcalendar_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function contactcalendar_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _contactcalendar_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function contactcalendar_civicrm_managed(&$entities) {
  return _contactcalendar_civix_civicrm_managed($entities);
}
