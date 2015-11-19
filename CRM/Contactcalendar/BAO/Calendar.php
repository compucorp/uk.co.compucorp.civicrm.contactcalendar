<?php 

class CRM_Contactcalendar_BAO_Calendar{

	/**
	*	Get Calendar event by $contactId
	*/
	static function getActivities($contactId, $start, $end, $atypes, $aemailed){

	$queryParam = array(1 => array($contactId, 'Integer'), 2 => array($start, 'String'), 3 => array($end, 'String'), 4 => array($atypes, 'String'));

$query1 = "SELECT activity.id,
activity.subject,
activity.activity_date_time,
activity.duration,
activity.status_id
FROM civicrm_activity activity
LEFT JOIN civicrm_activity_contact assignment ON assignment.activity_id = activity.id
LEFT JOIN civicrm_activity_contact target ON target.activity_id = activity.id
WHERE (assignment.contact_id = %1 OR target.contact_id = %1)
AND activity.activity_date_time BETWEEN %2 AND %3";

$query2 = "AND activity.activity_type_id IN (%4)";

$query3 = "AND activity.status_id != 3";

$query4 = "AND activity.is_deleted = 0
AND activity.is_current_revision = 1
AND activity.medium_id IS NULL
GROUP BY activity.id";

$query = $query1;

if(strtolower($atypes) != "all"){$query = $query." ".$query2;}
if($aemailed == 0){$query = $query." ".$query3;}

$query = $query." ".$query4;

    $activities = array();
    $dao = CRM_Core_DAO::executeQuery($query, $queryParam);
    while ($dao->fetch()) {
	$activities[$dao->id]['id'] =  $dao->id;
	$activities[$dao->id]['cid'] =  $contactId;
	$activities[$dao->id]['subject'] =  $dao->subject;
	$activities[$dao->id]['duration'] =  $dao->duration;
	$activities[$dao->id]['status_id'] =  $dao->status_id;
	$activities[$dao->id]['activity_date_time'] =  $dao->activity_date_time;
    }

		return $activities;
	}
}
