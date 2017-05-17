<?php

class CalendarFuncs
{

    /*     * ******************* PROPERTY ******************* */

    private $next;
    private $title;
    private $previous;
    private $currentYear = 0;
    private $currentMonth = 0;
    private $currentDay = 0;
    private $currentDate = null;
    private $dayLabels = array(
        "fc-sun" => "Sun",
        "fc-mon" => "Mon",
        "fc-tue" => "Tue",
        "fc-wed" => "Wed",
        "fc-thu" => "Thu",
        "fc-fri" => "Fri",
        "fc-sat" => "Sat"
    );
    public $colours = array("1" => "red",
        "2" => "green",
        "3" => "#3b91ad",
        "finished" => "orange");
    public $priorities = array(1 => "High",
        2 => "Medium",
        3 => "Low"
    );
   

    /**
     * Constructor
     */
    public function __construct ()
    {
        $this->naviHref = '/FormBuilder/calendar/loadCalendar';
    }

    public function setEvents ($events)
    {
        $this->events = $events;
    }

    function prev_month ()
    {
        return mktime (0, 0, 0, (CURRENT_MONTH_N == 1 ? 12 : CURRENT_MONTH_N - 1), (checkdate ((CURRENT_MONTH_N == 1 ? 12 : CURRENT_MONTH_N - 1), CURRENT_DAY, (CURRENT_MONTH_N == 1 ? CURRENT_YEAR - 1 : CURRENT_YEAR)) ? CURRENT_DAY : 1), (CURRENT_MONTH_N == 1 ? CURRENT_YEAR - 1 : CURRENT_YEAR));
    }

    function next_month ()
    {
        return mktime (0, 0, 0, (CURRENT_MONTH_N == 12 ? 1 : CURRENT_MONTH_N + 1), (checkdate ((CURRENT_MONTH_N == 12 ? 1 : CURRENT_MONTH_N + 1), CURRENT_DAY, (CURRENT_MONTH_N == 12 ? CURRENT_YEAR + 1 : CURRENT_YEAR)) ? CURRENT_DAY : 1), (CURRENT_MONTH_N == 12 ? CURRENT_YEAR + 1 : CURRENT_YEAR));
    }

    function getEvent ($timestamp)
    {
        $event = NULL;
        if ( array_key_exists ($timestamp, $this->events) )
            $event = $this->events[$timestamp];
        return $event;
    }

    function addEvent ($event, $date)
    {
        if ( array_key_exists ($date, $this->events) )
            array_push ($this->events[$date], $event);
        else
            $this->events[$date] = array($event);
    }

    private function week_dates ($date = null, $format = null, $start = 'sunday')
    {
        // is date given? if not, use current time...
        if ( is_null ($date) )
            $date = 'now';

        // get the timestamp of the day that started $date's week...
        $weekstart = strtotime ('last ' . $start, strtotime ($date));

        // add 86400 to the timestamp for each day that follows it...
        for ($i = 0; $i < 7; $i++) {
            $day = $weekstart + (86400 * $i);
            if ( is_null ($format) )
                $dates[$i] = $day;
            else
                $dates[$i] = date ($format, $day);
        }

        return $dates;
    }

    /**
     * create navigation
     */
    public function _createNavi ($type, $year, $month)
    {
        $this->type = $type;
        $this->currentMonth = $month;
        $this->currentYear = $year;

        $nextMonth = $this->currentMonth == 12 ? 1 : intval ($this->currentMonth) + 1;

        $nextYear = $this->currentMonth == 12 ? intval ($this->currentYear) + 1 : $this->currentYear;

        $preMonth = $this->currentMonth == 1 ? 12 : intval ($this->currentMonth) - 1;

        $preYear = $this->currentMonth == 1 ? intval ($this->currentYear) - 1 : $this->currentYear;


        $HTML = '<div class="fc-left">';

        switch ($this->type) {
            case "month":
                $this->previous = $this->naviHref . '?month=' . sprintf ('%02d', $preMonth) . '&year=' . $preYear . '&type=' . $this->type;
                $this->next = $this->naviHref . '?month=' . sprintf ("%02d", $nextMonth) . '&year=' . $nextYear . '&type=' . $this->type;
                $this->title = date ('Y M', strtotime ($this->currentYear . '-' . $this->currentMonth . '-1'));

                $dayActive = '';
                $monthActive = 'fc-state-active';
                $weekActive = '';

                break;

            case "week":
                if ( isset ($_REQUEST['date']) )
                {
                    $this->currentDate = date ("Y-m-d", strtotime ($_REQUEST['date']));
                }
                else
                {
                    $this->currentDate = date ("Y-m-d");
                }

                $year = (isset ($this->currentYear)) ? $this->currentYear : date ("Y");
                $this->currentYear = $year;
                $week = (isset ($_GET['week'])) ? $_GET['week'] : date ('W');
                $this->currentWeek = $week;

                if ( $week > 52 )
                {
                    $year++;
                    $week = 1;
                }
                elseif ( $week < 1 )
                {
                    $year--;
                    $week = 52;
                }

                if ( $week < 10 )
                {
                    $week = '0' . $week;
                }


                $from = date ("Y-m-d", strtotime ("{$year}-W{$week}0")); //Returns the date of monday in week
                $to = date ("Y-m-d", strtotime ("{$year}-W{$week}6"));   //Returns the date of sunday in week   

                $dayActive = '';
                $monthActive = '';
                $weekActive = 'fc-state-active';

                $this->next = $this->naviHref . '?week=' . ($week == 52 ? 1 : 1 + $week) . '&year=' . ($week == 52 ? 1 + $year : $year) . '&type=' . $this->type;
                $this->previous = $this->naviHref . '?week=' . ($week == 1 ? 52 : $week - 1) . '&year=' . ($week == 1 ? $year - 1 : $year) . '&type=' . $this->type;

                $this->title = date ("F", strtotime ($this->currentDate)) . ' ' . date ("d", strtotime ($from)) . " - " . date ("d", strtotime ($to)) . " " . $this->currentYear;

                break;

            case "day":
                if ( isset ($_REQUEST['date']) )
                {
                    $this->currentDate = date ("Y-m-d", strtotime ($_REQUEST['date']));
                }
                else
                {
                    $this->currentDate = date ("Y-m-d");
                }

                $dayActive = 'fc-state-active';
                $monthActive = '';
                $weekActive = '';

                $nextDay = date ('Y-m-d', strtotime ('+1 day', strtotime ($this->currentDate)));
                $previousDay = date ('Y-m-d', strtotime ('-1 day', strtotime ($this->currentDate)));

                $this->previous = $this->naviHref . '?date=' . $previousDay . '&type=' . $this->type;
                $this->next = $this->naviHref . '?date=' . $nextDay . '&type=' . $this->type;
                $this->title = date ("F d, Y", strtotime ($this->currentDate));
                break;
        }

        return array(
            "next" => $this->next,
            "prev" => $this->previous,
            "title" => $this->title,
            "day" => $dayActive,
            "month" => $monthActive,
            "week" => $weekActive);
    }

    /**
     * create calendar week labels
     */
    public function _createLabels ($type)
    {
        $this->type = $type;
        $content = '';

        switch ($this->type) {
            case "month":
                foreach ($this->dayLabels as $index => $label) {
                    $content.='<th class="fc-day-header fc-widget-header ' . $index . ' ' . ($label == 6 ? 'end title' : 'start title') . ' title">' . $label . '</th>';
                }
                break;

            case "day":
                $content .= '<th class="fc-axis fc-widget-header" style="width: 41px;"></th>';
                $content .= '<th class="fc-day-header fc-widget-header fc-' . strtolower (date ("D", strtotime ($this->currentDate))) . '">' . date ("l", strtotime ($this->currentDate)) . '</th>';

                break;

            case "week":
                $from = date ("Y-m-d", strtotime ("{$this->currentYear}-W{$this->currentWeek}0")); //Returns the date of monday in week
                $to = date ("Y-m-d", strtotime ("{$this->currentYear}-W{$this->currentWeek}6"));   //Returns the date of sunday in week   

                $startTime = strtotime ($from);
                $endTime = strtotime ($to);

                $content .= '<th class="fc-axis fc-widget-header" style="width:41px"></th>';

                // Loop between timestamps, 24 hours at a time
                for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
                    $content .= '<th thdate="'.date ('d-m-Y', $i).'" class="fc-day-header fc-widget-header fc-' . strtolower (date ('D', $i)) . '">' . date ('D m/d', $i) . '</th>';
                    //echo $thisDate = date( 'D', $i ); // 2010-05-01, 2010-05-02, etc
                }
                break;
        }



        return $content;
    }

    /**
     * calculate number of weeks in a particular month
     */
    private function _weeksInMonth ($month = null, $year = null)
    {

        if ( null == ($year) )
        {
            $year = date ("Y", time ());
        }

        if ( null == ($month) )
        {
            $month = date ("m", time ());
        }

        // find number of days in this month
        $daysInMonths = $this->_daysInMonth ($month, $year);

        $numOfweeks = ($daysInMonths % 7 == 0 ? 0 : 1) + intval ($daysInMonths / 7);

        $monthEndingDay = date ('N', strtotime ($year . '-' . $month . '-' . $daysInMonths));

        $monthStartDay = date ('N', strtotime ($year . '-' . $month . '-01'));

        if ( $monthEndingDay < $monthStartDay )
        {

            $numOfweeks++;
        }

        return $numOfweeks;
    }

    /*     * ******************* PRIVATE ********************* */

    public function buildWeek ()
    {
        $calendar = '';

        $calendar .= '<div class="fc-content-skeleton">'
                . '<table>'
                . '<tbody>'
                . '<tr>'
                . '<td class="fc-axis" style="width:41px"></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '<td></td>'
                . '</tr>'
                . '</tbody>'
                . '</table>'
                . '</div>'
                . '</div>'
                . '</div>'
                . '<hr class="fc-widget-header">'
                . '';


        $from = date ("Y-m-d", strtotime ("{$this->currentYear}-W{$this->currentWeek}0")); //Returns the date of monday in week
        $to = date ("Y-m-d", strtotime ("{$this->currentYear}-W{$this->currentWeek}6"));   //Returns the date of sunday in week   

        $startTime = strtotime ($from);
        $endTime = strtotime ($to);


        // Loop between timestamps, 24 hours at a time
        $calendar .= $this->buildBg ($startTime, $endTime);

        $calendar .= '<div class="fc-slats">
                <table>
                    <tbody>';

        $iTimestamp = mktime (1, 0, 0, 1, 1, 2011);

        for ($i = 1; $i < 24; $i++):
            $iTimestamp += 3600;

            $calendar .= $this->buildAxis ($iTimestamp);
        endfor;
        $calendar .= '</tbody>';



        $calendar .= '</table>'
                . '</div>'
                . '<hr class="fc-widget-header" style="display: none;">'
                . '<div class="fc-content-skeleton">'
                . '<table>'
                . '<tbody>';

        /* Events */
        $calendar .= '<tr>'
                . '<td class="fc-axis" style="width:41px"></td>';

        $from = date ("Y-m-d", strtotime ("{$this->currentYear}-W{$this->currentWeek}0")); //Returns the date of monday in week
        $to = date ("Y-m-d", strtotime ("{$this->currentYear}-W{$this->currentWeek}6"));   //Returns the date of sunday in week   

        $startTime = strtotime ($from);
        $endTime = strtotime ($to);

        // Loop between timestamps, 24 hours at a time
        for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {
            $date = date ('Y-m-d', $i);

            $calendar .= '<td time="' . $startTime . '">';

            $calendar .= '<div date="' . $date . '" class="fc-event-container">';

            if ( isset ($this->events[$date]) )
            {
                $calendar .= $this->buildEvents ($date);
            }
            else
            {
                
            }
            $calendar .= '</div>';
            $calendar .= '</td>';
        }

        $calendar .= '</tr>'
                . '</tbody>'
                . '</table>'
                . '</div>'
                . '</div>'
                . '</div>';

        return $calendar;
    }

    public function buildClass ($date)
    {
        $curdate = date ("Y-m-d");

        if ( strtotime ($date) < time () )
        {
            $class = "fc-past";
        }

        if ( strtotime ($date) > strtotime ($curdate) )
        {
            $class = "fc-future";
        }

        if ( date ('Y-m-d') == date ('Y-m-d', strtotime ($date)) )
        {
            $class = "fc-today fc-state-highlight";
        }

        return $class;
    }

    function buildMonth ()
    {

        $running_day = date ('w', mktime (0, 0, 0, $this->currentMonth, 1, $this->currentYear));
        $days_in_month = date ('t', mktime (0, 0, 0, $this->currentMonth, 1, $this->currentYear));
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();
        $mike = '';
        $mike3 = '';
        $calendar = '';
        $daysInPreviousMonth = date ('d', strtotime ('last day of previous month'));
        $arrPreviousDays = array();

        /* print "blank" days until the first of the current week */
        for ($x = 0; $x < $running_day; $x++):
            $arrPreviousDays[] = $daysInPreviousMonth;
            $daysInPreviousMonth--;
        endfor;

        $arrPreviousDays = array_reverse ($arrPreviousDays);

        /* print "blank" days until the first of the current week */
        for ($x = 0; $x < $running_day; $x++):
            $date = date ('Y-m-d', strtotime ($this->currentYear . '-' . $this->currentMonth . '-' . ($x + 1)));
            $calendar.= '<td class="fc-day fc-sun fc-other-month fc-past" data-date="' . $date . '">&nbsp;</td>';
            $mike.= '<td class="fc-day-number fc-sun fc-other-month fc-past" data-date="' . $date . '">' . $arrPreviousDays[$x] . '</td>';
            $mike3.= '<td data-date="' . $date . '">&nbsp;</td>';
            $days_in_this_week++;
        endfor;

        /* keep going with days.... */
        for ($list_day = 1; $list_day <= $days_in_month; $list_day++):
            $date = date ('Y-m-d', strtotime ($this->currentYear . '-' . $this->currentMonth . '-' . ($list_day)));

            $class = $this->buildClass ($date);

            $class2 = 'fc-' . strtolower (date ("D", strtotime ($date)));

            $classes = array($class2, $class, 'fc-day-number');

            $mike .= '<td class="' . join (' ', $classes) . '" data-date="' . $date . '">' . $list_day . '</td>';


            /* Event Container */

            $time = strtotime ($date) + 86400;
            $tomorrow = date ('Y-m-d', $time);

            if ( isset ($this->events[$date]) )
            {

                $mike3 .= '<td date="' . $date . '" class="fc-event-container">';

                $mike3 .= $this->buildEvents ($date);


                $mike3 .= '</td>';
            }
            else
            {
                $mike3 .= '<td colspan="1">&nbsp;</td>';
            }

            $classes = array($class2, $class, 'fc-day', 'fc-widget-content');

            $calendar.= '<td class="' . join (' ', $classes) . '" data-date="' . $date . '"></td>';




            if ( $running_day == 6 ):
                $calendar.= '</tr>'
                        . '</tbody>'
                        . '</table>'
                        . '</div>';

                /* NUMBERS */
                $calendar .= '<div class="fc-content-skeleton"><table><thead><tr>'
                        . $mike
                        . '</tr>'
                        . '</thead>'
                        . '<tbody>'
                        . '<tr>'
                        . $mike3
                        . '</tr>'
                        . '</tbody>'
                        . '</table>'
                        . '</div>';

                $calendar .= '</div>';
                if ( ($day_counter + 1) != $days_in_month ):
                    $calendar .= '<div class="fc-row fc-week fc-widget-content" style="height: 90px;">'
                            . '<div class="fc-bg">'
                            . '<table>'
                            . '<tbody>';
                    $calendar.= '<tr class="calendar-row">';
                endif;
                $running_day = -1;
                $days_in_this_week = 0;
                $mike = '';
                $mike3 = '';
            endif;
            $days_in_this_week++;
            $running_day++;
            $day_counter++;
        endfor;

        /* finish the rest of the days in the week */
        if ( $days_in_this_week < 8 ):
            for ($x = 1; $x <= (8 - $days_in_this_week); $x++):
                $calendar.= '<td style="height:90px;" class="calendar-day-np">&nbsp;</td>';
                $mike .= '<td style="" class="fc-day-number fc-wed fc-future fc-other-month">' . $x . '</td>';
                $mike3 .= '';
            endfor;
        endif;

        /* final row */
        $calendar.= '</tr>';
        $calendar .= '</tbody>';
        $calendar .= '</table>'
                . '</div>';

        $calendar .= '<div class="fc-content-skeleton"><table><thead><tr>'
                . $mike
                . '</tr>'
                . '</thead>'
                . '<tbody>'
                . '<tr>'
                . $mike3
                . '</tr>'
                . '</tbody'
                . '</table>'
                . '</div>';

        $calendar .= '</div>';


        /** DEBUG * */
        $calendar = str_replace ('</td>', '</td>' . "\n", $calendar);
        $calendar = str_replace ('</tr>', '</tr>' . "\n", $calendar);

        return $calendar;

        //die;
    }

    public function buildEvents ($date)
    {
        $mike3 = '';
        $color = '#1ab394';

        foreach ($this->events[$date] as $key => $teams):

            foreach ($teams as $event):

                $otherAttrs = array();

                if ( $this->type == "week" || $this->type == "day" )
                {
                    $time = $event['start_time'];
                    $times = explode (":", $time);
                    $top = $times[0] * 40.2;

                    if ( $times[1] == "30" )
                    {
                        $top += 16;
                    }

                    $finishTime = $event['end_time'];
                    $end = explode (":", $finishTime)[0];

                    $bottom = $end * 40.2;

                    if ( $finishTime[1] == "30" )
                    {
                        $bottom += 16;
                    }

                    $otherAttrs['data-start'] = $time;
                    $otherAttrs['data-full'] = $time . ' PM - ' . $finishTime . ' PM';
                }
                else
                {
                    $otherAttrs['data-start'] = $date;
                }

                $otherAttrs['teamid'] = $key;
                //$otherAttrs['accepted_by'] = $event['accepted_by'];

                if ( strtotime (date ("Y-m-d")) > strtotime ($date) )
                {
                    $color = "red";
                }
                else
                {
                    if ( isset ($event['class']) )
                    {
                        $color = $this->colours[$event['class']];
                    }
                }

                $classes = array('fc-day-grid-event', 'fc-event', 'fc-start', 'fc-end', 'fc-draggable');
                $resize = true;

                $output = implode (' ', array_map (
                                function ($v, $k) {
                            return sprintf ("%s='%s'", $k, $v);
                        }, $otherAttrs, array_keys ($otherAttrs)
                ));


                if ( $this->type == "week" || $this->type == "day" )
                {



                    $timeSlot = '<div class="fc-time" ' . $output . '>'
                            . '<span>' . $time . ' - ' . $finishTime . '</span>'
                            . '</div>';

                    $otherAttrs2 = array('prevtime' => $finishTime, 'timeSlot' => $time);

                    $output2 = implode (' ', array_map (
                                    function ($v, $k) {
                                return sprintf ("%s='%s'", $k, $v);
                            }, $otherAttrs2, array_keys ($otherAttrs2)
                    ));

                    $classes[] = 'fc-time-grid-event';
                    $classes[] = "fc-resizable";

                    $resize = true;
                }



                $mike3 .= '<a ' . $output . ' style="background-color:' . $color . ';"';

                if ( isset ($output2) && !empty ($output2) )
                {
                    $mike3 .= $output2;
                }

                $mike3 .= 'data-title=" ' . $key . ' ' . $event['assigned_for'] . ' ' . $event['title'] . ' ' . $date . '" id="' . $event['id'] . '" days="' . $event['days'] . '" class="' . join (' ', $classes) . '">';

                $mike3 .= '<div class="fc-content">';

                if ( $this->type == "week" || $this->type == "day" )
                {
                    $mike3 .= $timeSlot;
                }
                else
                {
                    $mike3 .= '<span class="fc-time"></span> '
                            . '<span class="fc-title">'
                            . '<div class="teamLabel" style="float:left; width:100%;">' . $key . ' </div> '
                            . '<div style="float:left; width:100%;">' . $event['title'] . '<span style="margin-left:2%;"> ' . $this->priorities[$event['class']] . '</span></div>'
                            . '</span>'
                            . '</div>';
                }

                if ( isset ($event['milestone']) )
                {
                    $mike3 .= '<div milestone="' . $event['id'] . '" class="milestone" style="position: absolute; left: 276px; opacity: 1; top: -270px; ">
                  <!--<i style="color: rgb(133, 40, 40); font-size: 31px;" class="fa fa-flag"></i>-->

                  <div>
                  <div class="ReleaseFlag" style=" background: rgb(105, 0, 0) none repeat scroll 0% 0%; color: white; text-align: left; margin: 0px; padding: 5px 5px 5px 9px; font-size: 11px; width: 93px; height: 34px; line-height: 12px;">
                  Important Release
                  </div>

                  <div style="border-left: 2px solid #e69191; height: 225px;">
                  &nbsp;
                  </div>
                  </div>
                  </div>';
                }

                if ( $resize === true )
                {
                    $mike3 .= '<div class="fc-resizer"></div>';
                }

                $mike3 .= '</div>';



                $mike3 .= '</a>';


            endforeach;
        endforeach;


        return $mike3;
    }

    function buildAxis ($iTimestamp)
    {
        return '<tr class="mainLine" selected="' . date ('H:i', $iTimestamp) . '">
                                <td class="fc-axis fc-time fc-widget-content" style="width: 41px;">
                                    <span>' . date ('H:i', $iTimestamp) . '</span>
                                </td>

                                <td class="fc-widget-content"></td>
                            </tr>

                            <tr class="fc-minor mainLine" selected="' . date ("H:i", strtotime ("+30 minutes", $iTimestamp)) . '">
                                <td class="fc-axis fc-time fc-widget-content" style="width: 41px;"></td>
                                <td class="fc-widget-content"></td>
                            </tr>';
    }

    function buildDay ()
    {
        $calendar = '<div class="fc-content-skeleton">
            <table>
                <tbody>
                    <tr>
                        <td class="fc-axis" style="width:41px"></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>'
                . '</div>'
                . '</div>'
                . '<hr class="fc-widget-header">';

        /* Build Bg */
        $calendar .= $this->buildBg ();

        /* End Bukld Bg */

        $calendar .= '<div class="fc-slats">
                <table>
                    <tbody>';

        $iTimestamp = mktime (1, 0, 0, 1, 1, 2011);

        for ($i = 1; $i < 24; $i++):
            $iTimestamp += 3600;
            $calendar .= $this->buildAxis ($iTimestamp);

        endfor;
        $calendar .= '</tbody>';



        $calendar .= '</table>'
                . '</div>'
                . '<hr class="fc-widget-header" style="display: none;">'
                . '<div class="fc-content-skeleton">'
                . '<table>'
                . '<tbody>';

        $calendar .= '<tr class="dayEvent" timeslot="">
                    <td class="fc-axis" style="width:41px"></td>
                    <td>
                        <div date="' . $this->currentDate . '" class="fc-event-container">';
        if ( isset ($this->events[$this->currentDate]) )
        {
            $calendar .= $this->buildEvents ($this->currentDate);
        }

        $calendar .= '</div>
                                                </td>
                                            </tr>';

        $calendar .= '</tbody>'
                . '</table>'
                . '</div>'
                . '</div>'
                . '</div>';

        return $calendar;
    }

    private function buildBg ($startTime = null, $endTime = null)
    {

        $content = '<div class="fc-time-grid-container fc-scroller">
            <div class="fc-time-grid">
            <div class="fc-bg">
                <table>
                    <tbody>
                        <tr>';

        $classes = array("fc-day", "fc-widget-content");


        if ( $this->type == "week" )
        {

            $content .= '<td class="fc-axis fc-widget-content" style="width:41px"></td>';

            for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {

                $date = date ('Y-m-d', $i);

                $class = $this->buildClass ($date);

                $classes[] = $class;
                $classes[] = 'fc-' . strtolower (date ("D", $i));

                $content .= '<td class="' . join (' ', $classes) . '" data-date="' . $date . '"></td>';
            }
        }
        else
        {
            $content .= '<td class="fc-axis fc-widget-content" style="width: 41px;"></td>
                                    <td class="' . join (' ', $classes) . '" data-date="2016-08-20"></td>';
        }


        $content .= '</tr>
                    </tbody>
                </table>
            </div>';

        return $content;
    }

    /**
     * calculate number of days in a particular month
     */
    private function _daysInMonth ($month = null, $year = null)
    {

        if ( null == ($year) )
            $year = date ("Y", time ());

        if ( null == ($month) )
            $month = date ("m", time ());

        return date ('t', strtotime ($year . '-' . $month . '-01'));
    }

}
