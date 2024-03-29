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

namespace filter_autotranslate\output;

use plugin_renderer_base;

/**
 * Filter Autotranslate Renderer
 *
 * @package    filter_autotranslate
 * @copyright  2024 Kaleb Heitzman <kaleb@jamfire.io>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @see        https://docs.moodle.org/dev/Output_API
 */
class renderer extends plugin_renderer_base {
    /**
     * Render Manage Page
     *
     * @param object $page
     * @return string
     */
    public function render_manage_page($page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('filter_autotranslate/manage_page', $data);
    }

    /**
     * Render Glossary Page
     *
     * @param object $page
     * @return string
     */
    public function render_glossary_page($page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('filter_autotranslate/glossary_page', $data);
    }
}
