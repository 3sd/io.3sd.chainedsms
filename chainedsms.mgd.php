<?php
$MailingMenuItem = civicrm_api3('Navigation', 'getsingle', array('name' => 'Mailings', 'parent_id' => NULL));

return array(array(
  'name' => 'SMS chains',
  'entity' => 'Navigation',
  'params' => array(
    'version' => 3,
    'name' => 'SMS chains',
    'label' => 'SMS chains',
    'url' => 'civicrm/sms/chains',
    'is_active' => '1',
    'weight' => '30',
    'parent_id' => $MailingMenuItem['id'],
  ),
));
