<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Add event handlers for the quiz
 *
 * @package    mod_quiz
 * @category   event
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


$handlers = array(
    // Handle our own quiz_attempt_submitted event, as a way to send confirmation
    // messages asynchronously.
    'quiz_attempt_submitted' => array (
        'handlerfile'     => '/mod/quiz/locallib.php',
        'handlerfunction' => 'quiz_attempt_submitted_handler',
        'schedule'        => 'cron',
    ),

    // Handle our own quiz_attempt_overdue event, to email the student to let them
    // know they forgot to submit, and that they have another chance.
    'quiz_attempt_overdue' => array (
        'handlerfile'     => '/mod/quiz/locallib.php',
        'handlerfunction' => 'quiz_attempt_overdue_handler',
        'schedule'        => 'cron',
    ),

);

$observers = array(

    // Handle group events, so that open quiz attempts with group overrides get updated check times.
    array(
        'eventname' => '\core\event\course_reset_started',
        'callback' => '\mod_quiz\group_observers::course_reset_started',
    ),
    array(
        'eventname' => '\core\event\course_reset_ended',
        'callback' => '\mod_quiz\group_observers::course_reset_ended',
    ),
    array(
        'eventname' => '\core\event\group_deleted',
        'callback' => '\mod_quiz\group_observers::group_deleted'
    ),
    array(
        'eventname' => '\core\event\group_member_added',
        'callback' => '\mod_quiz\group_observers::group_member_added',
    ),
    array(
        'eventname' => '\core\event\group_member_removed',
        'callback' => '\mod_quiz\group_observers::group_member_removed',
    ),

);

/* List of events generated by the quiz module, with the fields on the event object.

quiz_attempt_started
    ->component   = 'mod_quiz';
    ->attemptid   = // The id of the new quiz attempt.
    ->timestart   = // The timestamp of when the attempt was started.
    ->timestamp   = // The timestamp of when the attempt was started.
    ->userid      = // The user id that the attempt belongs to.
    ->quizid      = // The quiz id of the quiz the attempt belongs to.
    ->cmid        = // The course_module id of the quiz the attempt belongs to.
    ->courseid    = // The course id of the course the quiz belongs to.

quiz_attempt_submitted
    ->component   = 'mod_quiz';
    ->attemptid   = // The id of the quiz attempt that was submitted.
    ->timefinish  = // The timestamp of when the attempt was submitted.
    ->timestamp   = // The timestamp of when the attempt was submitted.
    ->userid      = // The user id that the attempt belongs to.
    ->submitterid = // The user id of the user who sumitted the attempt.
    ->quizid      = // The quiz id of the quiz the attempt belongs to.
    ->cmid        = // The course_module id of the quiz the attempt belongs to.
    ->courseid    = // The course id of the course the quiz belongs to.

quiz_attempt_overdue
    ->component   = 'mod_quiz';
    ->attemptid   = // The id of the quiz attempt that has become overdue.
    ->timestamp   = // The timestamp of when the attempt become overdue.
    ->userid      = // The user id that the attempt belongs to.
    ->submitterid = // The user id of the user who triggered this transition (may be null, e.g. on cron.).
    ->quizid      = // The quiz id of the quiz the attempt belongs to.
    ->cmid        = // The course_module id of the quiz the attempt belongs to.
    ->courseid    = // The course id of the course the quiz belongs to.

quiz_attempt_abandoned
    ->component   = 'mod_quiz';
    ->attemptid   = // The id of the quiz attempt that was submitted.
    ->timestamp   = // The timestamp of when the attempt was submitted.
    ->userid      = // The user id that the attempt belongs to.
    ->submitterid = // The user id of the user who triggered this transition (may be null, e.g. on cron.).
    ->quizid      = // The quiz id of the quiz the attempt belongs to.
    ->cmid        = // The course_module id of the quiz the attempt belongs to.
    ->courseid    = // The course id of the course the quiz belongs to.

*/
