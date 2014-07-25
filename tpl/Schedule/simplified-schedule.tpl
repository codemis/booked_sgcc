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
    {include file='globalheader.tpl' cssFiles='css/jquery.qtip.min.css,scripts/css/jqtree.css,css/schedule.css,css/admin.css,custom/css/clndr.css,custom/css/sgcc-custom.css'}
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
        <a href="/Web/reservation.php?sid=1">Add an Event</a>
        <div id="simplified-calendar">CALENDAR
            <div id="the-schedule" class="cal1"></div>
        </div>

        {block name="reservations"}
            <div id="simplified-listing">
                <div class="admin">
                    <div class="title">
                        Current Events
                    </div>
                    <div class="scheduleDetails schedule-details-simplified">
                        <table id="event-listing" width="100%">
                            <thead>
                                <tr>
                                    <th>Room</th>
                                    <th>Time</th>
                                    <th>Event</th>
                                    <th>Recurring</th>
                                    <th>Action</th>
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
        });
    </script>

    <script type="text/template" id="template-calendar">
        <div class='clndr-controls'>
            <div class='clndr-control-button'>
                <span class='clndr-previous-button'>previous</span>
            </div>
            <div class='month'>
                <%= month %> <%= year %>
            </div>
            <div class='clndr-control-button rightalign'>
                <span class='clndr-next-button'>next</span>
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
                                        <div class="">
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
                <td><%= events[i].room %></td>
                <td><%= events[i].startTime %> - <%= events[i].endTime %></td>
                <td><strong><%= events[i].title %></strong><br><em><%= events[i].description %></em></td>
                <td>
                    <% if (events[i].isRecurring) { %>
                        Yes
                    <% } else { %>
                        No
                    <% } %>
                </td>
                <td><a href="">Edit</a></td>
            </tr>
        <% } %>
    </script>
{/block}



{include file='globalfooter.tpl'}