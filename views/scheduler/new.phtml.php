<script src="/FormBuilder/public/js/scheduler_1.js" type="text/javascript"></script>
<link href="/FormBuilder/public/css/scheduler.css" type="text/css" rel="stylesheet">

<div class="wrapper wrapper-content">
    <div class="row animated fadeInDown">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Striped Table </h5>

                </div>
                <div class="ibox-content">
                    <div id="message" class="ui-corner-all"></div>
                    <div id="calendar_selection" class="ui-corner-all">
                        <strong>Event Data Source: </strong>
                        <select id="data_source">
                            <option value="">Select Event Data</option>
                            <option value="1">Department 1</option>
                            <option value="2">Department 2</option>
                        </select>
                    </div>

                    <div style="width:1050px; border:1px solid #CCC; float:left; overflow-y: auto;">
                        <div style="width:3600px">
                            <div id="calendar"></div>
                        </div>
                    </div>






                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox float-e-margins">

                <div class="ibox-content">
                    <div id="external-events">
                        <p>Drag a event and drop into callendar.</p>

                        <?php for ($i = 1; $i <= 10; $i++): ?>

                            <div class="external-event navy-bg ui-draggable ui-draggable-handle col-lg-2 m-l-sm ui-corner-all ui-resizable ui-draggable" style="position: relative;">
                                <div class="wc-time ui-corner-top ui-draggable-handle" style="background-color: rgb(153, 153, 153); border: 1px solid rgb(136, 136, 136);">02:00 pm to 02:40 pm</div>
                                <div class="wc-title">Team Meeting</div>
                                <div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;">=</div>

                            </div> 
                        <?php endfor; ?>
                        <p class="m-t">
                        <div class="icheckbox_square-green checked" style="position: relative;"><input type="checkbox" id="drop-remove" class="i-checks" checked="" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div> <label for="drop-remove" class="">remove after drop</label>
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script type="text/javascript">
    (function ($)
    {
        var d = new Date ();
        d.setDate (d.getDate () - d.getDay ());
        var year = d.getFullYear ();
        var month = d.getMonth ();
        var day = d.getDate ();

        var eventData1 = {
            options: {
                timeslotsPerHour: 4,
                timeslotHeight: 20,
                defaultFreeBusy: {free: true}
            },
            events: [
                {"id": 1, "start": new Date (year, month, day + 0, 12), "end": new Date (year, month, day + 0, 13, 30), "title": "Lunch with Mike", userId: 0, priority: 2},
                {"id": 2, "start": new Date (year, month, day + 0, 14), "end": new Date (year, month, day + 0, 14, 45), "title": "Dev Meeting", userId: 1, priority: 3},
                {"id": 3, "start": new Date (year, month, day + 1, 18), "end": new Date (year, month, day + 1, 18, 45), "title": "Hair cut", userId: 2, priority: 1},
                {"id": 4, "start": new Date (year, month, day + 2, 08), "end": new Date (year, month, day + 2, 09, 30), "title": "Team breakfast", userId: 3, priority: 2},
                {"id": 5, "start": new Date (year, month, day + 1, 14), "end": new Date (year, month, day + 1, 15, 00), "title": "Product showcase", userId: 4, priority: 3}
            ],
//            freebusys: [
//                {"start": new Date (year, month, day + 0, 00), "end": new Date (year, month, day + 3, 00, 00), "free": false, userId: [0, 1, 2, 3]},
//                {"start": new Date (year, month, day + 0, 08), "end": new Date (year, month, day + 0, 12, 00), "free": true, userId: [0, 1, 2, 3]},
//                {"start": new Date (year, month, day + 1, 08), "end": new Date (year, month, day + 1, 12, 00), "free": true, userId: [0, 1, 2, 3]},
//                {"start": new Date (year, month, day + 2, 08), "end": new Date (year, month, day + 2, 12, 00), "free": true, userId: [0, 1, 2, 3]},
//                {"start": new Date (year, month, day + 1, 14), "end": new Date (year, month, day + 1, 18, 00), "free": true, userId: [0, 1, 2, 3]},
//                {"start": new Date (year, month, day + 2, 08), "end": new Date (year, month, day + 2, 12, 00), "free": true, userId: [0, 3]},
//                {"start": new Date (year, month, day + 2, 14), "end": new Date (year, month, day + 2, 18, 00), "free": true, userId: 1}
//            ]
        };

        d = new Date ();
        d.setDate (d.getDate () - (d.getDay () - 3));
        year = d.getFullYear ();
        month = d.getMonth ();
        day = d.getDate ();

        var eventData2 = {
            options: {
                timeslotsPerHour: 3,
                timeslotHeight: 30,
                defaultFreeBusy: {free: false}
            },
            events: [
                {"id": 1, "start": new Date (year, month, day + 0, 12), "end": new Date (year, month, day + 0, 13, 00), "title": "Lunch with Sarah", userId: 1, priority: 2},
                {"id": 2, "start": new Date (year, month, day + 0, 14), "end": new Date (year, month, day + 0, 14, 40), "title": "Team Meeting", userId: 0, priority: 1},
                {"id": 3, "start": new Date (year, month, day + 1, 18), "end": new Date (year, month, day + 1, 18, 40), "title": "Meet with Joe", userId: 2, priority: 3},
                {"id": 4, "start": new Date (year, month, day - 1, 08), "end": new Date (year, month, day - 1, 09, 20), "title": "Coffee with Alison", userId: 3, priority: 2},
                {"id": 5, "start": new Date (year, month, day + 1, 14), "end": new Date (year, month, day + 1, 15, 00), "title": "Product showcase", userId: 4, priority: 1}
            ],
//            freebusys: [
//                {"start": new Date (year, month, day - 1, 08), "end": new Date (year, month, day - 1, 18, 00), "free": true, userId: [0, 1, 2, 3]},
//                {"start": new Date (year, month, day + 0, 08), "end": new Date (year, month, day + 0, 18, 00), "free": true, userId: [0, 1, 2, 3]},
//                {"start": new Date (year, month, day + 1, 08), "end": new Date (year, month, day + 1, 18, 00), "free": true, userId: [0, 3]},
//                {"start": new Date (year, month, day + 2, 14), "end": new Date (year, month, day + 2, 18, 00), "free": true, userId: 1}
//            ]
        };

        function updateMessage ()
        {
            var dataSource = $ ('#data_source').val ();
            $ ('#message').fadeOut (function ()
            {
                if (dataSource === "1")
                {
                    $ ('#message').text ("Displaying event data set 1 with timeslots per hour of 4 and timeslot height of 20px. Moreover, the calendar is free by default.");
                }
                else if (dataSource === "2")
                {
                    $ ('#message').text ("Displaying event data set 2 with timeslots per hour of 3 and timeslot height of 30px. Moreover, the calendar is busy by default.");
                }
                else
                {
                    $ ('#message').text ("Displaying no events.");
                }
                $ (this).fadeIn ();
            });
        }
        
        function buildPriority (priority)
        {
            var priorities = ['High', 'Medium', 'Low'];
            var options = '';

            for (var i = 0, l = priorities.length; i < l; i++) {
                var key = parseInt (i) + 1;

                if (priority != undefined && priority == key)
                {
                    var selected = 'selected="selected"';
                }
                else
                {
                    var selected = '';
                }

                options += '<option value="' + key + '" ' + selected + '>' + priorities[i] + '</option>';
            }

            $("#priority").html(options);

        }

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

            $startTimeField.empty ();
            $endTimeField.empty ();


            for (var i = 0; i < timeslotTimes.length; i++) {
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

                $timestampsOfOptions.start[timeslotTimes[i].startFormatted] = startTime.getTime ();
                $timestampsOfOptions.end[timeslotTimes[i].endFormatted] = endTime.getTime ();

            }
            $endTimeOptions = $endTimeField.find ("option");
            $startTimeField.trigger ("change");
        }

        var $endTimeField = $ ("select[name='end']");
        var $endTimeOptions = $endTimeField.find ("option");
        var $timestampsOfOptions = {start: [], end: []};

        //reduces the end time options to be only after the start time options.
        $ ("select[name='start']").change (function ()
        {
            var startTime = $timestampsOfOptions.start[$ (this).find (":selected").text ()];
            var currentEndTime = $endTimeField.find ("option:selected").val ();
            $endTimeField.html (
                    $endTimeOptions.filter (function ()
                    {
                        return startTime < $timestampsOfOptions.end[$ (this).text ()];
                    })
                    );

            var endTimeSelected = false;
            $endTimeField.find ("option").each (function ()
            {
                if ($ (this).val () === currentEndTime)
                {
                    $ (this).attr ("selected", "selected");
                    endTimeSelected = true;
                    return false;
                }
            });

            if (!endTimeSelected)
            {
                //automatically select an end date 2 slots away.
                $endTimeField.find ("option:eq(1)").attr ("selected", "selected");
            }

        });

        $ (document).ready (function ()
        {

            var users = [
                {
                    "id": "0",
                    "username": "michael",
                    "team_id": '0',
                    "photo": "a"
                },
                {
                    "id": "1",
                    "username": "paul",
                    "team_id": '0',
                    "photo": "c"
                },
                {
                    "id": "2",
                    "username": "uan",
                    "team_id": '1',
                    "photo": "b"
                },
                {
                    "id": "3",
                    "username": "lexi",
                    "team_id": '1',
                    "photo": "d"
                }
            ];

            //    setTimeout (function ()
            //    {
            //        location.reload ();
            //    }, 30000);


            var $calendar = $ ('#calendar').weekCalendar ({
                timeslotsPerHour: 4,
                scrollToHourMillis: 0,
                height: function ($calendar)
                {
                    return $ (window).height () - $ ('h1').outerHeight (true);
                },
                eventRender: function (calEvent, $event)
                {
                    if (calEvent.end.getTime () < new Date ().getTime ())
                    {
                        $event.css ("backgroundColor", "#aaa");
                        $event.find (".wc-time").css ({backgroundColor: "#999", border: "1px solid #888"});
                    }
                },
                eventDrop: function (calEvent, $event)
                {

                },
                eventResize: function (calEvent, $event)
                {
                },
                eventClick: function (calEvent, $event)
                {

                    if (calEvent.readOnly)
                    {
                        return;
                    }

                    $ ("#myModal").modal ("show");


                    var $dialogContent = $ ("#myModal");
                    resetForm ($dialogContent);
                    var startField = $dialogContent.find ("select[name='start']").val (calEvent.start);
                    var endField = $dialogContent.find ("select[name='end']").val (calEvent.end);
                    var titleField = $dialogContent.find ("input[name='title']").val (calEvent.title);
                    var bodyField = $dialogContent.find ("textarea[name='body']");
                    bodyField.val (calEvent.body);

                    setTimeout (function ()
                    {
                        $ (".modal-title").html (calEvent.title);
                        var startField = $dialogContent.find ("select[name='start']").val (calEvent.start);
                        var endField = $dialogContent.find ("select[name='end']").val (calEvent.end);
                        setupStartAndEndTimeFields (startField, endField, calEvent, $calendar.weekCalendar ("getTimeslotTimes", calEvent.start));

                        $dialogContent.find (".date_holder").text ($calendar.weekCalendar ("formatDate", calEvent.start));
                        setupStartAndEndTimeFields (startField, endField, calEvent, $calendar.weekCalendar ("getTimeslotTimes", calEvent.start));
                        $ (window).resize ().resize (); //fixes a bug in modal overlay size ??
                        buildPriority (calEvent.priority);
                    }, 800);

                    $ ("#SaveEvent").click (function ()
                    {
                        alert ("1");
                        calEvent.start = new Date (startField.val ());
                        calEvent.end = new Date (endField.val ());
                        calEvent.title = titleField.val ();
                        calEvent.body = bodyField.val ();

                        $calendar.weekCalendar ("updateEvent", calEvent);
                        $ ("#Close").click ();
                    });

                    $ ("#DeleteEvent").click (function ()
                    {
                        $calendar.weekCalendar ("removeEvent", calEvent.id);
                        $ ("#Close").click ();
                    });

                    $ ("#Close").click (function ()
                    {
                        $ ('#calendar').weekCalendar ("removeUnsavedEvents");
                    });
                },
                eventMouseover: function (calEvent, $event)
                {
                },
                eventMouseout: function (calEvent, $event)
                {
                },
                noEvents: function ()
                {

                },
                draggable: function (calEvent, $event)
                {
                    return calEvent.readOnly != true;
                },
                resizable: function (calEvent, $event)
                {
                    return calEvent.readOnly != true;
                },
                eventNew: function (calEvent, $event, FreeBusyManager, calendar)
                {

                    var $dialogContent = $ ("#newEventsForm");
                    resetForm ($dialogContent);
                    var startField = $dialogContent.find ("select[name='start']").val (calEvent.start);
                    var endField = $dialogContent.find ("select[name='end']").val (calEvent.end);
                    var titleField = $dialogContent.find ("input[name='title']");
                    var bodyField = $dialogContent.find ("textarea[name='body']");
                    $ ("#userId").val (calEvent.userId);

                    /****************** Start Date *****************************/
                    var date = $calendar.weekCalendar ("formatDate", calEvent.start);
                    var shortMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                    var m = /(\w+) (\d+), (\d+)/.exec (date);

                    alert (date + ' ' + m);

                    var year = m[3];
                    var month = parseInt (shortMonths.indexOf (m[1])) + 1;
                    var day = m[2];

                    day = day < 10 ? '0' + day : day;
                    month = month < 10 ? '0' + month : month;

                    var date = $.trim (day + "/" + month + "/" + year);

                    $ ("#startDate").val (date);

                    /****************** Start Date *****************************/
                    var date = $calendar.weekCalendar ("formatDate", calEvent.end);
                    var m = /(\w+) (\d+), (\d+)/.exec (date);
                    var year = m[3];
                    var month = parseInt (shortMonths.indexOf (m[1])) + 1;
                    var day = m[2];

                    day = day < 10 ? '0' + day : day;
                    month = month < 10 ? '0' + month : month;

                    var date = $.trim (day + "/" + month + "/" + year);

                    $ ("#endDate").val (date);

                    $ ("#myModal").modal ("show");

                    setTimeout (function ()
                    {
                        $ (".modal-title").html ("New Calendar Event");
                        $dialogContent.find (".date_holder").text ($calendar.weekCalendar ("formatDate", calEvent.start));
                        setupStartAndEndTimeFields (startField, endField, calEvent, $calendar.weekCalendar ("getTimeslotTimes", calEvent.start));
                    }, 800);

                    $ ("#SaveEvent").click (function ()
                    {
                        var arrData = $ ("#newEventsForm").serialize ();
                        arrData += "&timestamp1=" + $ ('[name="start"]').find (":selected").text ();
                        arrData += "&timestamp2=" + $ ('[name="end"]').find (":selected").text ();

                        $.ajax ({
                            type: "POST",
                            url: "/FormBuilder/scheduler/updateEvent",
                            data: arrData,
                            success: function (response)
                            {
                                alert (response);
                                console.log (response);
                                response = $.trim (response);

                                calEvent.id = calEvent.userId + '_' + calEvent.start.getTime ();
                                // alert ("You've added a new event. You would capture this event, add the logic for creating a new event with your own fields, data and whatever backend persistence you require.");
                                $ (calendar).weekCalendar ('updateFreeBusy', {userId: calEvent.userId, start: calEvent.start, end: calEvent.end, free: false});
                                calEvent.start = new Date (startField.val ());
                                calEvent.end = new Date (endField.val ());
                                calEvent.title = titleField.val ();
                                calEvent.body = bodyField.val ();
                                calEvent.priority = $ ("#priority").val ();
                                calEvent.userId = $ ("#userId").val ();

                                $calendar.weekCalendar ("removeUnsavedEvents");
                                $calendar.weekCalendar ("updateEvent", calEvent);
                                $dialogContent.dialog ("close");

                            }
                        });
                        $ ("#Close").click ();
                    });


//                    var isFree = true;
//                    $.each (FreeBusyManager.getFreeBusys (calEvent.start, calEvent.end), function ()
//                    {
//                        if (
//                                this.getStart ().getTime () != calEvent.end.getTime ()
//                                && this.getEnd ().getTime () != calEvent.start.getTime ()
//                                && !this.getOption ('free')
//                                )
//                        {
//                            isFree = false;
//                            return false;
//                        }
//                    });
//                    if (!isFree)
//                    {
//                        alert ('looks like you tried to add an event on busy part !');
//                        $ (calendar).weekCalendar ('removeEvent', calEvent.id);
//                        return false;
//                    }
//                    calEvent.id = calEvent.userId + '_' + calEvent.start.getTime ();
//                    alert ("You've added a new event. You would capture this event, add the logic for creating a new event with your own fields, data and whatever backend persistence you require.");
//                    $ (calendar).weekCalendar ('updateFreeBusy', {userId: calEvent.userId, start: calEvent.start, end: calEvent.end, free: false});
                },
                //data: "/FormBuilder/scheduler/getEvents",
                data: function (start, end, callback)
                {
                    var dataSource = $ ('#data_source').val ();
                    if (dataSource === "1")
                    {
                        callback (eventData1);
                    }
                    else if (dataSource === "2")
                    {
                        callback (eventData2);
                    }
                    else
                    {
                        callback ({options: {defaultFreeBusy: {free: true}}, events: []});
                    }
                },
                users: users,
                showAsSeparateUser: true,
                displayOddEven: true,
                displayFreeBusys: false,
                daysToShow: 7,
                switchDisplay: {'1 day': 1, '3 next days': 3, 'work week': 5, 'full week': 7},
                headerSeparator: ' ',
                //useShortDayNames: true,
                // I18N
//                firstDayOfWeek: $.datepicker.regional['fr'].firstDay,
//                shortDays: $.datepicker.regional['fr'].dayNamesShort,
//                longDays: $.datepicker.regional['fr'].dayNames,
//                shortMonths: $.datepicker.regional['fr'].monthNamesShort,
//                longMonths: $.datepicker.regional['fr'].monthNames,
                //dateFormat: "d F y"
            });

            $ ('#data_source').change (function ()
            {
                $calendar.weekCalendar ("refresh");
                updateMessage ();
            });

            updateMessage ();
        });
    }) (jQuery);

</script>

<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <i class="fa fa-laptop modal-icon"></i>
                <h4 class="modal-title">Modal title</h4>
                <small class="font-bold"><span>Date: </span><span class="date_holder"></span> </small>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" name="newEventsForm" id="newEventsForm">
                    <input type="hidden" id="startDate" name="startDate" value="">
                    <input type="hidden" id="endDate" name="endDate" value="">
                    <input type="hidden" id="userId" name="userId" value="">
                    <input type="hidden" />

                    <div class="form-group">
                        <label class="col-lg-2 control-label">Start Time:</label>

                        <div class="col-lg-10">
                            <select class="form-control" name="start">
                                <option value="">Select Start Time</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label">End Time:</label>

                        <div class="col-lg-10">
                            <select class="form-control" name="end">
                                <option value="">Select End Time</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label">Title:</label>

                        <div class="col-lg-10">
                            <input class="form-control" type="text" name="title" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label">Body:</label>

                        <div class="col-lg-10">
                            <textarea name="body" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label">Priority:</label>

                        <div class="col-lg-10">
                            <select id="priority" name="priority" class="form-control">
                                <option value="1">High</option>
                                <option value="2">Medium</option>
                                <option value="3">Low</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="Close" type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button id="SaveEvent" type="button" class="btn btn-primary">Save changes</button>
                <button id="DeleteEvent" type="button" class="btn btn-danger">Delete Event</button>
            </div>
        </div>
    </div>
</div>

