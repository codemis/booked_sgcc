var baseUrl = "/Web/Services/";
var authHeaders;
var reservations;
var scheduleId = 0;
var eventCalendar;
function setUpCalender(APIHeaders) {
    authHeaders = APIHeaders;
    eventCalendar = $('.cal1').clndr({
        template: $('#template-calendar').html(),
        daysOfTheWeek: ['Sun', 'Mon', 'Tues', 'Wed', 'Thurs', 'Fri', 'Sat'],
        clickEvents:
        {
            click: function(target) {
                $('#simplified-listing').find('div.title:first').text("Events for "+moment(target.date).format("dddd, MMMM Do YYYY"));
                if (target.events.length > 0) {
                    var template = $("#table-row-events").html();
                    var tpl = _.template(template, {events: target.events});
                    $("table#event-listing tbody").html(tpl);
                } else {
                    var html = "<tr><td colspan='5'>No events schedule on this day.</td></tr>";
                    $("table#event-listing tbody").html(html);
                }
            },
            onMonthChange: function(month)
                {
                    var startDateTime = month.startOf('month').toISOString();
                    var endDateTime = month.endOf('month').toISOString();
                    getResources(startDateTime, endDateTime);
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
    var endDateTime = moment().endOf('month').toISOString();
    getResources(startDateTime, endDateTime);
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