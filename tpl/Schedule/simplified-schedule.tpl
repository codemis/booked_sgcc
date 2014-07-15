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
    {include file='globalheader.tpl' cssFiles='css/jquery.qtip.min.css,scripts/css/jqtree.css,css/schedule.css,css/admin.css,css/sgcc-custom.css'}
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
        <div id="simplified-calendar">CALENDAR
        </div>

        {block name="reservations"}
            <div id="simplified-listing">
                <div class="admin">
                    <div class="title">
                        Current Events
                    </div>
                    <div class="scheduleDetails">
                        <div style="float:left;">
                            LEFT TEXT
                        </div>

                        <div class="layout">
                            RIGHT TEXT
                        </div>
                        <div class="actions">
                            ACTIONS
                        </div>
                    </div>
                    <div class="scheduleDetails">
                        <div style="float:left;">
                            LEFT TEXT
                        </div>

                        <div class="layout">
                            RIGHT TEXT
                        </div>
                        <div class="actions">
                            ACTIONS
                        </div>
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
    {jsfile src="js/jquery.qtip.min.js"}
    {jsfile src="js/moment.min.js"}
    {jsfile src="schedule.js"}
    {jsfile src="resourcePopup.js"}
    {jsfile src="js/tree.jquery.js"}
    {jsfile src="js/jquery.cookie.js"}

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
{/block}

{include file='globalfooter.tpl'}