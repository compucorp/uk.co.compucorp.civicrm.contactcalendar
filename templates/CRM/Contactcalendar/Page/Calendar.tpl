{* use javascript and css directly from extension url as using snippet=1 doesn't load resources on contact tab *}
<script type="text/javascript" src="{$config->extensionsURL}/uk.co.compucorp.civicrm.contactcalendar/resources/js/fullcalendar.min.js"></script>
<style type="text/css">@import url("{$config->extensionsURL}/uk.co.compucorp.civicrm.contactcalendar/resources/css/fullcalendar.css");</style>
<style type="text/css" media="print">@import url("{$config->extensionsURL}/uk.co.compucorp.civicrm.contactcalendar/resources/css/fullcalendar.print.css");</style>

<div id='calendar'></div>

{literal}
<script type="text/javascript">
var crmajaxURL = '{/literal}{php} print base_path(); {/php}{literal}civicrm/ajax/rest';
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
	            cache: false,
	            url: crmajaxURL,
	            dataType: 'json',
	            data: {
	                // our hypothetical feed requires UNIX timestamps
	                start: Math.round(start.getTime() / 1000),
	                end: Math.round(end.getTime() / 1000),
	                cid: {/literal}{$contactId}{literal},
	                entity: 'Calendar',
	                action: 'Get',
	                json: 1,
	                sequential: 1
	            },
	            success: function(data) {
	              var events = new Array();
	              for(index in data.values){    
	                e = data.values[index];
	                ad = false;
	                if(e.allDay == 1){
	                  ad = true;
	                }
	                events.push({
	                      title: e.title,
	                      start: e.start,
	                      end: e.end,
	                      allDay: ad
	                });      
	              }
	              callback(events);
	            }
	        	}); 
	      	} 
	    }); 
	}); //end ready function   
})(jQuery); 
</script>
{/literal}
