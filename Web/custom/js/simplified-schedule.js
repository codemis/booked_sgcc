var baseUrl = "/Web/Services/";
var token = "";
var iserId = "";
var authHeaders;
var authenticated = false;
var reservations;
var scheduleId = 1;
var calendars = {};
jQuery(document).ready(function($) {
    setUpCalender('USERNAME', 'PASSWORD');
});
function setUpCalender(username, password) {
    // authenticateUser(username, password, getIntialResources);
  var thisMonth = moment().format('YYYY-MM');

  var eventArray = [
    { startDate: thisMonth + '-10', endDate: thisMonth + '-21', title: 'Multi-Day Event', special_tag: 'Boogie Man'},
    { startDate: thisMonth + '-21', endDate: thisMonth + '-23', title: 'Another Multi-Day Event', special_tag: 'Ice Cream' },
    { startDate: thisMonth + '-21', title: 'A Single Day Event', special_tag: 'Ice Cream' }
  ];

  calendars.clndr1 = $('.cal1').clndr({
    template: $('#template-calendar').html(),
    events: eventArray,
    daysOfTheWeek: ['Sun', 'Mon', 'Tues', 'Wed', 'Thurs', 'Fri', 'Sat'],
    clickEvents: {
      click: function(target) {
        console.log(target.events);
        var template = $("#table-row-events").html();
        var tpl = _.template(template, {events: target.events});
        $("table#event-listing").html(tpl);
      },
      nextMonth: function() {
        console.log('next month.');
      },
      previousMonth: function() {
        console.log('previous month.');
      },
      onMonthChange: function() {
        console.log('month changed.');
      },
      nextYear: function() {
        console.log('next year.');
      },
      previousYear: function() {
        console.log('previous year.');
      },
      onYearChange: function() {
        console.log('year changed.');
      }
    },
    multiDayEvents: {
      startDate: 'startDate',
      endDate: 'endDate'
    },
    showAdjacentMonths: true,
    adjacentDaysChangeMonth: false
  });
};
function authenticateUser(username, password, callback) {
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
            callback();
        } else {
            alert(data.message);
        }
    });
};
function getIntialResources() {
    $.ajax(
        {
            type: "GET",
            url: baseUrl + "Reservations",
            headers: authHeaders,
            dataType: "json",
            data: {scheduleId: scheduleId}
        })
        .done(function (data) {
            reservations = [];
            $.each(data.reservations, function (idx, val) {
            });
        });       
};
function getUniqueDateTimeId(dateTime) {
    var date = new Date(dateTime);
    var components = [
        date.getFullYear(),
        date.getMonth(),
        date.getDate(),
        date.getHours(),
        date.getMinutes(),
        date.getSeconds(),
        date.getMilliseconds()
    ];

    return components.join("_");
};
function getAllDaysStartEndAndInBetween(start, end) {
    var startDate = new Date(start);
    var endDate = new Date(end);
    var allDates = [];
    while(startDate < endDate) {
        allDates.push(new Date(startDate));
        startDate = new Date(startDate.setDate(
            startDate.getDate() + 1
        ));
    }
    allDates.push(new Date(endDate));
    return allDates;
};