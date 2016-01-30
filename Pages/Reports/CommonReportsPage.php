<?php
/**
Copyright 2012-2015 Nick Korbel

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
 */

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Pages/Reports/IDisplayableReportPage.php');
require_once(ROOT_DIR . 'Presenters/Reports/CommonReportsPresenter.php');

interface ICommonReportsPage extends IDisplayableReportPage, IActionPage
{

}

class CommonReportsPage extends ActionPage implements ICommonReportsPage
{
	/**
	 * @var CommonReportsPresenter
	 */
	private $presenter;

	public function __construct()
	{
		parent::__construct('CommonReports', 1);

		$this->presenter = new CommonReportsPresenter($this, ServiceLocator::GetServer()->GetUserSession(), new ReportingService(new ReportingRepository()));
	}

	/**
	 * @return void
	 */
	public function ProcessAction()
	{
		$this->presenter->ProcessAction();
	}

	/**
	 * @param $dataRequest string
	 * @return void
	 */
	public function ProcessDataRequest($dataRequest)
	{
		// no-op
	}

	/**
	 * @return void
	 */
	public function ProcessPageLoad()
	{
		$this->Display('Reports/common-reports.tpl');
	}

	public function BindReport(IReport $report, IReportDefinition $definition)
	{
		$this->Set('HideSave', true);
		$this->Set('Definition', $definition);
		$this->Set('Report', $report);
		$this->Set('singleDate', $this->isSingleDate($report, $definition));
	}

	public function ShowCsv()
	{
		$this->DisplayCsv('Reports/custom-csv.tpl', 'report.csv');
	}

	public function DisplayError()
	{
		$this->Display('Reports/error.tpl');
	}

	public function ShowResults()
	{
		$this->Display('Reports/results-custom.tpl');
	}

	public function PrintReport()
	{
		$this->Display('Reports/print-custom-report.tpl');
	}

	public function PrintMaintenanceReport()
	{
		$this->Display('Reports/print-maintenance-report.tpl');
	}

	/**
	 * Check if we are only reporting a single day
	 * @param  IReport				$report 	The reporting object
	 * @param  IReportDefinition	$definition The Reporting Definition Object
	 * @return string       	A date if it is a single date, an empty string if not
	 * @access private
	 *
	 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
	 */
	private function isSingleDate(IReport $report, IReportDefinition $definition)
	{
		$data = $report->GetData()->Rows();
		$startDates = [];
		foreach ($data as $row) {
			$i = 0;
			foreach ($definition->GetRow($row) as $item) {
				if ($i === 1) {
					$current = new DateTime($item->Value());
					if (!in_array($current->format('Y-m-d'), $startDates)) {
						array_push($startDates, $current->format('Y-m-d'));
					}
				}
				$i++;
			}
		}
		if ((count($startDates) === 1)) {
			return $startDates[0];
		} else {
			return '';
		}
	}

	/**
	 * @return int
	 */
	public function GetReportId()
	{
		return $this->GetQuerystring(QueryStringKeys::REPORT_ID);
	}

	/**
	 * @param string $emailAddress
	 */
	public function SetEmailAddress($emailAddress)
	{
		$this->Set('UserEmail', $emailAddress);
	}

	/**
	 * @return string
	 */
	public function GetEmailAddress()
	{
		return $this->GetForm(FormKeys::EMAIL);
	}
}

?>
