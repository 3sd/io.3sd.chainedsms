<?php

class CRM_ChainedSMS_Page_Chains extends CRM_Core_Page {
  function run() {

    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml

    $query = "SELECT

      cimt.id AS cimt_id,
      cimt.msg_title AS cimt_msg_title,
      cimt.msg_text AS cimt_msg_text,

      ccc.answer AS ccc_answer,
      ccc.id AS ccc_id,

      csmt.id AS csmt_id,
      csmt.msg_title AS csmt_msg_title,
      csmt.msg_text AS csmt_msg_text

      FROM civicrm_chainedsms_couplet  AS ccc

      JOIN civicrm_msg_template AS cimt
      ON ccc.initial_msg_template_id = cimt.id

      JOIN civicrm_msg_template AS csmt
      ON ccc.subsequent_msg_template_id = csmt.id

      ORDER BY cimt_id, ccc_answer='*' ASC, ccc_answer";

    $result = CRM_Core_DAO::executeQuery($query);

    while($result->fetch()){
      $templates[$result->cimt_id]['answers'][]= array(
          'csmt_id' => $result->csmt_id,
          'csmt_msg_title' => $result->csmt_msg_title,
          'csmt_msg_text' => $result->csmt_msg_text,
          'ccc_id' => $result->ccc_id,
          'ccc_answer' => $result->ccc_answer == '' ? '[all]' : $result->ccc_answer,
      );
      $templates[$result->cimt_id]['cimt_id']=$result->cimt_id;
      $templates[$result->cimt_id]['cimt_msg_title']=$result->cimt_msg_title;
      $templates[$result->cimt_id]['cimt_msg_text']=$result->cimt_msg_text;

    }
    //print_r($templates);exit;
    // Example: Assign a variable for use in a template
    $this->assign('templates', $templates);

    parent::run();
  }
}
