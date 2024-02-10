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
 * Course Translator Upgrade
 *
 * Manage database migrations for filter_autotranslate
 *
 * @package    filter_autotranslate
 * @copyright  2024 Kaleb Heitzman <kaleb@jamfire.io>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see        https://docs.moodle.org/dev/Upgrade_API
 */

/**
 * Course Translator Upgrade
 *
 * @param integer $oldversion
 * @return boolean
 */
function xmldb_filter_autotranslate_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    // Create initial table.
    if ($oldversion < 2024011900) {
        // Define table filter_autotranslate to be created.
        $filterautotranslatetable = new xmldb_table('filter_autotranslate');

        // Define fields to be added to filter_autotranslate.
        $filterautotranslatetable->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $filterautotranslatetable->add_field('hash', XMLDB_TYPE_CHAR, '32', null, XMLDB_NOTNULL, null, null);
        $filterautotranslatetable->add_field('lang', XMLDB_TYPE_CHAR, '2', null, XMLDB_NOTNULL, null, null);
        $filterautotranslatetable->add_field('text', XMLDB_TYPE_TEXT, 'longtext', null, XMLDB_NOTNULL, null, null);
        $filterautotranslatetable->add_field('created_at', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null);
        $filterautotranslatetable->add_field('modified_at', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null);

        // Add keys to filter_autotranslate.
        $filterautotranslatetable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Add indexes to filter_autotranslate.
        $filterautotranslatetable->add_index('hash_index', XMLDB_INDEX_NOTUNIQUE, ['hash']);
        $filterautotranslatetable->add_index('lang_index', XMLDB_INDEX_NOTUNIQUE, ['lang']);

        // Conditionally launch create table for filter_autotranslate.
        if (!$dbman->table_exists($filterautotranslatetable)) {
            $dbman->create_table($filterautotranslatetable);
        }

        // Coursetranslator savepoint reached.
        upgrade_plugin_savepoint(true, 2024011900, 'filter', 'autotranslate');
    }

    // Create jobs and context id tables.
    if ($oldversion < 2024012900) {
        // Define table filter_autotranslate_jobs to be created.
        $filterautotranslatejobstable = new xmldb_table('filter_autotranslate_jobs');

        // Define fields to be added to filter_autotranslate.
        $filterautotranslatejobstable->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $filterautotranslatejobstable->add_field('hash', XMLDB_TYPE_CHAR, '32', null, XMLDB_NOTNULL, null, null);
        $filterautotranslatejobstable->add_field('lang', XMLDB_TYPE_CHAR, '2', null, XMLDB_NOTNULL, null, null);
        $filterautotranslatejobstable->add_field('fetched', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null);
        $filterautotranslatejobstable->add_field('source_missing', XMLDB_TYPE_INTEGER, '1', null, null, null, null);

        // Add keys to filter_autotranslate.
        $filterautotranslatejobstable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Add indexes to filter_autotranslate.
        $filterautotranslatejobstable->add_index('hash_index', XMLDB_INDEX_NOTUNIQUE, ['hash']);
        $filterautotranslatejobstable->add_index('lang_index', XMLDB_INDEX_NOTUNIQUE, ['lang']);
        $filterautotranslatejobstable->add_index('source_missing_index', XMLDB_INDEX_NOTUNIQUE, ['source_missing']);

        // Conditionally launch create table for filter_autotranslate.
        if (!$dbman->table_exists($filterautotranslatejobstable)) {
            $dbman->create_table($filterautotranslatejobstable);
        }

        // Define table filter_autotranslate_ctx to be created.
        $filterautotranslatectxtable = new xmldb_table('filter_autotranslate_ctx');

        // Define fields to be added to filter_autotranslate.
        $filterautotranslatectxtable->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $filterautotranslatectxtable->add_field('hash', XMLDB_TYPE_CHAR, '32', null, XMLDB_NOTNULL, null, null);
        $filterautotranslatectxtable->add_field('lang', XMLDB_TYPE_CHAR, '2', null, XMLDB_NOTNULL, null, null);
        $filterautotranslatectxtable->add_field('contextid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Add keys to filter_autotranslate.
        $filterautotranslatectxtable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Add indexes to filter_autotranslate.
        $filterautotranslatectxtable->add_index('hash_index', XMLDB_INDEX_NOTUNIQUE, ['hash']);
        $filterautotranslatectxtable->add_index('lang_index', XMLDB_INDEX_NOTUNIQUE, ['lang']);
        $filterautotranslatectxtable->add_index('contextid_index', XMLDB_INDEX_NOTUNIQUE, ['contextid']);

        // Conditionally launch create table for filter_autotranslate.
        if (!$dbman->table_exists($filterautotranslatectxtable)) {
            $dbman->create_table($filterautotranslatectxtable);
        }

        // Coursetranslator savepoint reached.
        upgrade_plugin_savepoint(true, 2024012900, 'filter', 'autotranslate');
    }

    // Add contextlevel and instanceid to the filter_autotranslate__ids table.
    if ($oldversion < 2024013000) {
        // Define the table to be modified.
        $table = new xmldb_table('filter_autotranslate_ctx');

        // Add new fields to the table.
        $field1 = new xmldb_field('contextlevel', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'hash');
        $field2 = new xmldb_field('instanceid', XMLDB_TYPE_CHAR, '32', null, XMLDB_NOTNULL, null, '', 'contextlevel');

        // Add the fields.
        $dbman->add_field($table, $field1);
        $dbman->add_field($table, $field2);

        // Add index on contextlevel and instanceid.
        $index1 = new xmldb_index('contextlevel_index', XMLDB_INDEX_NOTUNIQUE, ['contextlevel']);
        $index2 = new xmldb_index('instanceid_index', XMLDB_INDEX_NOTUNIQUE, ['instanceid']);

        // Add the indexes.
        $dbman->add_index($table, $index1);
        $dbman->add_index($table, $index2);

        // Set the default values for existing records if needed.
        // There is no way to know this but it won't be an issue because
        // the plugin is not in the wild yet....

        // Update the version number to the latest.
        upgrade_plugin_savepoint(true, 2024013000, 'filter', 'autotranslate');
    }

    // Add status field for tracking translations.
    if ($oldversion < 2024013101) {
        $sitelang = get_config('core', 'lang');

        // Define the table to be modified.
        $table = new xmldb_table('filter_autotranslate');

        // Add new fields to the table.
        $field1 = new xmldb_field('status', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'lang');

        // Add the fields.
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }

        // Add index on status and instanceid.
        $index1 = new xmldb_index('status_index', XMLDB_INDEX_NOTUNIQUE, ['status']);

        // Add the indexes.
        if (!$dbman->index_exists($table, $index1)) {
            $dbman->add_index($table, $index1);
        }

        // Set the default values for existing records if needed.
        $DB->execute("UPDATE {filter_autotranslate} SET status = ?", [0]);
        $DB->set_field("filter_autotranslate", 'status', 2, ["lang" => $sitelang]);

        // Update the version number to the latest.
        upgrade_plugin_savepoint(true, 2024013101, 'filter', 'autotranslate');
    }

    return true;
}
