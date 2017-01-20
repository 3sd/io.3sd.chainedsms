<?php
class CRM_ChainedSMS_Processor{
  function __construct(){
    $this->ChainedSMSTableName = civicrm_api("CustomGroup","getvalue", array ('version' => '3', 'name' =>'Chained_SMS', 'return' =>'table_name'));
    $this->ChainedSMSColumnName = civicrm_api("CustomField","getvalue", array ('version' => '3', 'name' =>'message_template_id', 'return' =>'column_name'));
    $this->OutboundSMSActivityTypeId = CRM_Core_OptionGroup::getValue('activity_type', 'SMS', 'name');
    $this->OutboundMassSMSActivityTypeId = CRM_Core_OptionGroup::getValue('activity_type', 'Mass SMS', 'name');
    $this->InboundSMSActivityTypeId = CRM_Core_OptionGroup::getValue('activity_type', 'Inbound SMS', 'name');
  }

  function inbound($activity){

    // Work out whether this is an answer to a question...

    // Find the most recent outbound text to this person that could be considered a question
    $mostRecentOutboundChainSMS = $this->mostRecentOutboundChainSMS($activity['source_contact_id']);

    //if there is no most recent question, then stop inbound processing
    if($mostRecentOutboundChainSMS->N == 0){
      return 1;
    }



    $mostRecentOutboundChainSMSDate = new DateTime($mostRecentOutboundChainSMS->activity_date_time);
    $inboundSMSDate = new DateTime($activity['activity_date_time']);

    //TODO: if the reply was send longer ago that the response_time_limit then there is no more processing to do
    //if(($inboundSMSDate - $mostRecentOutboundChainSMSDate['date']) > $an_amount_of_time){
    //return 1;
    //}
    //        error_log(print_r($activity->details, true));



    // Has the question been answered already?

    $penultimateInboundSMS = $this->penultimateInboundSMS($activity['source_contact_id']);
    $penultimateInboundSMSDate = new DateTime($penultimateInboundSMS->activity_date_time);

    //if an inbound has been received before this one and it was after we sent the most recent question, then consider this question answered
    if(is_object($penultimateInboundSMS) && $penultimateInboundSMSDate > $mostRecentOutboundChainSMSDate){
      return 1;
    }

    // if it is waiting for a reply, then this inbound message should be treated as a reply to that question
    // TODO - mark that this is an answer

    $nextMessageQuery = "
      SELECT subsequent_msg_template_id, answer
      FROM civicrm_chainedsms_couplet
      WHERE initial_msg_template_id = %1
      ORDER BY answer='*' ASC, answer";


    $nextMessageParams[1]=array($mostRecentOutboundChainSMS->message_template_id, 'Integer');
    //$nextMessageParams=array();

    $nextMessageResult = CRM_Core_DAO::executeQuery($nextMessageQuery, $nextMessageParams);


    while($nextMessageResult->fetch()){

      if(strtolower($nextMessageResult->answer) == strtolower(trim($activity['details'])) || $nextMessageResult->answer == '*'){ //TODO introduce wildcards
        civicrm_api('Contact', 'sms', array('version'=>'3','contact_id' => $activity['source_contact_id'], 'msg_template_id'=>$nextMessageResult->subsequent_msg_template_id));
        break; // only send (and the first encountered text
      }
    }

    return 1;
  }

  function mostRecentOutboundChainSMS($target_contact_id){

    //find the most recent outbound SMS)
    //(this could be SMS or Mass SMS)
    $query="
      SELECT ca.id, ca.activity_date_time, activity_type_id
      FROM civicrm_activity AS ca
      JOIN civicrm_activity_contact AS cac ON cac.activity_id=ca.id AND record_type_id = 3
      WHERE cac.contact_id=%1 AND ca.activity_type_id IN ({$this->OutboundSMSActivityTypeId}, {$this->OutboundMassSMSActivityTypeId})
      ORDER BY activity_date_time DESC
      LIMIT 1;
    ";

    //find out if this is a chain SMS
    //(the method we use to do this depends on whether it is Mass SMS or normal SMS)

    $params[1]=array($target_contact_id, 'Integer');
    $latestOutbound = CRM_Core_DAO::executeQuery($query, $params);
    if(!$latestOutbound->fetch()){
      return 0;
    }
    if($latestOutbound->activity_type_id==$this->OutboundSMSActivityTypeId){

      //we need to look in custom data
      $query = "
        SELECT cd.entity_id AS activity_id, message_template_id, activity_date_time
        FROM {$this->ChainedSMSTableName} AS cd
        JOIN civicrm_activity AS ca ON ca.id=cd.entity_id
        WHERE entity_id=%1";
      $params[1]=array($latestOutbound->id, 'Integer');

      $latestOutboundSMS = CRM_Core_DAO::executeQuery($query, $params);
      $latestOutboundSMS->fetch();

    }elseif($latestOutbound->activity_type_id==$this->OutboundMassSMSActivityTypeId){

      //we need to look in the mailing
      $query = "
        SELECT ca.id AS activity_id, cm.msg_template_id, activity_date_time
        FROM civicrm_activity AS ca
        JOIN civicrm_activity_contact AS cac ON cac.activity_id=ca.id AND record_type_id = 2
        JOIN civicrm_mailing AS cm ON ca.source_record_id=cm.id
        WHERE ca.id=%1";
      $params[1]=array($latestOutbound->id, 'Integer');

      $latestOutboundSMS = CRM_Core_DAO::executeQuery($query, $params);
      $latestOutboundSMS->fetch();
      //echo 'this is a mass SMS';exit;

    }
    return $latestOutboundSMS;
  }

  function penultimateInboundSMS($source_contact_id){
    $query="
      SELECT
      *
      FROM
      civicrm_activity AS ca
      JOIN civicrm_activity_contact AS cac ON cac.activity_id=ca.id AND record_type_id = 2
      WHERE
      activity_type_id={$this->InboundSMSActivityTypeId} AND
      cac.contact_id=%1
      ORDER BY
      activity_date_time DESC
      LIMIT 1,1
      ";

    $params[1]=array($source_contact_id, 'Integer');
    $activity = CRM_Core_DAO::executeQuery($query, $params);
    if($activity->fetch()){
      return $activity;
    }else{
      return 0;
    }
  }

  function outbound($question_id){
    $this->addMessage('out', $this->questions[$question_id]['text'], $question_id);
    echo "OUTBOUND: {$this->questions[$question_id]['text']}\n";
  }


  function addMessage($type, $text, $question_id=null){
    return $this->messages[]=array('date' => mktime(), 'type' => $type, 'text' => $text, 'question_id' => $question_id);
  }

  function printMessages(){
    print_r($this->messages);
  }

}
