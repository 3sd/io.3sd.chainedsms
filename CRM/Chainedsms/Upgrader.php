<?php

/**
 * Collection of upgrade steps.
 */
class CRM_Chainedsms_Upgrader extends CRM_Chainedsms_Upgrader_Base {

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

  public function install() {
    $this->executeCustomDataFile('xml/customdata.xml');
  }
}
