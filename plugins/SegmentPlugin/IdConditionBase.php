<?php
/**
 * SegmentPlugin for phplist
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
 * @package   SegmentPlugin
 * @author    Duncan Cameron
 * @copyright 2014-2015 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * 
 * 
 * @category  phplist
 * @package   SegmentPlugin
 */

abstract class SegmentPlugin_IdConditionBase extends SegmentPlugin_Condition
{
    protected $column;

    public function operators()
    {
        return array(
            SegmentPlugin_Operator::REGEXP => s('REGEXP'),
            SegmentPlugin_Operator::NOTREGEXP => s('not REGEXP'),
        );
    }

    public function display($op, $value, $namePrefix)
    {
        return CHtml::textField(
            $namePrefix . '[value]',
            $value
        );
    }

    public function joinQuery($operator, $value)
    {
        if (!(is_string($value) && $value)) {
            throw new SegmentPlugin_ValueException;
        }

        $value = sql_escape($value);

        switch ($operator) {
            case SegmentPlugin_Operator::NOTREGEXP:
                $op = 'NOT REGEXP';
                break;
            case SegmentPlugin_Operator::REGEXP:
            default:
                $op = 'REGEXP';
        }

        $r = new stdClass;
        $r->join = '';
        $r->where = "u.$this->column $op '$value'";
        return $r;
    }
}
