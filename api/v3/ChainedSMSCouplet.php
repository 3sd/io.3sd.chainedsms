<?php

/**
 * ChainedSMSCouplet.create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_chained_s_m_s_couplet_create_spec(&$spec) {
  $spec['answer']['api.required'] = 1;
  $spec['initial_msg_template_id']['api.required'] = 1;
  $spec['subsequent_msg_template_id']['api.required'] = 1;
}

/**
 * ChainedSMSCouplet.create API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_chained_s_m_s_couplet_create($params) {
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * ChainedSMSCouplet.delete API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_chained_s_m_s_couplet_delete($params) {
  return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * ChainedSMSCouplet.get API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_chained_s_m_s_couplet_get($params) {
  return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}
