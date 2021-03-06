var baseUrl = "/Web/Services/";
var authHeaders;
var reservations;
var scheduleId = 0;
var eventCalendar;
/**
 * Sets up the eventCalendar calendar using JQuery CLNDR
 * @return void
 */
function setUpCalender() {
    $("table.tablesorter").tablesorter(
        {
            theme: 'ice',
            widgets: ["zebra"],
            cssChildRow: "expand-child",
            widgetOptions : {
                zebra : ["odd", "even"]
            }
        }
    );
    eventCalendar = $('.cal1').clndr({
        template: $('#template-calendar').html(),
        daysOfTheWeek: ['Sun', 'Mon', 'Tues', 'Wed', 'Thurs', 'Fri', 'Sat'],
        clickEvents:
        {
            click: function(target) {
                $('#simplified-listing').find('div.title').eq(1).text("Events for "+moment(target.date).format("dddd, MMMM Do YYYY"));
                if (target.events.length > 0) {
                    var template = $("#table-row-events").html();
                    var tpl = _.template(template, {events: target.events});
                    $("table#event-listing tbody").html(tpl);
                } else {
                    var html = "<tr><td colspan='5'>No events schedule on this day.</td></tr>";
                    $("table#event-listing tbody").html(html);
                }
                $("table.tablesorter").trigger("update").trigger('sortReset');
            },
            onMonthChange: function(month)
                {
                    var startDateTime = month.startOf('month').toISOString();
                    var endDateTime = month.clone().add('months', 1).startOf('month').toISOString();
                    getReservations(startDateTime, endDateTime);
                }
        },
        doneRendering: function() {
            $('td.today').trigger('click');
        },
        multiDayEvents: {
            startDate: 'startDate',
            endDate: 'endDate'
        },
        showAdjacentMonths: false,
        adjacentDaysChangeMonth: false
    });
    var startDateTime = moment().startOf('month').toISOString();
    var endDateTime = moment().add('months', 1).startOf('month').toISOString();
    getReservations(startDateTime, endDateTime);
};
/**
 * Sets up the autocomplete for the Add Event form with the Resources from the Booked API
 *
 * @return void
 */
function setUpAddEventForm() {
    /* We need to setup the autocomplete */
    $('#autocomplete-resources').click(function(event) {
        $(this).val('');
        $('#autocomplete-resources').autocomplete("search", "");
        return true;
    });
    url = baseUrl+"Resources/?scheduleId="+scheduleId;
    $.ajax(
        {
            type: "GET",
            url: url,
            headers: authHeaders,
            dataType: "json"
        })
        .done(function (data) {
            var tags = [];
            $.each(data.resources, function(index, val) {
                tags.push({value: val.resourceId, label: val.name});
            });
            $('#autocomplete-resources').autocomplete({
                source: tags,
                minLength: 0,
                select: function(event, ui) {
                    var selectedObj = ui.item;
                    $('#autocomplete-resources').val(selectedObj.label);
                    $('#selected-resource-id').val(selectedObj.value);
                    event.preventDefault();
                }
            });
        }).error(function(e) {
            /* Act on the event */
            console.log(e.message);
        }
    );
};
/**
 * Get the Reservations for the given dates.  Uses an AJAX request to grab the data from the API, and fills up the eventCalendar calendar.
 * Depends on the var eventCalendar to be a JQuery CLNDR object
 *
 * @param String startDateTime the start datetime in ISO
 * @param String endDateTime the end datetime in ISO
 * @return void
 */
function getReservations(startDateTime, endDateTime) {
    url = baseUrl+"Reservations/?scheduleId="+scheduleId+"&startDateTime="+encodeURIComponent(startDateTime)+"&endDateTime="+encodeURIComponent(endDateTime);
    console.log(url);
    $.ajax(
        {
            type: "GET",
            url: url,
            headers: authHeaders,
            dataType: "json"
        })
        .done(function (data) {
            console.log(data);
            reservations = [];
            $.each(data.reservations, function (idx, val) {
                var newEvent = {
                    startDateMoment: moment(val.startDate, "YYYY-MM-DD"),
                    endDateMoment: moment(val.endDate, "YYYY-MM-DD"),
                    startDate: moment(val.startDate).format("YYYY-MM-DD"),
                    endDate: moment(val.endDate).format("YYYY-MM-DD"),
                    startTime: moment(val.startDate).format("hh:mm a"),
                    endTime: moment(val.endDate).format("hh:mm a"),
                    duration: val.duration,
                    description: val.description,
                    creatorFirstName: val.firstName,
                    creatorLastName: val.lastName,
                    room: val.resourceName,
                    title: val.title,
                    referenceNumber: val.referenceNumber
                };
                reservations.push(newEvent);
            });
            eventCalendar.setEvents(reservations);
        }).error(function(e) {
            /* Act on the event */
            console.log(e.message);
        });
};
