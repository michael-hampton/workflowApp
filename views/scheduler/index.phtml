<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<!-- https://github.com/robmonie/jquery-week-calendar/blob/master/weekcalendar_demo_2.html -->

<link href="/FormBuilder/public/css/scheduler.css" type="text/css" rel="stylesheet">

<div class="wrapper wrapper-content">
    <div class="row animated fadeInDown">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Striped Table </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Config option 1</a>
                            </li>
                            <li><a href="#">Config option 2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div id="message" class="ui-corner-all"></div>
                    <div id="calendar_selection" class="ui-corner-all">
                        <strong>Event Data Source: </strong>
                        <select id="data_source">
                            <option value="">Select Event Data</option>
                            <option value="1">Event Data 1</option>
                            <option value="2">Event data 2</option>
                        </select>
                    </div>
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--
                pop-up container,
                which will be shown when timeslot select
-->
<div id="event_edit_container" style="display: none;">
    <form>
        <input type="hidden" />
        <ul>
            <li>
                <span>Date: </span><span class="date_holder"></span> 
            </li>
            <li>
                <label for="start">Start Time: </label><select name="start"><option value="">Select Start Time</option></select>
            </li>
            <li>
                <label for="end">End Time: </label><select name="end"><option value="">Select End Time</option></select>
            </li>
            <li>
                <label for="title">Title: </label><input type="text" name="title" />
            </li>
            <li>
                <label for="body">Body: </label><textarea name="body"></textarea>
            </li>
        </ul>
    </form>
</div>


<script>

    $ (document).ready (function ()
    {

        var year = new Date ().getFullYear ();
        var month = new Date ().getMonth ();
        var day = new Date ().getDate ();
        var eventData = {
            events: [
                {"id": 1, "team_id": 1, "priority": "high", "team_member": "michael.hampton", "start": new Date (year, month, day, 12), "end": new Date (year, month, day, 13, 35), "title": "Lunch with Mike"},
                {"id": 2, "team_id": 2, "priority": "medium", "team_member": "lexi.hampton", "start": new Date (year, month, day, 14), "end": new Date (year, month, day, 14, 45), "title": "Dev Meeting"},
                {"id": 3, "team_id": 3, "priority": "low", "team_member": "jhoanna.hampton", "start": new Date (year, month, day + 1, 18), "end": new Date (year, month, day + 1, 18, 45), "title": "Hair cut"},
                {"id": 4, "team_id": 4, "priority": "high", "team_member": "paul.hampton", "start": new Date (year, month, day - 1, 8), "end": new Date (year, month, day - 1, 9, 30), "title": "Team breakfast"},
                {"id": 5, "team_id": 5, "priority": "medium", "team_member": "jackie.hampton", "start": new Date (year, month, day + 1, 14), "end": new Date (year, month, day + 1, 15), "title": "Product showcase"}
            ]
        };

        /*
         store calendar dom into variable
         */
        var $calendar = $ ('#calendar');
        var id = 10;
        $calendar.weekCalendar ({
            /* Calendar Options goes to _computeOptions */


            /*
             define slot between each hours,
             4 define 4 time slot,
             which indicate 15 minutes,
             2 define 2 times slot,
             which indicate 30 minutes,
             between each hours
             */
            timeslotsPerHour: 4,
            /*
             [boolean | default: false]
             Whether the calendar will allow events to overlap.
             Events will be moved or resized if necessary
             if they are dragged or resized to a location
             that overlaps another calendar event.
             */
            allowCalEventOverlap: true,
            /*
             [boolean | default: false]
             If true,
             events that overlap will be rendered separately without any overlap.
             */
            overlapEventsSeparate: true,
            /*
             [integer | default: 0]
             Determines what day of the week to start on.
             0 = sunday, 1 = monday etc
             */
            firstDayOfWeek: 1,
            /*
             [object | default:
             {start: 8, end: 18, limitDisplay: false}]
             An object that specifies which hours within the day to render as
             */
            businessHours: {start: 8, end: 18, limitDisplay: true},
            /*
             [integer | default: 7]
             Determines how many days to show.
             Note that next/prev weekly navigation is still
             based on weeks rather than the number of days displaying
             */
            daysToShow: 7,
            // Show buttons
            buttons: true,
            /* Text for buttons */
            buttonText: {
                today: "today",
                lastWeek: "&nbsp;&lt;&nbsp;",
                nextWeek: "&nbsp;&gt;&nbsp;"
            },
            /* Basic Configuration */
            // set default date
            date: new Date (),
            timeFormat: "h:i a",
            dateFormat: "M d, Y",
            alwaysDisplayTimeMinutes: true,
            use24Hour: false,
            useShortDayNames: false,
            shortMonths: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            longMonths: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            shortDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            longDays: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            // Events
            data: eventData,
            readonly: false,
            timeSeparator: " to ",
            startParam: "start",
            endParam: "end",
            newEventText: "New Event",
            timeslotHeight: 20,
            defaultEventLength: 2,
            calendarBeforeLoad: function (calendar)
            {
            },
            /*
             Called prior to rendering an event.
             Allows modification of the eventElement
             or the ability to return a different element
             */
            eventRender: function (calEvent, $event)
            {
                if (calEvent.end.getTime () < new Date ().getTime ())
                {
                    $event.css ("backgroundColor", "#aaa");
                    $event.find (".wc-time").css ({
                        "backgroundColor": "#999",
                        "border": "1px solid #888"
                    });
                }
            },
            draggable: function (calEvent, element)
            {
                return true;
            },
            resizable: function (calEvent, element)
            {
                return true;
            },
            eventAfterRender: function (calEvent, element)
            {
                return element;
            },
            calendarAfterLoad: function (calendar)
            {
            },
            eventDrag: function (calEvent, element)
            {
            },
            eventDrop: function (calEvent, element)
            {
            },
            eventResize: function (calEvent, element)
            {
            },
            /*
             Called on creation of a new calendar event
             */
            eventNew: function (calEvent, $event)
            {
                /*
                 we are creating dialog box,
                 which will be shown when event is selected.
                 */
                var $dialogContent = $ ("#event_edit_container");
                resetForm ($dialogContent);
                var startField = $dialogContent.find ("select[name='start']").val (calEvent.start);
                var endField = $dialogContent.find ("select[name='end']").val (calEvent.end);
                var titleField = $dialogContent.find ("input[name='title']");
                var bodyField = $dialogContent.find ("textarea[name='body']");

                $dialogContent.dialog (
                        {
                            modal: true,
                            title: "New Calendar Event",
                            close: function ()
                            {
                                $dialogContent.dialog ("destroy");
                                $dialogContent.hide ();
                                $ ('#calendar').weekCalendar ("removeUnsavedEvents");
                            },
                            buttons:
                                    {
                                        save: function ()
                                        {
                                            calEvent.id = id;
                                            id++;
                                            calEvent.start = new Date (startField.val ());
                                            calEvent.end = new Date (endField.val ());
                                            calEvent.title = titleField.val ();
                                            calEvent.body = bodyField.val ();

                                            $calendar.weekCalendar ("removeUnsavedEvents");
                                            $calendar.weekCalendar ("updateEvent", calEvent);
                                            $dialogContent.dialog ("close");
                                        },
                                        cancel: function ()
                                        {
                                            $dialogContent.dialog ("close");
                                        }
                                    }
                        }).show ();

                $dialogContent.find (".date_holder").text ($calendar.weekCalendar ("formatDate", calEvent.start));
                setupStartAndEndTimeFields (startField, endField, calEvent, $calendar.weekCalendar ("getTimeslotTimes", calEvent.start));
            },
            eventMouseover: function (calEvent, $event)
            {
            },
            eventMouseout: function (calEvent, $event)
            {
            },
            /*
             Called on click of a calendar event
             */
            eventClick: function (calEvent, $event)
            {
                if (calEvent.readOnly)
                {
                    return;
                }
                /*
                 calling dialog box with events data filled in.
                 */
                var $dialogContent = $ ("#event_edit_container");
                resetForm ($dialogContent);
                var startField = $dialogContent.find ("select[name='start']").val (calEvent.start);
                var endField = $dialogContent.find ("select[name='end']").val (calEvent.end);
                var titleField = $dialogContent.find ("input[name='title']").val (calEvent.title);
                var bodyField = $dialogContent.find ("textarea[name='body']");
                bodyField.val (calEvent.body);

                $dialogContent.dialog (
                        {
                            modal: true,
                            title: "Edit - " + calEvent.title,
                            close: function ()
                            {
                                $dialogContent.dialog ("destroy");
                                $dialogContent.hide ();
                                $ ('#calendar').weekCalendar ("removeUnsavedEvents");

                            },
                            buttons:
                                    {
                                        save: function ()
                                        {
                                            calEvent.start = new Date (startField.val ());
                                            calEvent.end = new Date (endField.val ());
                                            calEvent.title = titleField.val ();
                                            calEvent.body = bodyField.val ();

                                            $calendar.weekCalendar ("updateEvent", calEvent);
                                            $dialogContent.dialog ("close");
                                        },
                                        "delete": function ()
                                        {
                                            $calendar.weekCalendar ("removeEvent", calEvent.id);
                                            $dialogContent.dialog ("close");
                                        },
                                        cancel: function ()
                                        {
                                            $dialogContent.dialog ("close");
                                        }
                                    }
                        }).show ();
                var startField = $dialogContent.find ("select[name='start']").val (calEvent.start);
                var endField = $dialogContent.find ("select[name='end']").val (calEvent.end);
                $dialogContent.find (".date_holder").text ($calendar.weekCalendar ("formatDate", calEvent.start));
                setupStartAndEndTimeFields (startField, endField, calEvent, $calendar.weekCalendar ("getTimeslotTimes", calEvent.start));
                $ (window).resize ().resize (); //fixes a bug in modal overlay size ??

                alert ("OK");
            },
            height: function ($calendar)
            {
                return $ (window).height () - $ ("h1").outerHeight () - 1;
            },
        });
    });

    function resetForm ($dialogContent)
    {
        $dialogContent.find ("input").val ("");
        $dialogContent.find ("textarea").val ("");
    }

    /*
     * Sets up the start and end time fields in the calendar event
     * form for editing based on the calendar event being edited
     */
    function setupStartAndEndTimeFields ($startTimeField, $endTimeField, calEvent, timeslotTimes)
    {
        for (var i = 0; i < timeslotTimes.length; i++)
        {
            var startTime = timeslotTimes[i].start;
            var endTime = timeslotTimes[i].end;
            var startSelected = "";
            if (startTime.getTime () === calEvent.start.getTime ())
            {
                startSelected = "selected=\"selected\"";
            }
            var endSelected = "";
            if (endTime.getTime () === calEvent.end.getTime ())
            {
                endSelected = "selected=\"selected\"";
            }
            $startTimeField.append ("<option value=\"" + startTime + "\" " + startSelected + ">" + timeslotTimes[i].startFormatted + "</option>");
            $endTimeField.append ("<option value=\"" + endTime + "\" " + endSelected + ">" + timeslotTimes[i].endFormatted + "</option>");
        }
        $endTimeOptions = $endTimeField.find ("option");
        $startTimeField.trigger ("change");
    }
</script>

<script src="/FormBuilder/public/js/scheduler.js" type="text/javascript"></script>
