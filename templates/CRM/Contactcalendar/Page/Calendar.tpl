{* use javascript and css directly from extension url as using snippet=1 doesn't load resources on contact tab *}
<script type="text/javascript" src="{$config->extensionsURL}/uk.co.compucorp.civicrm.contactcalendar/resources/js/fullcalendar.min.js"></script>
<style type="text/css">@import url("{$config->extensionsURL}/uk.co.compucorp.civicrm.contactcalendar/resources/css/fullcalendar.css");</style>
<style type="text/css" media="print">@import url("{$config->extensionsURL}/uk.co.compucorp.civicrm.contactcalendar/resources/css/fullcalendar.print.css");</style>

<div id='calendar'></div>
{literal}
<script type="text/javascript">
var crmajaxURL = '{/literal}{php} print base_path(); {/php}{literal}civicrm/ajax/rest';
var CiviBorderColor = "#36c";
var ActivityType = 1;

(function ($) {
	jQuery(document).ready(function () {
	    jQuery('#calendar').fullCalendar({
	    	header: {
	        	left: 'prev,next, today',
	        	center: 'title',
	        	right: 'month,agendaWeek,agendaDay'
	      	},
	      	defaultView: 'month',
	      	firstDay: 1,
	      	allDaySlot: true,
	      	slotMinutes: 15,
	    		events: function(start, end, callback) {
		      	jQuery.ajax({
	            async: false,
	            cache: false,
	            url: crmajaxURL,
	            dataType: 'json',
	            data: {
	                // our hypothetical feed requires UNIX timestamps
	                start: Math.round(start.getTime() / 1000),
	                end: Math.round(end.getTime() / 1000),
	                cid: {/literal}{$contactId}{literal},
	                atypes: 'all', // Activity Types (all = All types or Activity Type numbers separated by commas)
	                aemailed: 0, // Show emailed copy of Activity (1 = show, 0 = hide)
	                entity: 'Calendar',
	                action: 'Get',
	                json: 1,
	                sequential: 1
	            },
	            success: function(data) {
	              var events = new Array();
	              for(index in data.values){    
	                e = data.values[index];

					jQuery.ajax({
						async: false,
						cache: false,
						url: crmajaxURL,
						dataType: 'json',
						data: {
							id: e.id,
							entity: 'Activity',
							action: 'Get',
							json: 1,
							sequential: 1
						},
						success: function(data) {
							var aevents = new Array();
							for(aindex in data.values){    
								ae = data.values[aindex];

								TheColors = CiviColor(parseInt(ae.activity_type_id));
								var now = '{/literal}{php} print date("Y-m-d H:i:s"); {/php}{literal}';

								var a=ae.activity_date_time.split(" ");
								var b=a[0].split("-");
								var c=a[1].split(":");
								var TimeStart = new Date(b[0],(b[1]-1),b[2],b[0],c[1],c[2]);

								var a=now.split(" ");
								var b=a[0].split("-");
								var c=a[1].split(":");
								var TimeNow = new Date(b[0],(b[1]-1),b[2],b[0],c[1],c[2]);

								TimeStartString = Math.round(TimeStart.getTime() / 1000);
								TimeNowString = Math.round(TimeNow.getTime() / 1000);

								if ((parseInt(ae.status_id) != 2) && (TimeStartString < TimeNowString) && (TheColors[2] != "Default")) { // Past Due
									CiviBorderColor = '#FF0000';
									// TheColors[1] = CiviBorderColor;
								}else if (TheColors[2] != "Default"){ // Not Past Due
									switch(parseInt(ae.status_id)) {
										case 1: // Scheduled
											CiviBorderColor = 'white';
											// TheColors[1] = CiviBorderColor;
											break;
										case 2: // Completed
											CiviBorderColor = 'Grey';
											// TheColors[1] = CiviBorderColor;
											break;
										default: //default code block
											CiviBorderColor = 'white';
											// TheColors[1] = CiviBorderColor;
									}
								}else {
									CiviBorderColor = 'white';
									// TheColors[1] = CiviBorderColor;
								}
							}
						}
					});

	                ad = false;
	                if(e.allDay == 1){
	                  ad = true;
	                }
	                events.push({
	                      title: e.title,
	                      start: e.start,
	                      end: e.end,
	                      color: TheColors[0],
	                      textColor: TheColors[1],
	                      borderColor: CiviBorderColor,
	                      allDay: ad,
	                      url: "{/literal}{php} print base_path(); {/php}{literal}civicrm/activity?action=view&id="+e.id+"&cid="+e.cid
	                });      
	              }
	              callback(events);
	            }
	        	}); 
	      	} 
	    });
	}); //end ready function

	function CiviColor(ActivityType){
		color = new Array();
		switch(ActivityType) {
			case 19: // Bulk Email
			case 37: // Cancel Recurring Contribution
			case 35: // Change Membership Status
			case 36: // Change Membership Type
			case 48: // Change Registration
			case 51: // Contact Merged
			case 6:  // Contribution
			case 42: // Create Batch
			case 49: // Downloaded Invoice
			case 43: // Edit Batch
			case 3:  // Email
			case 50: // Emailed Invoice
			case 5:  // Event Registration
			case 41: // Export Accounting Batch
			case 52: // Failed Payment
			case 12: // Inbound Email
			case 45: // Inbound SMS
			case 34: // Mass SMS
			case 1:  // Meeting
			case 8:  // Membership Renewal
			case 17: // Membership Renewal Reminder
			case 7:  // Membership Signup
			case 4:  // Outbound SMS
			case 46: // Payment
			case 2:  // Phone Call
			case 10: // Pledge Acknowledgment
			case 11: // Pledge Reminder
			case 22: // Print PDF Letter
			case 47: // Refund
			case 40: // Reminder Sent
			case 44: // SMS delivery
			case 9:  // Tell a Friend
			case 39: // Update Recurring Contribution
			case 38: // Update Recurring Contribution Billing Details
				BGcolor = "#36c";
				TextColor = "#FFF";
				Type = "Not Default";
				break;
			default: // Other Activity Types
				BGcolor = "#36c";
				TextColor = "#FFF";
				Type = "Default";
		}

		color.push(BGcolor);
		color.push(TextColor);
		color.push(Type);

		return color;
	}
})(jQuery); 
</script>
{/literal}
