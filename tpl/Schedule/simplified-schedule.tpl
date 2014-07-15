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
    {include file='globalheader.tpl' cssFiles='css/jquery.qtip.min.css,scripts/css/jqtree.css,css/schedule.css'}
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

    {block name="reservations"}
        {foreach from=$BoundDates item=date}
            {$date}
                {foreach from=$Resources item=resource name=resource_loop}
                    {$resource->Name}
                {/foreach}
            {flush}
        {/foreach}
    {/block}
{else}
    <div class="error">{translate key=NoResourcePermission}</div>
{/if}

{block name="scripts"}

{/block}

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
        });
    </script>
{/block}

{include file='globalfooter.tpl'}