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

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Presenters/SimplifiedSchedulePresenter.php');

interface ISimplifiedSchedulePage extends IActionPage
{

    /**
     * @param bool $shouldShow
     */
    public function ShowPermissionError($shouldShow);

}

class SimplifiedSchedulePage extends ActionPage implements ISimplifiedSchedulePage
{

    /**
     * @var SchedulePresenter
     */
    protected $_presenter;

    public function __construct()
    {
        parent::__construct('Schedule');

        $permissionServiceFactory = new PermissionServiceFactory();
        $userRepository = new UserRepository();
        $resourceService = new ResourceService(new ResourceRepository(), $permissionServiceFactory->GetPermissionService(), new AttributeService(new AttributeRepository()), $userRepository);
        $reservationService = new ReservationService(new ReservationViewRepository(), new ReservationListingFactory());
        $this->_presenter = new SimplifiedSchedulePresenter($this, $resourceService, $reservationService);
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

    public function ProcessDataRequest($dataRequest)
    {
        $this->_presenter->GetLayout(ServiceLocator::GetServer()
                                     ->GetUserSession());
    }

    public function ProcessAction()
    {
        // no-op
    }

    public function ShowInaccessibleResources()
    {
        return Configuration::Instance()
               ->GetSectionKey(ConfigSection::SCHEDULE,
                               ConfigKeys::SCHEDULE_SHOW_INACCESSIBLE_RESOURCES,
                               new BooleanConverter());
    }

    public function ShowPermissionError($shouldShow)
    {
        $this->Set('IsAccessible', !$shouldShow);
    }
}