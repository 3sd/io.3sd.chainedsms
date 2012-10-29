<?php

/**
 * Send a single SMS to a contact.
 *
 * @param  array   $params   input parameters
 *
 * Allowed @params array keys are:
 * {int     id     			id of the contact that you want to SMS}
 * {int     text	    	the text of the SMS that you want to send}
 *
 * @return array  API Result Array
 *
 * @static void
 * @access public
 */

function civicrm_api3_contact_sms($params) {
		
	//This API makes everything nice for / wraps around
	//CRM_Activity_BAO_Activity::sendSMS()
	
	//it would be nice to be able to chain this by sending the results of a Contact.get
	

	//get the list of contacts that you want to send the SMS to
	if(!isset($params['id'])){
		return civicrm_api3_create_error('Please include a contact id.');
	}
	$contactsResult = civicrm_api('Contact', 'get', array('version'=>3, 'id' => $params['id']));
	//$contactsResult = civicrm_api('Contact', 'get', $params);
	
	if(!$contactsResult['count']){
		return civicrm_api3_create_error('Please specify at least one contact.');
	}
	$contactDetails = $contactsResult['values'];
	//idea is that this contact will take a contact ID and a text message and then send an SMS
	
	foreach($contactDetails as $contact){
		$contactIds[]=$contact['contact_id'];
	}
	
	// use the default SMS provider
	$providers=CRM_SMS_BAO_Provider::getProviders(NULL, array('is_default' => 1));
	$provider = $providers[0];
	$provider['provider_id'] = $provider['id'];
	
	//this should be set somehow when not set (or maybe we need to change the underlying BAO to not require it?)
	$userID=1;
	
	$activityParams['text_message']=$params['text'];
	
	CRM_Activity_BAO_Activity::sendSMS($contactDetails, $activityParams, $provider, $contactIds, $userID);
	
	return civicrm_api3_create_success();
		


}