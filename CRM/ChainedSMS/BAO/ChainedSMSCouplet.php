<?php

class CRM_ChainedSMS_BAO_ChainedSMSCouplet extends CRM_ChainedSMS_DAO_ChainedSMSCouplet {

  /**
   * Create a new ChainedSMSCouplet based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_ChainedSMS_DAO_ChainedSMSCouplet|NULL
   *
  public static function create($params) {
    $className = 'CRM_ChainedSMS_DAO_ChainedSMSCouplet';
    $entityName = 'ChainedSMSCouplet';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
