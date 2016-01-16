{*
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
*}
<!DOCTYPE HTML>
<html lang="{$HtmlLang}" dir="{$HtmlTextDirection}">
<head>
	<title>{if $TitleKey neq ''}{translate key=$TitleKey args=$TitleArgs}{else}{$Title}{/if}</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$Charset}"/>
	<link rel="stylesheet" type="text/css" media="print,screen" href="/Web/custom/css/report-print.css">
</head>
<body>
{if $singleDate != ''}
	<div class="single-date">
		{$singleDate|date_format:"%B %e, %G"}
	</div>
{/if}
<table width="100%" border="1">
	<tr>
		<th data-columnTitle="Resource">Resource</th>
		<th data-columnTitle="Start">Start</th>
		<th data-columnTitle="End">End</th>
		<th data-columnTitle="Title">Title</th>
		<th data-columnTitle="Description">Description</th>
	</tr>
	{foreach from=$Report->GetData()->Rows() item=row}
		<tr>
			<td>{$row['resource_name']}</td>
			<td>
				{if $singleDate != ''}
					{$row['start_date']|date_format:"%l:%M %p"}
				{else}
					{$row['start_date']}
				{/if}
			</td>
			<td>
				{if $singleDate != ''}
					{$row['end_date']|date_format:"%l:%M %p"}
				{else}
					{$row['end_date']}
				{/if}
			</td>
			<td>{$row['title']}</td>
			<td>{$row['description']}</td>
		</tr>
	{/foreach}
</table>
{$Report->ResultCount()} {translate key=Rows}
{if $Definition->GetTotal() != ''}
	| {$Definition->GetTotal()} {translate key=Total}
{/if}

<p>{translate key=Created}: {format_date date=Date::Now() key=general_datetime}</p>
{jsfile src="reports/common.js"}

<script type="text/javascript">
	var common = new ReportsCommon(
			{
				scriptUrl: '{$ScriptUrl}'
			}
	);
	common.init();
	window.print();
</script>

</body>
</html>
