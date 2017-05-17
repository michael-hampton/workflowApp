<?php
class Calendar
{
    private $daysInMonth = 0;
    private $naviHref = null;
    private $currentWeek = 0;
    private $calendarfuncs;
    private $currentYear = 0;
    private $currentMonth = 0;
    private $currentDate = 0;
    
     private $dayLabels = array(
        0 => "Sun",
        1 => "Mon",
        2 => "Tue",
        3 => "Wed",
        4 => "Thu",
        5 => "Fri",
        6 => "Sat"
    );
     
     private $dayIDs = array(
                            "Sun" => "fc-sun",
                            "Mon" => "fc-mon",
                            "Tue" => "fc-tue",
                            "Wed" => "fc-wed",
                            "Thu" => "fc-thu",
                            "Fri" => "fc-fri",
                            "Sat" => "fc-sat"
                            );
    
    
    
    private $defaults = array(
        "header" => array(
            "left" => "today prev,next",
            "center" => "title",
            "right" => "month week,day"
        ),
        "buttonIcons" => array(
            "prev" => "left-single-arrow",
            "next" => "right-single-arrow",
            "prevYear" => "left-double-arrow",
            "nextYear" => "right-double-arrow"
        
	),
        "buttonText" => array(
            "prev" => "prev",
            "next" => "next",
            "prevYear" => "prev year",
            "nextYear" => "next year",
            "year" => "year",
            "today" => "today",
            "month" => "month",
            "week" => "week",
            "day" => "day"
        )
    );

    /*     * ******************* PUBLIC ********************* */
    public $type;
    public $content;
   

    /**
     * Constructor
     */
    public function __construct ()
    {
        if ( empty ($date) )
            $date = time ();
        define ('NUM_OF_DAYS', date ('t', $date));
        define ('CURRENT_DAY', date ('j', $date));
        define ('CURRENT_MONTH_A', date ('F', $date));
        define ('CURRENT_MONTH_N', date ('n', $date));
        define ('CURRENT_MONTH_M', date ('m', $date));
        define ('CURRENT_YEAR', date ('Y', $date));
        define ('START_DAY', (int) date ('N', mktime (0, 0, 0, CURRENT_MONTH_N, 1, CURRENT_YEAR)) - 1);
        define ('COLUMNS', 7);
        
        $content = '';
        $this->calendarfuncs = new CalendarFuncs();
        //define ('PREV_MONTH', $this->prev_month ());
        //define ('NEXT_MONTH', $this->next_month ());
        
//        echo '<pre>';
//        print_r($this->events);
//        die;
    }
    
    public function renderHeader()
    {
        //$tm = options.theme ? 'ui' : 'fc';
        $tm = 'fc';
        
        $this->content .= "<div class='fc-toolbar'>";
        
        $this->content .= $this->renderSection('left');
	$this->content .= $this->renderSection('right');
	$this->content .= $this->renderSection('center');
	$this->content .= '<div class="fc-clear"/>';
        
        $this->content .= '</div>';

    }
    
    public function renderSection($position)
    {
        $tm = 'fc';
        
        $links = $this->calendarfuncs->_createNavi($this->type, $this->currentYear, $this->currentMonth);

         
        $sectionEl = '<div class="fc-' . $position . '">';

        $content = $sectionEl;

        $options = $this->defaults;
        $buttonStr = $options['header'][$position];
        
        if (!empty($buttonStr)) {
            $tags = explode(' ', $buttonStr);
            
            foreach($tags as $tag) {
                $groupChildren = null;
		$isOnlyButtons = true;
                $groupEl = null;
                
                
                $tags2 = explode(",", $tag);
                
                foreach ($tags2 as $buttonName) {

                    $customButtonProps;
                    $viewSpec;
                    $buttonClick = false;
                    $overrideText; // text explicitly set by calendar's constructor options. overcomes icons
                    $defaultText;
                    $themeIcon = '';
                    $normalIcon;
                    $innerHtml = null;
                    $classes;
                    $button; // the element
                    
                    if ($buttonName == 'title') {
                        $content .= '<h2>'.$links['title'].'</h2></div>'; // we always want it to take up height
			$isOnlyButtons = false;
                    }
                    else {
                        $defaultText = $options['buttonText'][$buttonName];
                        
                        if(isset($options['buttonIcons'][$buttonName])) {
                             $normalIcon = $options['buttonIcons'][$buttonName];
                        }
                        
                       
                        
                        if($buttonName == "today") {
                            $innerHtml = $defaultText;
                        } else {
                            if(isset($normalIcon)){
                                $innerHtml = "<span class='fc-icon fc-icon-" . $normalIcon  . "'></span>";
                            } else {
                                $innerHtml = $defaultText;
                            }
                        }
             
                        $classes = array('fc-' . $buttonName . '-button',
					$tm . '-button',
					$tm . '-state-default'
                                    );
                        
                        if($buttonName == "prev") {
                            $classes[] = "fc-corner-left";
                        } elseif ( $buttonName == "next") {
                            $classes[] = "fc-corner-right";
                            
                        } elseif ( $defaultText == "week" ) {
                            $classes[] = "fc-agendaWeek-button";
                            
                            if(!empty($links['week'])) {
                                $classes[] = $links['week'];
                                
                                 unset($links['week']);
                            }
                        
                        } elseif($defaultText == "day") {
                            $classes[] = "fc-agendaDay-button";
                            $classes[] = "fc-corner-right";
                            
                             if(!empty($links['day'])) {
                                $classes[] = $links['day'];
                                
                                unset($links['day']);
                            }
                            
                        } elseif($defaultText == "month") {
                            $classes[] = "fc-month-button";
                            $classes[] = "fc-corner-left";
                            
                             if(!empty($links['month'])) {
                                $classes[] = $links['month'];
                                
                                 unset($links['month']);
                            }
                            
                        } elseif ( $defaultText == "today" ) {
                            
                           if($this->type == "month" && $this->currentMonth == CURRENT_MONTH_M) {
                                $classes[] = "fc-state-disabled";
                            }
                            
                            $classes[] = "fc-today-button";
                            $classes[] = "fc-corner-left";
                            $classes[] = "fc-corner-right";
                             
                        }
                        
                        $button = '<button type="button" class="' . implode(' ', $classes) . '">';
                        if(isset($links[$buttonName]) && !empty( $links[$buttonName])) {
                            $button .= '<a href="'.$links[$buttonName].'">' .
                                $innerHtml .
                            '</a>';
                        } else {
                            $button .= $innerHtml;
                        }
                          
			$button .= '</button>';
                        
                          
                        if($defaultText == "month" || $buttonName == "prev") {
                            $content .= '<div class="fc-button-group">';
                        }
                        
                        if($buttonName != "today") {
                            $content .= $button;
                        } else {
                            $today = $button;
                        }

                          if($buttonName == "next" || $defaultText == "day") {
                                $content .= '</div>';
                              
                                if($buttonName == "next") {
                                  $content .= $today;
                              }
                              
                             
                              $content .= '</div>';
                          }

			
                    }
                    
                }
            } 
            //echo $groupChildren;
            //$content = $sectionEl . $groupChildren;
				
        }
        
       
        
       return $content;
        
        
       
    }
    
    private function renderDates()
    {
       return '' .
            '<div class="fc-row fc-widget-header ">' .
		'<table>' .
                    '<thead>' .
                    $this->calendarfuncs->_createLabels($this->type)
                    . '</thead>' .
		'</table>' .
            '</div>';
    }
    
    public function renderHeadTrHtml() {
        
        if($this->type == "month") {
            $colCnt = 7;
        }
	return '' .
            '<tr>' .
		$this->renderHeadIntroHtml() .
		$this->renderHeadDateCellsHtml($colCnt) .
            '</tr>';
    }
    
    public function renderHeadDateCellsHtml($colCnt) {
	$htmls = array();
	$col = 0; 
        $date = 0;

	for ($col = 0; $col < $colCnt; $col++) {
            $date = $this->getCellDate(0, $col);
            $htmls[] = $this->renderHeadDateCellHtml($date, 0, '');
	}
        
        return join('', $htmls);

    }
    
    public function renderHeadDateCellHtml ($date, $colspan, $otherAttrs) {

        $this->rowCnt = 0;
        
	return '' .
            '<th class="fc-day-header fc-widget-header fc-' . strtolower ($date) . '"' .
				($this->rowCnt == 1 ?
					' data-date="' . date.format('YYYY-MM-DD') . '"' :
					'') .
				($colspan > 1 ?
					' colspan="' . $colspan . '"' :
					'') .
				($otherAttrs ?
					' ' . $otherAttrs :
					'') .
			'>' .
				$date .
			'</th>';
    }
    
    public function renderHeadIntroHtml() {
	return $this->renderIntroHtml(); // fall back to generic
    }


// Generates the default HTML intro for any row. User classes should override
public function renderIntroHtml() {
}


    public function renderNumberIntroHtml($row)
    {
        return $this->renderIntroHtml();
    }
    
    public function getCellDate ($row, $col) {
        if($row == 0) {
            if($this->type == "month") {
                return $this->dayLabels[$col];
            }
        }
        
        
	//return this.dayDates[this.getCellDayIndex(row, col)].clone();
    }
    
    public function renderNumberCellsHtml ($row, $colCnt) {
	$htmls = array();
	$col = 0;
        $date = 0;

	for ($col = 0; $col < $colCnt; $col++) {
            $date = $this->getCellDate($row, $col);
            $htmls[] = $this->renderNumberCellHtml($date);
        }

	return join('', $htmls);
    }

    // Generates the HTML for the <td>s of the "number" row in the DayGrid's content skeleton.
    // The number row will only exist if either day numbers or week numbers are turned on.
    public function renderNumberCellHtml($date, $dayNumbersVisible) {
	$classes = null;

        if ($dayNumbersVisible === false) { // if there are week numbers but not day numbers
            return '<td/>'; //  will create an empty space above events :(
	}

        $classes = this.getDayClasses($date);
	classes.unshift('fc-day-number');

	return '' .
	'<td class="' . join(' ', $classes) . '" data-date="' . date.format() . '">' .
            date.date() .
	'</td>';
    }
    


    public function renderNumberTrHtml($row) {
        return '' .
            '<tr>' 
                . $this->renderNumberIntroHtml($row)
		. this.renderNumberCellsHtml(row) +
            '</tr>';
    }
    
    //DayTableMixin.js
    
    public function renderBgCellsHtml($row, $colCnt) {
	$htmls = array();
	$col = 0; 
        $date = 0;

	for ($col = 0; $col < $colCnt; $col++) {
            //$date = this.getCellDate(row, col);
            $htmls[] = this.renderBgCellHtml($date);
        }

        return join('', $htmls);
    }
    
    public function getNow() {
	/*$now = t.options.now;
	if (typeof now === 'function') {
            now = now();
	}
	return t.moment(now).stripZone();*/
    }
    
    
    // Computes HTML classNames for a single-day element
    public function getDayClasses($date) {
        /*$today = view.calendar.getNow();
        // day shorthand
	$classes = array('fc-'.dayIDs[date.day());

	if (view.intervalDuration.as('months') == 1 && date.month() != view.intervalStart.month()) {
            $classes[] = 'fc-other-month';
	}

	if (date.isSame(today, 'day')) {
            $classes[] = 'fc-today';
            $classes[] = view.highlightStateClass;
	}
	else if (date < today) {
            $classes[] = 'fc-past';
	}
	else {
            $classes[] = 'fc-future';
	}

	return classes; */
    }
    
    
    public function renderBgCellHtml ($date, $otherAttrs) {
        $classes = $this->getDayClasses(date);

		/*classes.unshift('fc-day', view.widgetContentClass);

		$content = '<td class="' . join(' ', $classes) . '"' .
                    ' data-date="' + date.format('YYYY-MM-DD') . '"' . // if date has a time, won't format it
			if(!empty($otherAttrs)) {
                            $content .= ' ' . otherAttrs
                        }
                  .
			'></td>';*/
	}
    
    public function renderBgIntroHtml ($row) {
	return this.renderIntroHtml(); // fall back to generic
    }
    
    public function renderBgTrHtml($row) {
        return '' .
            '<tr>' .
                $this->renderBgIntroHtml($row) .
               $this->renderBgCellsHtml($row) .
            '</tr>';
    }

    // Generates the HTML for a single row, which is a div that wraps a table.
    // `row` is the row number.
    public function renderDayRowHtml($numbersVisible) 
    {
        $isRigid = false;
        
        $classes = array("fc-row", "fc-week", "fc-widget-content");

	if ($isRigid === TRUE) {
            $classes[] = 'fc-rigid';
	}
        
        $days_in_month = date('t',mktime(0,0,0,  $this->currentMonth,1,  $this->currentYear));
        die($days_in_month);
        
        for($list_day = 1; $list_day <= $days_in_month; $list_day++):
        
            $content = '' .
                '<div class="' . implode (" ", $classes)  . '">' .
                    '<div class="fc-bg">' .
                        '<table>' .
                            //$this->renderBgTrHtml($row) .
                        '</table>' .
                    '</div>'; /*.

                    '<div class="fc-content-skeleton">' .
                        '<table>';
                            if($numbersVisible === true) {
                                $content .= '<thead>' .
                                    $this->renderNumberTrHtml .
                                '</thead>' 
                        . '</table>' .
                    '</div>' .
                '</div>';*/
        endfor;

        return $content;
        
    }
    
    public function buildView()
    {
        $calendar = '<div class="fc-row fc-week fc-widget-content" style="height: 90px;">'
            . '<div class="fc-bg">'
                . '<table>'
                    . '<tbody>';
                        $calendar.= '<tr class="calendar-row">';

        if ( $this->type == "day" )
        {
            $calendar .= '<td class="fc-axis fc-widget-content" style="width: 41px;">'
                    . '<span>all-day</span>'
                    . '</td>'
                    . '<td class="fc-day fc-widget-content fc-' . strtolower (date ("D", strtotime ($this->currentDate))) . ' fc-today fc-state-highlight" data-date="' . date ("Y-m-d", strtotime ($this->currentDate)) . '">'
                    . '</td>'
                    . '</tr>'
                    . '</tbody>'
                    . '</table>'
                    . '</div>';
        }

        if ( $this->type == "week" )
        {

            $from = date ("Y-m-d", strtotime ("{$this->currentYear}-W{$this->currentWeek}0")); //Returns the date of monday in week
            $to = date ("Y-m-d", strtotime ("{$this->currentYear}-W{$this->currentWeek}6"));   //Returns the date of sunday in week   

            $startTime = strtotime ($from);
            $endTime = strtotime ($to);

            $calendar .= '<td class="fc-axis fc-widget-content" style="width:41px"><span>all-day</span></td>';

            // Loop between timestamps, 24 hours at a time
            for ($i = $startTime; $i <= $endTime; $i = $i + 86400) {

                $date = date ('Y-m-d', $i);
                $curdate = date ("Y-m-d");

                $class = $this->calendarfuncs->buildClass($date);

                $calendar .= '<td class="fc-day fc-widget-content fc-' . strtolower (date ("D", $i)) . ' ' . $class . '" data-date="' . $date . '"></td>';
            }

            $calendar .= '</tr>'
                    . '</tbody>'
                    . '</table>'
                    . '</div>';
        }
        
         switch ($this->type) {
            case "month":
                $calendar .= $this->calendarfuncs->buildMonth();

                break;

            case "day":
                $calendar .= $this->calendarfuncs->buildDay();
                break;

            case "week":
                $calendar .= $this->calendarfuncs->buildWeek();
                break;
        }
        
        /* all done, return result */
        return $calendar;
    }

    public function show($year, $month, $viewType, $events)
    {
        $this->calendarfuncs->setEvents($events);
        $this->type = $viewType;
        
        if ( $year == NULL )
        {
            $year = date ("Y", time ());
        }

        if ( $month == NULL )
        {
            $month = date ("m", time ());
        }
        
         if ( isset ($_REQUEST['date']) )
                {
                    $this->currentDate = date ("Y-m-d", strtotime ($_REQUEST['date']));
                }
                else
                {
                    $this->currentDate = date ("Y-m-d");
                }
        
        $this->currentMonth = $month;
        $this->currentYear = $year;
        
        
        $week = (isset ($_GET['week'])) ? $_GET['week'] : date ('W');
        $this->currentWeek = $week;
        //$this->curr
        
        $this->content .= '<div id="calendar" class="fc fc-ltr fc-unthemed">';
            $this->renderHeader();
            
            $this->content .= "<div class='fc-view-container'>";
        
                $this->content .= "<div class='fc-view fc-" . $viewType . "-view fc-basic-view' >";
        
                    $this->content .= '<table>'
                        . '<thead>'
                            . '<tr>';
        
                                $this->content .= '<td class="fc-widget-header">';
                                    // Need to change here
                                    $this->content .= $this->renderDates();
                                $this->content .= '</td>';
                            $this->content .= '</tr>';
                        $this->content .= '</thead>';
                        
                        $this->content .= '<tbody>';
                            $this->content .= '<tr>';
                                $this->content .= '<td class="fc-widget-content">';
                                if($this->type == "month") {
                                    $this->content .= '<div class="fc-day-grid-container">';
                                }
                                    
                                        $this->content .= '<div class="fc-day-grid">';
                                            $this->content .= $this->buildView();
                                        $this->content .= '</div>';
                                    $this->content .= '</div>';
                                $this->content .= '</td>';
                            $this->content .= '</tr>';
                        $this->content .= '</tbody>';

                        
                        /* End Here */
                    $this->content .= '</table>';  
                $this->content .= '</div>'; 
            $this->content .= '</div>'; 
            
        $this->content .= '</div>';   
        $this->content .= '</div>';   

             
        return $this->content;
       
    }
}
