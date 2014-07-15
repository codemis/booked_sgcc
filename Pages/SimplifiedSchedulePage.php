<?php
/**
 * Copyright 2011-2014 Nick Korbel
 * 
 * This file is part of Booked Scheduler, but has been modified by Johnathan Pulos.
 * 
 * Booked Scheduler is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Booked Scheduler is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Booked Scheduler.  If not, see <http://www.gnu.org/licenses/>.
 */
require_once(ROOT_DIR . 'Pages/SchedulePage.php');
/**
 * This class allows us to inherit all the functionality of the SchedulePage Class, but overwrite the displaying
 * of that page.  We want a custom view.
 *
 * @package default
 * @author Johnathan Pulos
 **/
class SimplifiedSchedulePage extends SchedulePage
{
    public function ProcessPageLoad()
    {
        $start = microtime(true);

        $user = ServiceLocator::GetServer()
                ->GetUserSession();

        $this->_presenter->PageLoad($user);

        $endLoad = microtime(true);

        $this->Set('SlotLabelFactory', $user->IsAdmin ? new AdminSlotLabelFactory() : new SlotLabelFactory($user));
        $this->Set('DisplaySlotFactory', new DisplaySlotFactory());

        $this->Display('Schedule/simplified-schedule.tpl');

        $endDisplay = microtime(true);

        $load = $endLoad - $start;
        $display = $endDisplay - $endLoad;
        $total = $endDisplay - $start;
        Log::Debug('Schedule took %s sec to load, %s sec to render. Total %s sec', $load, $display, $total);
    }

}
?>