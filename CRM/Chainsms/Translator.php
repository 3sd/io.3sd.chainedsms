<?php
class CRM_ChainSMS_Translator {

  function __construct(){
    $this->OutboundSMSActivityTypeId = CRM_Core_OptionGroup::getValue('activity_type', 'SMS', 'name');
    $this->MassSMSActivityTypeId = CRM_Core_OptionGroup::getValue('activity_type', 'Mass SMS', 'name');
    $this->InboundSMSActivityTypeId = CRM_Core_OptionGroup::getValue('activity_type', 'Inbound SMS', 'name');
    $this->SMSDeliveryActivityTypeId = CRM_Core_OptionGroup::getValue('activity_type', 'SMS Delivery', 'name');
    $this->SMSConversationActivityTypeId = CRM_Core_OptionGroup::getValue('activity_type', 'SMS Conversation', 'name');
  }

  function setStartDate($startDate){
    $this->startDate = $startDate;
  }

  function setEndDate($endDate){
    $this->endDate = $endDate;
  }

  function setGroups($groups){
    //expects an array of group ids;
    $this->groups = $groups;
  }

  function setCampaign($campaign){
    // TODO - this should be added to SMS and also to the processor so that all SMS are
    // automatically tagged with a campaign.
    // We might also consider adding parent activity ids to all chain SMS
    $this->campaign = $campaign;
  }

  function prepare(){

    //create an array that contains a stdClass object for each contact

    foreach($this->groups as $group_id){
      $contacts = civicrm_api('GroupContact', 'Get', array('version' => 3, 'rowCount' => '1000000', 'group_id' => $group_id));
      foreach ($contacts['values'] as $contact){
        $this->contacts[$contact['contact_id']] =new CRM_Chainsms_Contact($contact['contact_id']);
      }
    }

    //process inbound SMS
    foreach($this->contacts as $contact){

      //for each contact find inbound SMS (in the time period)
      $smsActivitiesQuery = "SELECT ca.id, ca.activity_date_time, details
        FROM civicrm_activity AS ca
        WHERE activity_type_id = %1 AND source_contact_id = %2 AND activity_date_time BETWEEN %3 AND %4";

      $smsActivitiesParams = array(
        1 => array($this->InboundSMSActivityTypeId, 'Integer'),
        2 => array($contact->id, 'Integer'),
        3 => array($this->startDate, 'String'),
        4 => array($this->endDate, 'String'),
      );

      //remove any contacts without inbound SMS
      $results = CRM_Core_DAO::executeQuery($smsActivitiesQuery, $smsActivitiesParams);
      if(!$results->N){
        unset($this->contacts[$contact->id]);

        //add inbound SMS to the texts array
      }else{
        while($results->fetch()){
          $contact->addText($results->id, 'inbound', $results->activity_date_time, NULL, $results->details);
        }
      }
    }

    //process outbound SMS
    foreach($this->contacts as $contact){

      //foreach contact, get mass SMS that fall within the time period, and the templates these were based on
      $smsActivitiesQuery = "SELECT ca.id, details, ca.activity_date_time, cvcs.message_template_id_82
        FROM civicrm_activity AS ca
        JOIN civicrm_activity_target AS cat ON ca.id=cat.activity_id
        JOIN civicrm_value_chained_sms_16 AS cvcs ON cvcs.entity_id = ca.id
        WHERE activity_type_id = %1 AND target_contact_id = %2 AND activity_date_time BETWEEN %3 AND %4";

      $smsActivitiesParams = array(
        1 => array($this->OutboundSMSActivityTypeId, 'Integer'),
        2 => array($contact->id, 'Integer'),
        3 => array($this->startDate, 'String'),
        4 => array($this->endDate, 'String'),
      );

      $results = CRM_Core_DAO::executeQuery($smsActivitiesQuery, $smsActivitiesParams);

      while($results->fetch()){
        $contact->addText($results->id, 'outbound', $results->activity_date_time, $results->message_template_id_82, $results->details);
      }
    };

    //process mass SMS
    foreach($this->contacts as $contact){

      //foreach contact, get mass SMS that fall within the time period, and the templates these were based on
      $smsActivitiesQuery = "SELECT ca.id, cmt.msg_text, ca.activity_date_time, cm.msg_template_id
        FROM civicrm_activity AS ca
        JOIN civicrm_activity_target AS cat ON ca.id=cat.activity_id
        JOIN civicrm_mailing AS cm ON ca.source_record_id = cm.id
        JOIN civicrm_msg_template AS cmt ON cm.msg_template_id = cmt.id
        WHERE activity_type_id = %1 AND target_contact_id = %2 AND activity_date_time BETWEEN %3 AND %4";

      $smsActivitiesParams = array(
        1 => array($this->MassSMSActivityTypeId, 'Integer'),
        2 => array($contact->id, 'Integer'),
        3 => array($this->startDate, 'String'),
        4 => array($this->endDate, 'String'),
      );

      $results = CRM_Core_DAO::executeQuery($smsActivitiesQuery, $smsActivitiesParams);
      while($results->fetch()){
        $contact->addText($results->id, 'outbound', $results->activity_date_time, $results->msg_template_id, $results->msg_text);
      }
    };


    foreach($this->contacts as $contact){
      ksort($contact->texts);
    }

    // ---***--- FF SPECIFIC CODE ---***---
    //
    // In this particular instance (and this is only relevant for FF) if there is no initial outbound SMS, then work out the message template and make one up


    $yearToMessageTemplateInfo = array(
      '' => 85,
      'eleven' => 75,
      'thirteen' => 83,
      'twelve' => 85,
      'Unknown' => 85,
      'unknown' => 85,
      'Year 11' => 75,
      'Year 13' => 83
    );

    foreach($yearToMessageTemplateInfo as $key => $template_id){
      $messageTemplateParams=array('id'=>$template_id);
      $messageTemplateDefaults=array();
      $messageTemplate = CRM_Core_BAO_MessageTemplates::retrieve($messageTemplateParams, $messageTemplateDefaults);
      $yearToMessageTemplateInfo[$key]=array('id' => $template_id, 'text' => $messageTemplate->msg_text);
    }

    $this->contactsWithMissingOutbound = array();
    foreach($this->contacts as $contact){
      $firstText = current($contact->texts);
      if($firstText['direction'] == 'inbound'){

        $this->contactsWithMissingOutbound[]= $contact->id;

        //find out what year they are in, and then find out what message template to use
        $query = "SELECT what_year_are_you_in__12 AS year FROM civicrm_value_contact_reference_9 WHERE entity_id = %1";
        $params[1] = array($contact->id, 'Integer');
        $result = CRM_Core_DAO::executeQuery($query, $params);
        if($result->fetch()){
          $contact->addText(-1, 'outbound', -1,  $yearToMessageTemplateInfo[$result->year]['id'], $yearToMessageTemplateInfo[$result->year]['text']);
        }else{
          $this->contactsWithMissingOutboundAndNoYearInfo[]= $contact->id;
        }
      }
    }

    //transfer that into the new data structure
    foreach($this->contactsWithMissingOutbound as $c){
      //print_r($this->contacts[$c]);
    }
    //print_r($this->contactsWithMissingOutboundAndNoYearInfo);

    // ---***--- END OF FF SPECIFIC CODE ---***---

    //once all data has been added, clean up the contacts

    foreach($this->contacts as $contact){
      ksort($contact->texts);
    }

  }

  function setTranslatorClass($translatorClass){
    $this->translatorClass = new $translatorClass;
  }

  function translate(){
    foreach ($this->contacts as $contact){
      $this->translatorClass->translate($contact);
    }
  }

  function update(){
    foreach ($this->contacts as $contact){
      /*
      //delete any previous activity for this campaign
      $params = array();
      $params['activity_type_id'] = $this->SMSConversationActivityTypeId;
      $params['version'] = 3;
      $params['subject'] = $this->campaign;
      $params['source_contact_id'] = $contact->id;
      $params['target_contact_id'] = $contact->id;
      $activities = civicrm_api("Activity", "get", $params);
       */

      //params for the new activity
      $params = array();
      $params['activity_type_id'] = $this->SMSConversationActivityTypeId;
      $params['version'] = 3;
      $params['subject'] = $this->campaign;
      $params['source_contact_id'] = $contact->id;
      $params['target_contact_id'] = $contact->id;
      $params['activity_date_time'] = $contact->getDate(); //TODO should be the date of the last text
      $params['details']  = "TEXTS:\n";

      //display the texts
      foreach($contact->texts as $text){
        $params['details']  .= " -> {$text['direction']}: {$text['text']}\n";
      }

      //display the data
      $params['details'] .= "\nDATA:\n".print_r($contact->data, TRUE);


      $this->translatorClass->update($contact);

      if($this->translatorClass->cleanupNecessary($contact)){ 
        //If cleanup is necessary, then set the status to scheduled so that people can come in and clean it up,
        //and also display the errors.
        $params['status_id'] = 1; //scheduled
        //display the errors
        $params['details'] .= "\nERRORS:\n";
        $params['details'] .= $contact->getErrors()."\n\n";

      }else{
        //if no cleanup is necessary, set the activity status to complete
        $params['status_id'] = 2; //completed

      }

      $params['details'] = nl2br($params['details']);
      $result = civicrm_api("Activity", "create", $params);
      if($result['is_error']){
        die(print_r($params));
        print_r($result);exit;//TODO we shouldn't exit processing on an error but we should report on it
      }
    }
  }
}

