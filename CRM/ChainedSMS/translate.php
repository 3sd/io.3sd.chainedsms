<?php
require_once '/projects/ff/drupal/sites/all/modules/civicrm/civicrm.config.php' ;
require_once  'CRM/Core/Config.php';
CRM_Core_Config::singleton( );

//include('Translator.php');
//include('Translator/FFNov12.php');
//include('Contact.php');

// * Create a cleaner object

$translator = new CRM_ChainedSMS_Translator;


// * Add the data to be cleaned to the cleaner object

// ** define a group of contacts that were part of the cleanup

// 219: all people that replied to a text
// 193: year-11-7
// 185: year-12-unknown-7
// 170: year-13-7
$translator->setGroups(array(170,185,193));
//$translator->setGroups(array(219));
//$translator->setGroups(array(164));
// ** define a start date for activities

$translator->setStartDate('2012-11-01');

// ** define an end date for activties

$translator->setEndDate('2013-02-08');

$translator->prepare();

// * Run the cleaning script
$translator->setTranslatorClass("CRM_ChainedSMS_Translator_FFNov12");
$translator->setCampaign("November Tracking 2012");

$translator->translate();
$translator->update();

