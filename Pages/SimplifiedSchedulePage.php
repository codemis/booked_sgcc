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
require_once(ROOT_DIR . 'Presenters/SimplifiedSchedulePresenter.php');
require_once(ROOT_DIR . 'Presenters/SimplifiedSchedulePageBuilder.php');

class SimplifiedSchedulePage extends SchedulePage implements ISchedulePage
{

    public function __construct()
    {
        parent::__construct('Schedule');

        $permissionServiceFactory = new PermissionServiceFactory();
        $scheduleRepository = new ScheduleRepository();
        $userRepository = new UserRepository();
        $resourceService = new ResourceService(new ResourceRepository(), $permissionServiceFactory->GetPermissionService(), new AttributeService(new AttributeRepository()), $userRepository);
        $pageBuilder = new SimplifiedSchedulePageBuilder();
        $reservationService = new ReservationService(new ReservationViewRepository(), new ReservationListingFactory());
        $dailyLayoutFactory = new DailyLayoutFactory();
        $scheduleService = new ScheduleService($scheduleRepository, $resourceService);
        $this->_presenter = new SimplifiedSchedulePresenter($this, $scheduleService, $resourceService, $pageBuilder, $reservationService, $dailyLayoutFactory);
    }

    public function ProcessPageLoad()
    {
        $start = microtime(true);

        $user = ServiceLocator::GetServer()
                ->GetUserSession();

        $this->_presenter->PageLoad($user);

        $endLoad = microtime(true);

        $this->Display('Schedule/simplified-schedule.tpl');

        $endDisplay = microtime(true);

        $load = $endLoad - $start;
        $display = $endDisplay - $endLoad;
        $total = $endDisplay - $start;
        Log::Debug('Schedule took %s sec to load, %s sec to render. Total %s sec', $load, $display, $total);
    }

}