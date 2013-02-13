<?php 

class CRM_Contactcalendar_BAO_Calendar{

	/**
	*	Get Calendar event by $contactId
	*/
	static function getActivities($contactId){

    $queryParam = array(1 => array($contactId, 'Integer'));

    //get assignee and target contact
		$query = "
			SELECT activity.id,
						 activity.subject,
						 activity.activity_date_time,
						 activity.duration,
						 activity.status_id
			FROM  civicrm_activity activity
			LEFT JOIN civicrm_activity_assignment assignment ON assignment.activity_id = activity.id
			LEFT JOIN civicrm_activity_target target ON target.activity_id = activity.id
			WHERE (assignment.assignee_contact_id = %1 OR target.target_contact_id = %1)
			AND activity.is_deleted = 0
			AND activity.is_current_revision = 1
			GROUP BY activity.id";

    $activities = array();
    $dao = CRM_Core_DAO::executeQuery($query, $queryParam);
    while ($dao->fetch()) {
    	$activities[$dao->id]['id'] =  $dao->id;
   		$activities[$dao->id]['subject'] =  $dao->subject;
   		$activities[$dao->id]['duration'] =  $dao->duration;
   		$activities[$dao->id]['status_id'] =  $dao->status_id;
   		$activities[$dao->id]['activity_date_time'] =  $dao->activity_date_time;
    }

		return $activities;
	}
}