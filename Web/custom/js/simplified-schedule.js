var baseUrl = "/Web/Services/";
var token = "";
var iserId = "";
var authHeaders;
var authenticated = false;
var reservations;
var scheduleId = 1;
var eventCalendar;
jQuery(document).ready(function($) {
    setUpCalender('USERNAME', 'PASSWORD');
});
function setUpCalender(username, password) {
    authenticateUser(username, password);
    eventCalendar = $('.cal1').clndr({
        template: $('#template-calendar').html(),
        daysOfTheWeek: ['Sun', 'Mon', 'Tues', 'Wed', 'Thurs', 'Fri', 'Sat'],
        clickEvents:
        {
            click: function(target) {
                if (target.events.length > 0) {
                    var template = $("#table-row-events").html();
                    var tpl = _.template(template, {events: target.events});
                    $("table#event-listing tbody").html(tpl);
                } else {
                    var html = "<tr><td colspan='5'>No events schedule on this day.</td></tr>";
                    $("table#event-listing tbody").html(html);
                }
            },
            nextMonth: function()
                {
                    console.log('next month.');
                },
            previousMonth: function()
                {
                    console.log('previous month.');
                },
            onMonthChange: function()
                {
                    console.log('month changed.');
                },
            nextYear: function()
                {
                    console.log('next year.');
                },
            previousYear: function()
                {
                    console.log('previous year.');
                },
            onYearChange: function()
                {
                    console.log('year changed.');
                }
        },
        multiDayEvents: {
            startDate: 'startDate',
            endDate: 'endDate'
        },
        showAdjacentMonths: false,
        adjacentDaysChangeMonth: false
    });
};
function authenticateUser(username, password) {
    $.ajax(
    {
        type: "POST",
        url: baseUrl + "Authentication/Authenticate",
        data: JSON.stringify({username: username, password: password}),
        dataType: "json"
    })
    .done(function (data) {
        if (data.isAuthenticated) {
            token = data.sessionToken;
            userId = data.userId;
            authHeaders = {"X-Booked-SessionToken": data.sessionToken, "X-Booked-UserId": data.userId}
            authenticated = true;
            /* Get the current month's start and end dates and times */
            var startDateTime = moment().startOf('month').toISOString();
            var endDateTime = moment().endOf('month').toISOString();
            getResources(startDateTime, endDateTime);
        } else {
            alert(data.message);
        }
    });
};
function getResources(startDateTime, endDateTime) {
    url = baseUrl+"Reservations/?scheduleId="+scheduleId+"&startDateTime="+encodeURIComponent(startDateTime)+"&endDateTime="+encodeURIComponent(endDateTime);
    $.ajax(
        {
            type: "GET",
            url: url,
            headers: authHeaders,
            dataType: "json"
        })
        .done(function (data) {
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
                    isRecurring: val.isRecurring,
                    room: val.resourceName,
                    title: val.title,
                };
                reservations.push(newEvent);
            });
            eventCalendar.setEvents(reservations);
        }).error(function(e) {
            /* Act on the event */
            console.log(e.message);
        });   
};