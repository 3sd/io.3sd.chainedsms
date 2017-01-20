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
  _chainedsms_civix_civicrm_managed($entities);
}

function chainedsms_civicrm_post( $op, $objectName, $objectId, &$objectRef ){

    //try and return as quickly as possible
    if($objectName!='Activity' || $objectRef->activity_type_id != CRM_Core_OptionGroup::getValue('activity_type', 'Inbound SMS', 'name')){
        return;
    }
    $activity = civicrm_api('Activity', 'getsingle', array('version'=>'3','id' => $objectId));
    $p = new CRM_ChainedSMS_Processor;
    $p->inbound($activity);
}

function chainedsms_civicrm_entityTypes(&$entityTypes){
  $entityTypes['CRM_ChainedSMS_DAO_ChainedSMSCouplet'] = array (
      'name' => 'ChainedSMSCouplet',
      'class' => 'CRM_ChainedSMS_DAO_ChainedSMSCouplet',
      'table' => 'civicrm_chainedsms_couplet',
  );
}

// function chainedsms_civicrm_navigationMenu(&$params) {
//
//   // Check that our item doesn't already exist
//   $menu_item_search = array('url' => 'civicrm/trends');
//   $menu_items = array();
//   CRM_Core_BAO_Navigation::retrieve($menu_item_search, $menu_items);
//
//   if ( ! empty($menu_items) ) {
//     return;
//   }
//
//   $navId = CRM_Core_DAO::singleValueQuery("SELECT max(id) FROM civicrm_navigation");
//   if (is_integer($navId)) {
//     $navId++;
//   }
//   // Find the Report menu
//   $reportID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Reports', 'id', 'name');
//       $params[$reportID]['child'][$navId] = array (
//         'attributes' => array (
//           'label' => ts('Donor Trends',array('domain' => 'org.eff.donortrends')),
//           'name' => 'Donor Trends',
//           'url' => 'civicrm/trends',
//           'permission' => 'access CiviReport,access CiviContribute',
//           'operator' => 'OR',
//           'separator' => 1,
//           'parentID' => $reportID,
//           'navID' => $navId,
//           'active' => 1
//     )
//   );
// }
