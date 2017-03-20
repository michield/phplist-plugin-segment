<?php
/**
 * SegmentPlugin for phplist.
 *
 * This file is a part of SegmentPlugin.
 *
 * SegmentPlugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * CriteriaPlugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @category  phplist
 *
 * @author    Duncan Cameron
 * @copyright 2014-2016 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * @category  phplist
 */
class SegmentPlugin_AttributeConditionDate extends SegmentPlugin_DateConditionBase
{
    public function joinQuery($operator, $value)
    {
        $ua = 'ua' . $this->id;
        $r = new stdClass();
        $r->join = "LEFT JOIN {$this->tables['user_attribute']} $ua ON u.id = $ua.userid AND $ua.attributeid = {$this->field['id']} ";

        if ($operator == SegmentPlugin_Operator::AFTERINTERVAL) {
            $value1 = $this->validateInterval($value[0]);
            $r->where =
                "COALESCE($ua.value, '') != ''
                AND CURDATE() = DATE($ua.value) + INTERVAL $value1";
        } else {
            list($value1, $value2) = $this->validateDates($operator, $value);
            $value1 = sql_escape($value1);

            if ($operator == SegmentPlugin_Operator::BETWEEN) {
                $value2 = sql_escape($value2);
                $r->where = "(COALESCE($ua.value, '') != '' AND DATE(COALESCE($ua.value, '')) BETWEEN '$value1' AND '$value2')";
            } else {
                $op = $operator == SegmentPlugin_Operator::BEFORE ? '<'
                    : ($operator == SegmentPlugin_Operator::AFTER ? '>' : '=');
                $r->where = "(COALESCE($ua.value, '') != '' AND DATE(COALESCE($ua.value, '')) $op '$value1')";
            }
        }

        return $r;
    }
}
