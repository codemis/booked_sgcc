<?php
/**
Copyright 2011-2014 Nick Korbel

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

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

/**
 * Application configuration
 */
$conf['settings']['app.title'] = 'SGCC Scheduler';          // application title
$conf['settings']['default.timezone'] = 'America/Los_Angeles';      // look up here http://php.net/manual/en/timezones.php
$conf['settings']['allow.self.registration'] = 'false';          // if users can register themselves
$conf['settings']['admin.email'] = 'johnathan@missionaldigerati.org';         // email address of admin user
$conf['settings']['admin.email.name'] = 'Johnathan Pulos';  // name to be used in From: field when sending automatic emails
$conf['settings']['default.page.size'] = '50';                  // number of records per page
$conf['settings']['enable.email'] = 'true';                     // global configuration to enable if any emails will be sent
$conf['settings']['default.language'] = 'en_us';                // find your language in the lang directory
$conf['settings']['script.url'] = 'http://calendar.sangabrielchristian.org/Web';    // public URL to the Web directory of this instance. this is the URL that appears when you are logging in. leave http: or https: off to auto-detect
$conf['settings']['image.upload.directory'] = 'Web/uploads/images'; // full or relative path to where images will be stored
$conf['settings']['image.upload.url'] = 'uploads/images';       // full or relative path to show uploaded images from
$conf['settings']['cache.templates'] = 'false';                  // true recommended, caching template files helps web pages render faster
$conf['settings']['use.local.jquery'] = 'false';                // false recommended, delivers jQuery from Google CDN, uses less bandwidth
$conf['settings']['registration.captcha.enabled'] = 'true';     // recommended. unless using recaptcha this requires php_gd2 enabled in php.ini
$conf['settings']['registration.require.email.activation'] = 'false';       // requires enable.email = true
$conf['settings']['registration.auto.subscribe.email'] = 'false';           // requires enable.email = true
$conf['settings']['registration.notify.admin'] = 'false';       // whether the registration of a new user sends an email to the admin (ala phpScheduleIt 1.2)
$conf['settings']['inactivity.timeout'] = '30';                 // minutes before the user is automatically logged out
$conf['settings']['name.format'] = '{first} {last}';            // display format when showing user names
$conf['settings']['css.extension.file'] = '';                   // full or relative url to an additional css file to include. this can be used to override the default style
$conf['settings']['disable.password.reset'] = 'false';          // if the password reset functionality should be disabled
$conf['settings']['home.url'] = '/Web/simplified-schedule.php';                             // the url to open when the logo is clicked
$conf['settings']['logout.url'] = '';                           // the url to be directed to after logging out

$conf['settings']['schedule']['use.per.user.colors'] = 'false';         // color reservations by user
$conf['settings']['schedule']['show.inaccessible.resources'] = 'true';  // whether or not resources that are inaccessible to the user are visible
$conf['settings']['schedule']['reservation.label'] = '{title}';         // format for what to display on the reservation slot label. Available properties are: {name}, {title}, {description}, {email}, {phone}, {organization}, {position}
$conf['settings']['schedule']['hide.blocked.periods'] = 'false';        // if blocked periods should be hidden or shown

/**
 * ical integration configuration
 */
$conf['settings']['ics']['require.login'] = 'true';             // recommended, if the user must be logged in to access ics files
$conf['settings']['ics']['subscription.key'] = '';              // must be set to allow webcal subscriptions
$conf['settings']['ics']['import'] = 'false';                   // enable iCal import
$conf['settings']['ics']['import.key'] = '';                    // it's recommended  to set this key when iCal import is enabled
/**
 * Privacy configuration
 */
$conf['settings']['privacy']['view.schedules'] = 'true';                // if unauthenticated users can view schedules
$conf['settings']['privacy']['view.reservations'] = 'false';                // if unauthenticated users can view reservations
$conf['settings']['privacy']['hide.user.details'] = 'false';                // if personal user details should be displayed to non-administrators
$conf['settings']['privacy']['hide.reservation.details'] = 'false';         // if reservation details should be displayed to non-administrators
/**
 * Reservation specific configuration
 */
$conf['settings']['reservation']['start.time.constraint'] = 'future';       // when reservations can be created or edited. options are future, current, none
$conf['settings']['reservation']['updates.require.approval'] = 'false';     // if updates to previously approved reservations require approval again
$conf['settings']['reservation']['prevent.participation'] = 'false';        // if participation and invitation options should be removed
$conf['settings']['reservation']['prevent.recurrence'] = 'false';           // if recurring reservations are disabled for non-administrators
$conf['settings']['reservation']['enable.reminders'] = 'false';             // if reminders are enabled. this requires email to be enabled and the reminder job to be configured
/**
 * Email notification configuration
 */
$conf['settings']['reservation.notify']['resource.admin.add'] = 'false';
$conf['settings']['reservation.notify']['resource.admin.update'] = 'false';
$conf['settings']['reservation.notify']['resource.admin.delete'] = 'false';
$conf['settings']['reservation.notify']['application.admin.add'] = 'false';
$conf['settings']['reservation.notify']['application.admin.update'] = 'false';
$conf['settings']['reservation.notify']['application.admin.delete'] = 'false';
$conf['settings']['reservation.notify']['group.admin.add'] = 'false';
$conf['settings']['reservation.notify']['group.admin.update'] = 'false';
$conf['settings']['reservation.notify']['group.admin.delete'] = 'false';
/**
 * File upload configuration
 */
$conf['settings']['uploads']['enable.reservation.attachments'] = 'false';   // if reservation attachments can be uploaded
$conf['settings']['uploads']['reservation.attachment.path'] = 'uploads/reservation';    // full or relative (to the root of your installation) filesystem path to store reservation attachments
$conf['settings']['uploads']['reservation.attachment.extensions'] = 'txt,jpg,gif,png,doc,docx,pdf,xls,xlsx,ppt,pptx,csv';   // comma separated list of file extensions that users are allowed to attach. leave empty to allow all extensions
/**
 * Database configuration
 */
$conf['settings']['database']['type'] = 'mysql';
$conf['settings']['database']['user'] = 'root';        // database user with permission to the booked database
$conf['settings']['database']['password'] = 'root';
$conf['settings']['database']['hostspec'] = 'localhost';        // ip, dns or named pipe
$conf['settings']['database']['name'] = 'booked_sgcc';
/**
 * Mail server configuration
 */
$conf['settings']['phpmailer']['mailer'] = 'mail';              // options are 'mail', 'smtp' or 'sendmail'
$conf['settings']['phpmailer']['smtp.host'] = '';               // 'smtp.company.com'
$conf['settings']['phpmailer']['smtp.port'] = '25';
$conf['settings']['phpmailer']['smtp.secure'] = '';             // options are '', 'ssl' or 'tls'
$conf['settings']['phpmailer']['smtp.auth'] = 'true';           // options are 'true' or 'false'
$conf['settings']['phpmailer']['smtp.username'] = '';
$conf['settings']['phpmailer']['smtp.password'] = '';
$conf['settings']['phpmailer']['sendmail.path'] = '/usr/sbin/sendmail';
$conf['settings']['phpmailer']['smtp.debug'] = 'false';
/**
 * Plugin configuration.  For more on plugins, see readme_installation.html
 */
$conf['settings']['plugins']['Authentication'] = '';
$conf['settings']['plugins']['Authorization'] = '';
$conf['settings']['plugins']['Permission'] = '';
$conf['settings']['plugins']['PostRegistration'] = '';
$conf['settings']['plugins']['PreReservation'] = '';
$conf['settings']['plugins']['PostReservation'] = '';
/**
 * Installation settings
 */
$conf['settings']['install.password'] = 'TofEvTx8C3';
/**
 * Pages
 */
$conf['settings']['pages']['enable.configuration'] = 'true';
/**
 * API
 */
$conf['settings']['api']['enabled'] = 'true';
/**
 * ReCaptcha
 */
$conf['settings']['recaptcha']['enabled'] = 'false';
$conf['settings']['recaptcha']['public.key'] = '';
$conf['settings']['recaptcha']['private.key'] = '';
/**
 * Email
 */
$conf['settings']['email']['default.from.address'] = '';
$conf['settings']['email']['default.from.name'] = '';
/**
 * Reports
 */
$conf['settings']['reports']['allow.all.users'] = 'false';
/**
 * Account Password Rules
 */
$conf['settings']['password']['minimum.letters'] = '6';
$conf['settings']['password']['minimum.numbers'] = '0';
$conf['settings']['password']['upper.and.lower'] = 'false';