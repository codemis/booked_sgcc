{*
Copyright 2011-2014 Nick Korbel

This file is part of Booked Scheduler.

Booked Scheduler is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Booked Scheduler is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Booked Scheduler.  If not, see <http://www.gnu.org/licenses/>.
*}

{block name="header"}
    {include file='globalheader.tpl' cssFiles='css/jquery.qtip.min.css,scripts/css/jqtree.css,css/schedule.css,css/admin.css,custom/css/clndr.css,custom/css/theme.ice.css,custom/css/sgcc-custom.css' printCssFiles='custom/css/sgcc-custom.print.css'}
{/block}

{if $IsAccessible}

    {block name="schedule_control"}
        <div>
            <div class="schedule_title">
                <span>{$ScheduleName}</span>
                {if $Schedules|@count gt 0}
                    <ul class="schedule_drop">
                        <li id="show_schedule">{html_image src="down_sm_blue.png" alt="Change Schedule"}</li>
                        <ul style="display:none;" id="schedule_list">
                            {foreach from=$Schedules item=schedule}
                                <li><a href="#"
                                       onclick="ChangeSchedule({$schedule->GetId()}); return false;">{$schedule->GetName()}</a>
                                </li>
                            {/foreach}
                        </ul>
                    </ul>
                {/if}
            </div>
        </div>
    {/block}
    <br><br>
    <div id="simplified-schedule">
        <div id="target"></div>
        <div id="simplified-calendar">
            <div id="the-schedule" class="cal1"></div>
        </div>

        {block name="reservations"}
            <div id="simplified-listing">
                <div class="admin margin-bottom hide-print">
                    <div class="title">
                        Add an Event
                    </div>
                    <div class="scheduleDetails schedule-details-simplified">
                        <form method="GET" action="/Web/reservation.php" id="add-event-form">
                            <div class="form-group">
                                <label for="autocomplete-resources">Resource to Schedule:</label>
                                <input type="text" class="form-control" id="autocomplete-resources">
                                <input type="hidden" id="selected-resource-id" name="rid">
                                <input type="hidden" name="sid" value="{$ScheduleId}">
                            </div>
                            <button type="submit" class="button save">Submit</button>
                        </form>
                    </div>
                </div>
                <div class="admin">
                    <div class="title">
                        Current Events
                    </div>
                    <div class="scheduleDetails schedule-details-simplified">
                        <table id="event-listing" class="tablesorter">
                            <thead>
                                <tr>
                                    <th>Room</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Event</th>
                                    <th data-sorter="false">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5">Please select a date on the calendar.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        {/block}
        <div class="clear">&nbsp;</div>
    </div>
{else}
    <div class="error">{translate key=NoResourcePermission}</div>
{/if}

{block name="scripts-common"}
    {jsfile src="js/jquery.qtip.min.js"}
    {jsfile src="schedule.js"}
    {jsfile src="resourcePopup.js"}
    {jsfile src="js/tree.jquery.js"}
    {jsfile src="js/jquery.cookie.js"}
    {jsfile src="../custom/js/moment-2.5.1.js"}
    {jsfile src="../custom/js/underscore.min.js"}
    {jsfile src="../custom/js/clndr.min.js"}
    {jsfile src="../custom/js/jquery.tablesorter.min.js"}
    {jsfile src="../custom/js/jquery.tablesorter.widgets.min.js"}
    {jsfile src="../custom/js/simplified-schedule.js"}

    <script type="text/javascript">

        $(document).ready(function ()
        {
            var scheduleOpts = {
                reservationUrlTemplate: "{$Path}{Pages::RESERVATION}?{QueryStringKeys::REFERENCE_NUMBER}=[referenceNumber]",
                summaryPopupUrl: "{$Path}ajax/respopup.php",
                setDefaultScheduleUrl: "{$Path}{Pages::PROFILE}?action=changeDefaultSchedule&{QueryStringKeys::SCHEDULE_ID}=[scheduleId]",
                cookieName: "{$CookieName}",
                scheduleId:"{$ScheduleId}"
            };

            var schedule = new Schedule(scheduleOpts, {$ResourceGroupsAsJson});
            schedule.init();
            scheduleId = {$ScheduleId};
            authHeaders = {$APIHeader};
            setUpCalender();
            setUpAddEventForm();
        });
    </script>

    <script type="text/template" id="template-calendar">
        <div class='clndr-controls'>
            <div class='clndr-control-button'>
                <span class='clndr-previous-button'>&laquo; previous</span>
            </div>
            <div class='month'>
                <%= month %> <%= year %>
            </div>
            <div class='clndr-control-button rightalign'>
                <span class='clndr-next-button'>next &raquo;</span>
            </div>
        </div>
        <table class='clndr-table' border='0' cellspacing='0' cellpadding='0'>
            <thead>
                <tr class='header-days'>
                    <% for(var i = 0; i < daysOfTheWeek.length; i++) { %>
                        <td class='header-day'><%= daysOfTheWeek[i] %></td>
                    <% } %>
                </tr>
            </thead>
            <tbody>
                <% for(var i = 0; i < numberOfRows; i++){ %>
                    <tr>
                        <% for(var j = 0; j < 7; j++){ %>
                            <% var d = j + i * 7; %>
                            <td class='<%= days[d].classes %>'>
                                <div class='day-contents'>
                                    <%= days[d].day %>
                                    <% if( days[d].events.length > 0) { %>
                                        <div class="total-events">
                                            <%= days[d].events.length %> 
                                            <% if( days[d].events.length == 1) { %>
                                                Event
                                            <% } else { %>
                                                Events
                                            <% } %>
                                        </div>
                                    <% } %>
                                </div>
                            </td>
                        <% } %>
                    </tr>
                <% } %>
            </tbody>
        </table>
    </script>
    <script type="text/template" id="table-row-events"> 
        <% for(var i = 0; i < events.length; i++) { %>
            <tr>
                <td rowspan="2"><strong><%= events[i].room %></strong></td>
                <td><%= events[i].startTime %></td>
                <td><%= events[i].endTime %></td>
                <td><%= events[i].title %></td>
                <td rowspan="2">
                    <form method="GET" action="/Web/reservation.php" class="hide-print">
                        <input type="hidden" name="rn" value="<%= events[i].referenceNumber %>">
                        <button type="submit" class="button save">Edit</button>
                    </form>
                </td>
            </tr>
            <tr class="expand-child">
                <td colspan="3"><strong>Desc.</strong>: <em><%= events[i].description %></em></td>
            </tr>
        <% } %>
    </script>
{/block}



{include file='globalfooter.tpl'}