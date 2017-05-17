

<div class="fc-view-container" style="">
    <div class="fc-view fc-agendaDay-view fc-agenda-view">
        <table>
            <thead>
                <tr>
                    <td class="fc-widget-header">
                        <div class="fc-row fc-widget-header" style="border-right-width: 1px; margin-right: 16px;">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="fc-axis fc-widget-header" style="width: 41px;"></th>
                                        <th class="fc-day-header fc-widget-header fc-sat"><?= date("l") ?></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </td>
                </tr>
            </thead>
            
            <tbody>
                <tr>
                    <td class="fc-widget-content">
                        <div class="fc-day-grid">
                            <div class="fc-row fc-week fc-widget-content" style="border-right-width: 1px; margin-right: 16px;">
                                <div class="fc-bg">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="fc-axis fc-widget-content" style="width: 41px;">
                                                    <span>all-day</span>
                                                </td>
                                                
                                                <td class="fc-day fc-widget-content fc-sat fc-today fc-state-highlight" data-date="2016-08-20"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="fc-content-skeleton">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="fc-axis" style="width:41px"></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="fc-widget-header">
                        <div class="fc-time-grid-container fc-scroller" style="height: 500px;">
                            <div class="fc-time-grid"><div class="fc-bg">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="fc-axis fc-widget-content" style="width: 41px;"></td>
                                                <td class="fc-day fc-widget-content fc-sat fc-today fc-state-highlight" data-date="2016-08-20"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="fc-slats">
                                    <table>
                                        <tbody>
                                            <?php
                                            $iTimestamp = mktime(1,0,0,1,1,2011);
                                            for ($i = 1; $i < 24; $i++): 
                                                $iTimestamp += 3600;
                                            ?>
                                            <tr class="mainLine" selected="<?= date('H:i', $iTimestamp) ?>">
                                                <td class="fc-axis fc-time fc-widget-content" style="width: 41px;">
                                                    <span><?= date('H:i', $iTimestamp) ?></span>
                                                </td>
                                                <td class="fc-widget-content"></td>
                                            </tr>
                                            <tr class="fc-minor mainLine" selected="<?= date("H:i", strtotime("+30 minutes", $iTimestamp)) ?>">
                                                <td class="fc-axis fc-time fc-widget-content" style="width: 41px;"></td>
                                                <td class="fc-widget-content"></td>
                                            </tr>
                                            <?php endfor; ?>
                                           </tbody>
                                    </table>
                                </div>
                                
                                <hr class="fc-widget-header" style="display: none;">
                                <div class="fc-content-skeleton">
                                    <table>
                                        <tbody>
                                            <?php $arrTimes = array("02:00" => array("title" => "Meeting", "end_time" => "04:00"), "12:00" => array("title" => "Lunch", "end_time" => "14:00")); ?>
                                            <tr class="dayEvent">
                                                <td class="fc-axis" style="width:41px"></td>
                                                <td><div class="fc-event-container">
                                                        <?php foreach ($arrTimes as $time => $arrTime):
                                                            $times = explode (":", $time);
                                                            $top = $times[0] * 40.2;
                                                            if($times[1] == "30") { 
                                                                $top += 16;
                                                            }
                                                            
                                                            $endTime = $arrTime['end_time'];
                                                            $end = explode(":", $endTime)[0];
                                                            
                                                            $bottom = $end * 40.2;
                                                            
                                                            if($endTime[1] == "30") { 
                                                                $bottom += 16;
                                                            }
                                                            ?>
                                                        
                                                        <a prevtime="<?= $endTime; ?>" timeSlot="<?= $time; ?>" class="fc-time-grid-event fc-event fc-start fc-end fc-draggable fc-resizable" style="top: <?= $top ?>px; bottom: -<?= $bottom ?>px; z-index: 1; left: 0%; right: 0%; margin-right: 20px;">
                                                            <div class="fc-content">
                                                                <div class="fc-time" data-start="<?= $time ?>" data-full="<?= $time ?> AM">
                                                                    <span><?= $time ?></span>
                                                                </div>
                                                                
                                                                <div class="fc-title"><?= $arrTime['title'] ?></div>
                                                                    
                                                            </div>
                                                            
                                                            <div class="fc-bg"></div>
                                                            <div class="fc-resizer"></div>
                                                        </a>
                                                        
                                                        
                                                        <?php
                                                        endforeach;
                                                        ?>
                                                        
                                                       <!-- <a class="fc-time-grid-event fc-event fc-start fc-end fc-draggable fc-resizable" style="top: 396px; bottom: -521px; z-index: 2; left: 50%; right: 0%;">
                                                            <div class="fc-content">
                                                                <div class="fc-time" data-start="12:00" data-full="12:00 PM - 2:00 PM">
                                                                    <span>12:00 - 2:00</span>
                                                                </div>
                                                                <div class="fc-title">Lunch</div>
                                                                    
                                                            </div>
                                                            <div class="fc-bg">
                                                                
                                                            </div>
                                                            <div class="fc-resizer"></div>
                                                        </a>-->
                                                            
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                        
                                </div>
                                    
                            </div>
                                
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
            
    </div>
        
</div>

<script>
    $(document).ready(function () {
        $(".fc-event").each(function () {
                        var count = $(".mainLine[selected='"+$(this).attr("timeslot")+"']").prevAll("tr").length / 2;
                         var count2 = $(".mainLine[selected='"+$(this).attr("prevtime")+"']").prevAll("tr").length / 2;
                        
                        var bottom = count2 * 40;
                        var top = count * 40;

                        $(this).css("top", top+'px');
                        $(this).css("bottom", '-'+bottom+'px');
                    });
    });

</script>