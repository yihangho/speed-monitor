<?php
require_once(__DIR__ . "/../commons/mysql.php");
require_once(__DIR__ . "/../commons/config.php");

function send_email($recipients, $subject, $body) {
    $post_fields = [];
    $post_fields["to"] = $recipients;
    $post_fields["from"] = "Speed Minitor <cron@" . MAILGUN_DOMAIN . ">";
    $post_fields["subject"] = $subject;
    $post_fields["text"] = $body;


    $curl = curl_init("https://api.mailgun.net/v2/" . MAILGUN_DOMAIN . "/messages");
    curl_setopt($curl, CURLOPT_USERPWD, "api:" . MAILGUN_API);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
    curl_exec($curl);
    curl_close($ch);
}

$current_timestamp = time();
error_log("current timestamp: " . $current_timestamp);

$results = $mysql->query("SELECT `ts` FROM `speedtest` ORDER BY `ts` DESC LIMIT 1");
$last_timestamp = $results->fetch_row()[0];
$hours = ($current_timestamp - $last_timestamp) / 3600.0;
$hours_required = intval(NO_SUBMISSION_NOTIFICATION);
$results = $mysql->query("SELECT `value` FROM `system` WHERE `config` = 'last_no_submission_notification'");
$last_notification = intval($results->fetch_row()[0]);
error_log("No submission notification");
error_log("Last timestamp: " . $last_timestamp);
error_log("Hours elapsed: " . $hours);
error_log("Hours needed: " . $hours_required);
error_log("Last notification: " . $last_notification);
if ($hours >= $hours_required && $last_notification < $last_timestamp) {
    error_log("Send notification");
    $mysql->query("UPDATE `system` SET `value` = '$current_timestamp' WHERE `config` = 'last_no_submission_notification'");
    send_email(NO_SUBMISSION_NOTIFICATION_RECIPIENT, "Speed Monitor Notification: no submission received", "The last submission received was about $hours hours ago.");
} else {
    error_log("Notification not needed");
}

$low_speed = intval(LOW_SPEED_CUTOFF);
$results = $mysql->query("SELECT `ts` FROM `speedtest` WHERE `dl` > '$low_speed' ORDER BY `ts` DESC LIMIT 1");
$last_timestamp = $results->fetch_row()[0];
$hours = ($current_timestamp - $last_timestamp) / 3600.0;
$hours_required = intval(LOW_SPEED_NOTIFICATION);
$results = $mysql->query("SELECT `value` FROM `system` WHERE `config` = 'last_low_speed_notification'");
$last_notification = intval($results->fetch_row()[0]);
error_log("Low submission notification");
error_log("Last timestamp: " . $last_timestamp);
error_log("Hours elapsed: " . $hours);
error_log("Hours needed: " . $hours_required);
error_log("Last notification: " . $last_notification);
if ($hours >= $hours_required && $last_notification < $last_timestamp) {
    error_log("Send notification");
    $mysql->query("UPDATE `system` SET `value` = '$current_timestamp' WHERE `config` = 'last_low_speed_notification'");
    send_email(LOW_SPEED_NOTIFICATION_RECIPIENT, "Speed Monitor Notification: low speed", "The Internet speed is slow for the past $hours hours.");
} else {
    error_log("Notification not needed");
}
?>
