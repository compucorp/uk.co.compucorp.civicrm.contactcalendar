<?php

/**
 * An example API call
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_calendar_get($params) {
  try{
    $cid = CRM_Utils_Array::value('cid',$params);

    $results = civicrm_api("Activity","get", array ('version' => '3','sequential' =>'1',
    'target_contact_id' => $cid ,
    'return.assignee_contact_id' => 1,
    'return.target_contact_id' => 1,
    'api.contact.get' => array( 
      'id' => '$value.source_contact_id',
    ), 'rowCount' => 200));
  
    $events = array();
    foreach($results['values'] as $k => $activity){

      $events[$k]['title'] = $activity['subject'];
      $events[$k]['start'] = $activity['activity_date_time'];

      $duration = $activity['duration'];
      if($duration != NULL && $duration != 0){
        $activityEndTime = date('Y-m-d H:i:s', strtotime("+". $duration ." minutes", strtotime($activity['activity_date_time'])));
        $events[$k]['end'] = $activityEndTime;
        $events[$k]['allDay'] = 0;
      }else{
        $events[$k]['allDay'] = 1;

      }
    }

    // ALTERNATIVE: $returnValues = array(); // OK, success
    // ALTERNATIVE: $returnValues = array("Some value"); // OK, return a single value
    $returnValues = $events;
    return civicrm_api3_create_success($results, $params, 'Calendar', 'Get');
  }catch (Exception $ex){
    throw new API_Exception($ex, 0001);
  }
}

