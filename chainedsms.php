<?php

require_once 'chainedsms.civix.php';

/**
 * Implementation of hook_civicrm_config
 */
function chainedsms_civicrm_config(&$config) {
  _chainedsms_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function chainedsms_civicrm_xmlMenu(&$files) {
  _chainedsms_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function chainedsms_civicrm_install() {
  return _chainedsms_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function chainedsms_civicrm_uninstall() {
  return _chainedsms_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function chainedsms_civicrm_enable() {
  return _chainedsms_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function chainedsms_civicrm_disable() {
  return _chainedsms_civix_civicrm_disable();
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
function chainedsms_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _chainedsms_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function chainedsms_civicrm_managed(&$entities) {
  return _chainedsms_civix_civicrm_managed($entities);
}

function chainedsms_civicrm_post( $op, $objectName, $objectId, &$objectRef ){
 
    //try and return as quickly as possible
    if($objectName!='Activity' || $objectRef->activity_type_id != CRM_Core_OptionGroup::getValue('activity_type', 'Inbound SMS', 'name')){
        return;
    }
    $activity = civicrm_api('Activity', 'getsingle', array('version'=>'3','id' => $objectId));
    $p = new CRM_Chainedsms_Processor;
    $p->inbound($activity);
}

