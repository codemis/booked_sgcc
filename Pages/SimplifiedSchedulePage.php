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
require_once(ROOT_DIR . 'Vendor/PHPToolbox/CachedRequest/CurlUtility.php');
require_once(ROOT_DIR . 'config/APIConfig.php');

class SimplifiedSchedulePage extends SchedulePage implements ISchedulePage
{
    /**
     * The cURL Utility Object
     *
     * @var object
     * @access protected
     **/
    protected $curlUtility;

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
        $this->curlUtility = new PHPToolbox\CachedRequest\CurlUtility();
    }

    public function ProcessPageLoad()
    {
        $start = microtime(true);

        $user = ServiceLocator::GetServer()->GetUserSession();

        $this->Set('APIHeader', json_encode($this->getAPIHeaders()));

        $this->_presenter->PageLoad($user);

        $endLoad = microtime(true);

        $this->Display('Schedule/simplified-schedule.tpl');

        $endDisplay = microtime(true);

        $load = $endLoad - $start;
        $display = $endDisplay - $endLoad;
        $total = $endDisplay - $start;
        Log::Debug('Schedule took %s sec to load, %s sec to render. Total %s sec', $load, $display, $total);
    }

    private function getAPIHeaders() {
        if ($this->needsNewAPISession()) {
            $APIConfig = new config\APIConfig();
            $fields = $APIConfig->getCredentials();
            $response = $this->curlUtility->makeRequest("http://" . $_SERVER['SERVER_NAME'] . "/Web/Services/Authentication/Authenticate", "POST", json_encode($fields));
            $data = json_decode($response);
            $_SESSION['API.sessionToken'] = $data->sessionToken;
            $_SESSION['API.userId'] = $data->userId;
            $_SESSION['API.expiresOn'] = $data->sessionExpires;
        }
        return array(
            'X-Booked-SessionToken' =>  $_SESSION['API.sessionToken'],
            'X-Booked-UserId' =>  $_SESSION['API.userId'],
        );
    }

    /**
     * Do we need to get a new API key?
     *
     * @return boolean
     * @access private
     * @author Johnathan Pulos
     **/
    private function needsNewAPISession() {
        if (
            ((array_key_exists('API.sessionToken', $_SESSION)) && ($_SESSION['API.sessionToken'] != '')) &&
            ((array_key_exists('API.userId', $_SESSION)) && ($_SESSION['API.userId'] != '')) &&
            ((array_key_exists('API.expiresOn', $_SESSION)) && ($_SESSION['API.expiresOn'] != ''))
            ) {
            if (!$this->hasExpired($_SESSION['API.expiresOn'])) {
                 return false;
             }
        }
        return true;
    }

    /**
     * Checks whether the date has expired
     *
     * @param string $dateToCheck the date to compare to now
     * @return boolean (has it expired)
     * @access private
     * @author Johnathan Pulos
     **/
    private function hasExpired($dateToCheck) {
        $now = new DateTime();
        $expires = new DateTime($dateToCheck);
        return ($now > $expires);
    }

}
