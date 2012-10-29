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
	if(!$id = CRM_Utils_Array::value('id', $params)){
		return civicrm_api3_create_error('You need to supply an ID to SMS a contact');
	}
	require_once('CRM/Core/Error.php');

	require_once('CRM/Activity/BAO/Activity.php');
	$tokenText = CRM_Utils_Token::replaceContactTokens('Test', $values, FALSE, $messageToken, FALSE, $escapeSmarty);
	$tokenText = CRM_Utils_Token::replaceHookTokens($tokenText, $values, $categories, FALSE, $escapeSmarty);
	echo $tokenText;

	// for some reason, we need to add some smsParams here - not sure where to get them from ATM
	$smsParams=array();

	CRM_Activity_BAO_Activity::sendSMSMessage($id, $tokenText, $tokenText, $smsParams, 1);
	CRM_Core_Error::debug_log_message('Sending SMS...');

	return civicrm_api3_create_success();
	return civicrm_api3_create_error('Could not SMS contact');
}