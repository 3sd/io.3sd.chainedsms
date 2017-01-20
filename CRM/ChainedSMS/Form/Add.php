<?php

class CRM_ChainedSMS_Form_Add extends CRM_Core_Form {

  function preProcess(){
  }

  function buildQuickForm() {

    $defaults = array();

    $this->templates = array(0 => '- select -') + CRM_Core_BAO_MessageTemplate::getMessageTemplates(FALSE);

    $this->add( 'hidden', 'id');
    $this->add( 'select', 'initial_msg_template', ts('Initial message'), $this->templates, FALSE);
    $this->add( 'text', 'answer', ts('Answer'), FALSE, FALSE, 'a');
    $this->add( 'select', 'subsequent_msg_template', ts('Subsequent message'), $this->templates, FALSE);

    if($id = CRM_Utils_Array::value('id', $_GET)){
      $query = "SELECT * FROM civicrm_chainedsms_couplet WHERE id=%1";
      $params[1] = array($id, 'Integer');
      $result = CRM_Core_DAO::executeQuery($query, $params);
      $result->fetch();
      $this->assign('answer_for_delete', $result->answer);
      $this->assign('initial_msg_template_for_delete', $result->initial_msg_template_id);
      $this->assign('subsequent_msg_template_for_delete', $result->subsequent_msg_template_id);
      $defaults['initial_msg_template'] = $result->initial_msg_template_id;
      $defaults['subsequent_msg_template'] = $result->subsequent_msg_template_id;
      $defaults['answer'] = $result->answer;
      $defaults['id'] = $result->id;
    }

    if($msg_template_id = CRM_Utils_Array::value('msg_template_id', $_GET)){
      $defaults['initial_msg_template'] = $msg_template_id;
    }

    // set buttons
    if(in_array(CRM_Utils_Array::value('action', $_GET), array('add', 'update'))){
      $buttons[] = array(
        'name' => ts('Save'),
        'type' => 'submit',
        'isDefault' => TRUE,
      );

      $buttons[] = array(
        'type' => 'cancel',
        'name' => ts('Cancel'),
      );

    }elseif(CRM_Utils_Array::value('action', $_GET)=='delete'){
      CRM_Utils_System::setTitle(ts('Delete couplet'));

      $buttons[] = array(
        'name' => ts('Delete'),
        'type' => 'submit',
        'isDefault' => TRUE,
      );

      $buttons[] = array(
        'type' => 'cancel',
        'name' => ts('Cancel'),
      );
    }

    $this->addButtons($buttons);
    $this->setDefaults($defaults);

  }




  function postProcess(){


    $submittedValues = $this->getSubmitValues();
    if($this->_action == CRM_Core_Action::ADD || $this->_action == CRM_Core_Action::UPDATE){
      $params[1] = array($submittedValues['initial_msg_template'], 'Integer');
      $params[2] = array($submittedValues['answer'], 'String');
      $params[3] = array($submittedValues['subsequent_msg_template'], 'Integer');
    }
    if($this->_action == CRM_Core_Action::DELETE || $this->_action == CRM_Core_Action::UPDATE){
      $params[4] = array($submittedValues['id'], 'Integer');
    }
    //if we are updating, update
    if($this->_action == CRM_Core_Action::ADD){
      $query = "INSERT INTO civicrm_chainedsms_couplet SET
        initial_msg_template_id = %1,
        answer = %2,
        subsequent_msg_template_id = %3";
      $result = CRM_Core_DAO::executeQuery($query, $params);
      CRM_Core_Session::setStatus('Your answer has been added');
      CRM_Utils_System::redirect('/civicrm/sms/chains');
      ////set message and redirect
    }elseif($this->_action == CRM_Core_Action::UPDATE){
      $query = "UPDATE civicrm_chainedsms_couplet SET
        initial_msg_template_id = %1,
        answer = %2,
        subsequent_msg_template_id = %3
        WHERE id=%4";
        var_dump($params);
      $result = CRM_Core_DAO::executeQuery($query, $params);
      CRM_Core_Session::setStatus('Your answer has been updated');
      CRM_Utils_System::redirect('/civicrm/sms/chains');

    }elseif($this->_action == CRM_Core_Action::DELETE){
      $query = "DELETE FROM civicrm_chainedsms_couplet
        WHERE id=%4";
      $result = CRM_Core_DAO::executeQuery($query, $params);
      CRM_Core_Session::setStatus('Your answer has been deleted');
      CRM_Utils_System::redirect('/civicrm/sms/chains');
    }
  }
}
