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

require_once(ROOT_DIR . 'lib/Config/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');
require_once(ROOT_DIR . 'lib/Server/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Presenters/SchedulePageBuilder.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');

interface ISimplifiedSchedulePresenter {

    public function PageLoad(UserSession $user);
}

class SimplifiedSchedulePresenter extends ActionPresenter implements ISimplifiedSchedulePresenter {

    /**
     * @var ISimplifiedSchedulePage
     */
    private $_page;

    /**
     * @var IResourceService
     */
    private $_resourceService;

    /**
     * @var IReservationService
     */
    private $_reservationService;

    /**
     * @param ISchedulePage $page
     * @param IResourceService $resourceService
     * @param IReservationService $reservationService
     * @param IDailyLayoutFactory $dailyLayoutFactory
     */
    public function __construct(
        ISimplifiedSchedulePage $page,
        IResourceService $resourceService,
        IReservationService $reservationService
    )
    {
		parent::__construct($page);
        $this->_page = $page;
        $this->_resourceService = $resourceService;
        $this->_reservationService = $reservationService;
    }

    public function PageLoad(UserSession $user)
    {

        $showInaccessibleResources = $this->_page->ShowInaccessibleResources();

        $schedules = $this->_scheduleService->GetAll($showInaccessibleResources, $user);

		if (count($schedules) == 0)
		{
			$this->_page->ShowPermissionError(true);
			return;
		}

		$this->_page->ShowPermissionError(false);

    }
}

?>