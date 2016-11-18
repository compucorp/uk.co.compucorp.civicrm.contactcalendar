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
    $start = date('Y-m-d H:i:s', CRM_Utils_Array::value('start',$params));
    $end = date('Y-m-d H:i:s', CRM_Utils_Array::value('end',$params));
    $show = CRM_Utils_Array::value('show',$params);
    $hide = CRM_Utils_Array::value('hide',$params);

    require_once 'CRM/Contactcalendar/BAO/Calendar.php';
    $activities = CRM_Contactcalendar_BAO_Calendar::getActivities($cid, $start, $end, $show, $hide);

    $events = array();
    foreach($activities as $k => $activity){

      $events[$k]['cid'] = $activity['cid'];
      $events[$k]['id'] = $activity['id'];
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
    return civicrm_api3_create_success($returnValues, $params, 'Calendar', 'Get');
  }catch (Exception $ex){
    throw new API_Exception($ex, 0001);
  }
}
