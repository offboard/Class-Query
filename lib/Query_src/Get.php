<?php

//namespace to organize

namespace Query_src;

/**
 * Class Query Get Query
 * @author Zachbor       <zachborboa@gmail.com>
 * @author Bruno Ribeiro <bruno.espertinho@gmail.com>
 * 
 * @version 0.18
 * @access public
 * @package Get
 * @subpackage Insert
 */
class Get extends Insert {

    /**
     * returns select, insert or update query
     * 
     * @access public
     * @param boolean $use_limit standard false
     * @version 0.2.1
     * @return boolean
     */
    public function get($use_limit = false) {
        switch (true) {
            case self::_get_custom_sql():
                $execute = $this->customSQL;
                break;
            case self::_get_multiples_custom_sql():
                $execute = $this->customMultipleSQL;
                break;
            case self::_get_delete_query():
                $execute = $this->delete_query;
                break;
            case self::_get_insert_query():
                $execute = $this->insert_query;
                break;
            case self::_get_select_query($use_limit):
                $execute = $this->select_query;
                break;
            case self::_get_replace_query():
                $execute = $this->replace_query;
                break;
            case self::_get_update_query():
                $execute = $this->update_query;
                break;
            case self::_get_insert_multiple():
                $execute = $this->insert_multiple_query;
                break;
            default:
                $execute = FALSE;
                break;
        }
        return $execute;
    }

    /**
     * get select distinct
     * 
     * @return string
     */
    private function _get_custom_sql() {
        if (isset($this->customSQL)) {
            $this->query_type = 'customSQL';
            return $this->customSQL;
        }
        return NULL;
    }

    /**
     * get select distinct
     * 
     * @return string
     */
    private function _get_multiples_custom_sql() {
        if (isset($this->customMultipleSQL)) {
            $this->query_type = 'customMultipleSQL';
            return $this->customMultipleSQL;
        }
        return NULL;
    }

    /**
     * get select distinct
     * 
     * @return string
     */
    private function _get_distinct() {
        if (isset($this->distinct)) {
            if (is_array($this->distinct)) {
                $this->distinct = implode(',' . "\n\t", $this->distinct);
            }
            return 'SELECT DISTINCT' . "\n" . "\t(" . $this->distinct . ") \n" . '';
        }
        return NULL;
    }

    /**
     * get delete from
     * @return string
     */
    private function _get_delete_from() {
        return 'DELETE FROM' . "\n" . "\t" . $this->delete_from . "\n" . '';
    }

    /**
     * get delete query
     * 
     * @access protected
     * @return boolean
     */
    protected function _get_delete_query() {
        if (isset($this->delete_from)) {
            $this->query_type = 'delete';
            $this->delete_query = "\n" . self::_get_delete_from() . self::get_where() . self::_get_order_by() . self::_get_limit() . '';
            return true;
        }
        return false;
    }

    /**
     * Get name of a table or many tables
     * 
     * @access private
     * @return string
     */
    private function _get_from() {
        if (isset($this->from)) {
            if (is_array($this->from)) {
                $this->from = implode(',' . "\n\t", $this->from);
            }
            return 'FROM' . "\n" . "\t" . $this->from . "\n" . '';
        } else {
            return '';
        }
    }

    /**
     * get GROUP BY Determines how the records should be grouped.
     * @return string
     */
    private function _get_group_by() {
        if (isset($this->group_by)) {
            if (is_array($this->group_by)) {
                $this->group_by = implode(',' . "\n\t", $this->group_by);
            }
            return 'GROUP BY' . "\n" . "\t" . $this->group_by . "\n" . '';
        }
    }

    /**
     * get INNER JOIN to records.
     * Check the value on the type of data provided.
     * 
     * @return string
     * @version 2.0
     */
    private function _get_inner_join() {
        if (!isset($this->inner_join) || empty($this->inner_join)) {
            return '';
        } else {
            if (is_array($this->inner_join)) {
                $this->inner_join = implode("\n" . 'INNER JOIN' . "\n\t", $this->inner_join);
            }
            return 'INNER JOIN' . "\n" . "\t" . $this->inner_join . "\n" . '';
        }
    }

    /**
     * LEFT JOIN to records.
     * 
     * @return string
     * @version 0.1
     */
    private function _get_left_join() {
        if (!isset($this->left_join) || empty($this->left_join)) {
            return '';
        } else {
            if (is_array($this->left_join)) {
                $this->left_join = implode("\n" . 'LEFT JOIN' . "\n\t", $this->left_join);
            }
            return 'LEFT JOIN' . "\n" . "\t" . $this->left_join . "\n" . '';
        }
    }

    /**
     * get insert query
     * Check the value on the type of data provided.
     * 
     * @return boolean
     */
    private function _get_insert_query() {
        if (isset($this->insert_into)) {
            $this->query_type = 'insert_into';
            $this->insert_query = $this->insert_into;
            return true;
        } elseif (isset($this->insert_ignore_into)) {
            $this->query_type = 'insert_ignore_into';
            $this->insert_query = $this->insert_ignore_into;
            return true;
        }
        return false;
    }

    /**
     * get insert multiple
     * Check the value on the type of data provided.
     * 
     * @return boolean
     */
    private function _get_insert_multiple() {
        if (isset($this->insert_multiple)) {
            $this->query_type = 'insert_multiple';
            $this->insert_multiple_query = $this->insert_multiple;
            return true;
        }
        return false;
    }

    /**
     * Alias to load _get_inner_join() && _get_left_join()
     * @return Object
     */
    private function _get_join() {
        return self::_get_inner_join() . self::_get_left_join();
    }

    /**
     * get limit
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_limit() {
        if (!isset($this->limit)) {
            return '';
        } else {
            if (isset($this->offset)) {
                return 'LIMIT' . "\n" . "\t" . $this->offset . ', ' . $this->limit . "\n" . '';
            }
            return 'LIMIT' . "\n" . "\t" . $this->limit . "\n" . '';
        }
    }

    /**
     * ORDER BY to order the records.
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_order_by() {
        if (!isset($this->order_by) || empty($this->order_by)) {
            return '';
        } else {
            if (is_array($this->order_by)) {
                $this->order_by = implode(',' . "\n\t", $this->order_by);
            }
            return 'ORDER BY' . "\n" . "\t" . $this->order_by . "\n" . '';
        }
    }

    /**
     * get total records
     * 
     * @access protected
     * @return void
     */
    protected function _get_results() {
        $this->results = @mysqli_num_rows($this->result);
    }

    /**
     * get replace query
     * 
     * @return boolean
     */
    private function _get_replace_query() {
        if (isset($this->replace_into)) {
            $this->query_type = 'replace_into';
            $this->replace_query = $this->replace_into;
            return true;
        }
        return false;
    }

    /**
     * get select
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_select() {
        // checks distinct if exist ignore this function
        if (!empty(self::_get_distinct())) {
            return self::_get_distinct();
        }

        if (is_array($this->select)) {
            $selects = array();
            foreach ($this->select as $k => $v) {
                if (false !== strpos($k, '%s')) {
                    $selects[] = sprintf($k, $this->_check_link_mysqli($v));
                } else {
                    $selects[] = $v;
                }
            }
            return 'SELECT' . "\n" . "\t" . implode(',' . "\n\t", $selects) . "\n" . '';
        } elseif (empty($this->select)) {
            return 'SELECT' . "\n" . "\t" . '*' . "\n" . '';
        } else {
            return 'SELECT' . "\n" . "\t" . $this->select . "\n" . '';
        }
    }

    /**
     * Get select query
     * 
     * @param int $use_limit Limit, used null for disable
     * @version 0.2
     * @return boolean
     */
    protected function _get_select_query($use_limit = null) {
        if (isset($this->select)) {
            $this->query_type = 'select';
            $this->select_query = "\n" . self::_get_select() . self::_get_from() . self::_get_join() . self::get_where() . self::_get_group_by() . $this->having . self::_get_order_by() . ($use_limit || (!isset($this->page) && !isset($this->offset)) ? self::_get_limit() : '') . '';
            return true;
        }
        return false;
    }

    /**
     * get set
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_set() {
        $sets = array();
        $set_equals = array();
        foreach ($this->set as $k => $v) {
            if (is_null($v)) {
                $set_equals[] = $k . ' = NULL';
            } elseif (is_int($k)) {
                $set_equals[] = $v;
            } elseif (is_int($v)) {
                $set_equals[] = sprintf($k . ' = %s', $this->_check_link_mysqli($v));
            } else {
                $set_equals[] = sprintf($k . ' = "%s"', $this->_check_link_mysqli($v));
            }
        }

        $sets[] = implode(', ' . "\n\t", $set_equals);

        return 'SET' . "\n" . "\t" . implode(',' . "\n\t", $sets) . "\n" . '';
    }

    /**
     * Get update
     * 
     * @return string
     */
    private function _get_update() {
        return 'UPDATE' . "\n" . "\t" . $this->update . "\n" . '';
    }

    /**
     * Get update query
     * 
     * @version 0.2
     * @return boolean
     */
    private function _get_update_query() {
        if (isset($this->update)) {
            $this->query_type = 'update';
            $this->update_query = "\n" . self::_get_update() . self::_get_join() . self::_get_set() . self::get_where() . self::_get_limit() . '';
            return true;
        }
        return false;
    }

    /**
     * load all where's options
     * 
     * @version 2.2
     * @return string
     */
    private function get_where() {
        $wheres = array();
        $wheres_or = array();
        if (!empty(self::_get_where())) {
            $wheres[] = self::_get_where();
        }
        if (!empty(self::_get_where_not_exists())) {
            $wheres[] = self::_get_where_not_exists();
        }
        if (!empty(self::_get_where_exists())) {
            $wheres[] = self::_get_where_exists();
        }
        if (!empty(self::_get_where_greater_than())) {
            $wheres[] = self::_get_where_greater_than();
        }
        if (!empty(self::_get_where_between())) {
            $wheres[] = self::_get_where_between();
        }
        if (!empty(self::_get_where_in()))
            $wheres[] = self::_get_where_in();

        if (!empty(self::_get_where_in_or()))
            $wheres_or[] = self::_get_where_in_or();

        if (!empty(self::_get_where_greater_than_or_equal_to())) {
            $wheres[] = self::_get_where_greater_than_or_equal_to();
        }
        if (!empty(self::_get_where_less_than())) {
            $wheres[] = self::_get_where_less_than();
        }
        if (!empty(self::_get_where_less_than_or_equal_to())) {
            $wheres[] = self::_get_where_less_than_or_equal_to();
        }
        if (!empty(self::_get_where_equal_or())) {
            $wheres[] = self::_get_where_equal_or();
        }
        if (!empty(self::_get_where_equal_to_and_or())) {
            $wheres[] = self::_get_where_equal_to_and_or();
        }
        if (!empty(self::_get_where_equal_to())) {
            $wheres[] = self::_get_where_equal_to();
        }
        if (!empty(self::_get_where_not_equal_or())) {
            $wheres[] = self::_get_where_not_equal_or();
        }
        if (!empty(self::_get_where_not_in())) {
            $wheres[] = self::_get_where_not_in();
        }
        if (!empty(self::_get_where_not_equal_to())) {
            $wheres[] = self::_get_where_not_equal_to();
        }
        if (!empty(self::_get_where_like_after())) {
            $wheres[] = self::_get_where_like_after();
        }
        if (!empty(self::_get_where_like_before())) {
            $wheres[] = self::_get_where_like_before();
        }
        if (!empty(self::_get_where_like_both())) {
            $wheres[] = self::_get_where_like_both();
        }
        if (!empty(self::_get_where_like_or())) {
            $wheres[] = self::_get_where_like_or();
        }
        if (!empty(self::_get_where_not_like())) {
            $wheres[] = self::_get_where_not_like();
        }
        if (!empty(self::_get_where_like_binary())) {
            $wheres[] = self::_get_where_like_binary();
        }
        if (!empty(self::_get_where_between_columns())) {
            $wheres[] = self::_get_where_between_columns();
        }
        if (!empty(self::_get_where_between_columns_or())) {
            $wheres[] = self::_get_where_between_columns_or();
        }

        if (empty($wheres)) {
            return '';
        } else {
            $or = "\n";
            if (count($wheres_or) > 0) {
                $or = count($wheres_or) > 1 ? "\t" . implode('OR' . "\n\t", $wheres_or) . "\n" : "OR \n \t" . implode('OR' . "\n\t", $wheres_or);
            }
            return 'WHERE' . "\n" . "\t" . implode('AND' . "\n\t", $wheres) . $or . "\n";
        }
    }

    /**
     * between min AND max
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_between() {
        if (!isset($this->where_between) || !is_array($this->where_between) || empty($this->where_between)) {
            return '';
        } else {
            $where_between = array();
            foreach ($this->where_between as $k => $v) {
                $k = $this->replaceReservedWords($k);
                $v = $this->replaceReservedWords($v);
                $min = $this->_check_link_mysqli($v[0]);
                $max = $this->_check_link_mysqli($v[1]);
                if (is_array($v)) {
                    $where_between[] = $k . " BETWEEN '" . $min . "' AND '" . $max . "'";
                } else {
                    $where_between[] = $k . " BETWEEN '" . $v . "'";
                }
            }
            return implode(' AND' . "\n\t", $where_between) . ' ';
        }
    }

    /**
     * between min AND max
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_between_columns() {
        if (!isset($this->where_between_columns) || !is_array($this->where_between_columns) || empty($this->where_between_columns)) {
            return '';
        } else {
            $where_between = array();
            foreach ($this->where_between_columns as $k => $v) {
                $k = $this->replaceReservedWords($k);
                $v = $this->replaceReservedWords($v);
                $min = $this->_check_link_mysqli($v[0]);
                $max = $this->_check_link_mysqli($v[1]);
                if (is_array($v)) {
                    $where_between[] = "'{$k}' BETWEEN {$min} AND {$max}";
                } else {
                    $where_between[] = "'{$k}' BETWEEN {$v}";
                }
            }
            return implode(' AND' . "\n\t", $where_between) . ' ';
        }
    }

    /**
     * between min AND max
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_between_columns_or() {
        if (!isset($this->where_between_columns_or) || !is_array($this->where_between_columns_or) || empty($this->where_between_columns_or)) {
            return '';
        } else {
            $where_between_or = array();
            foreach ($this->where_between_columns_or as $k => $v) {
                $k = $this->replaceReservedWords($k);
                $v = $this->replaceReservedWords($v);
                $min = $this->_check_link_mysqli($v[0]);
                $max = $this->_check_link_mysqli($v[1]);
                if (is_array($v)) {
                    $where_between_or[] = "'{$k}' BETWEEN {$min} AND {$max}";
                } else {
                    $where_between_or[] = "'{$k}' BETWEEN {$v}";
                }
            }
            return '(' . "\n" . "\t\t" . implode(' OR' . "\n\t", $where_between_or) . "\n" . "\t" . ') ';
        }
    }

    /**
     * get where not exists
     * 
     * @return string
     */
    private function _get_where_not_exists() {
        if (!isset($this->where_not_exists) || empty($this->where_not_exists)) {
            return '';
        } else {
            if (is_array($this->where_not_exists)) {
                $where_not_exists = array();
                foreach ($this->where_not_exists as $v) {
                    $where_not_exists[] = $v;
                }
                $this->where_not_exists = implode("\n" . 'AND ' . "\n\t", $where_not_exists);
            } else {
                $this->where_not_exists = $this->where_not_exists;
            }
            return "\n" . " NOT EXISTS \t\t" . $this->where_not_exists . "\n" . "\t";
        }
    }

    /**
     * get where exists
     * 
     * @return string
     */
    private function _get_where_exists() {
        if (!isset($this->where_exists) || empty($this->where_exists)) {
            return '';
        } else {
            if (is_array($this->where_exists)) {
                $where_exists = array();
                foreach ($this->where_exists as $v) {
                    $where_exists[] = $v;
                }
                $this->where_exists = implode("\n" . 'AND ' . "\n\t", $where_exists);
            } else {
                $this->where_exists = $this->where_exists;
            }
            return "\n" . " EXISTS \t\t" . $this->where_exists . "\n" . "\t";
        }
    }

    /**
     * get where equal or
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_equal_or() {
        if (!isset($this->where_equal_or) || !is_array($this->where_equal_or) || empty($this->where_equal_or)) {
            return '';
        } else {
            $where_equal_or = array();
            foreach ($this->where_equal_or as $k => $v) {
                $k = $this->replaceReservedWords($k);
                $v = $this->replaceReservedWords($v);
                if (is_null($v)) {
                    $where_equal_or[] = $k . ' IS NULL';
                } elseif (is_int($k)) {
                    $where_equal_or[] = $v;
                } elseif (is_array($v)) {
                    foreach ($v as $key => $value) {
                        if (is_null($value)) {
                            $where_equal_or[] = $key . ' IS NULL';
                        } elseif (is_int($k)) {
                            $where_equal_or[] = $value;
                        } else {
                            $where_equal_or[] = sprintf($key . ' = "%s"', $this->_check_link_mysqli($value));
                        }
                    }
                } else {
                    $where_equal_or[] = sprintf($k . ' = "%s"', $this->_check_link_mysqli($v));
                }
            }
            return '(' . "\n" . "\t\t" . implode(' OR' . "\n\t\t", $where_equal_or) . "\n" . "\t" . ') ';
        }
    }

    /**
     * Custom criteria where
     * 
     * @version 0.2
     * @return string
     */
    private function _get_where() {
        if (!isset($this->where)) {
            return '';
        }
        if (!is_array($this->where)) {
            return $this->where;
        }
        return implode(' AND' . "\n\t", $this->where) . ' ';
    }

    /**
     * = Equal to
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_equal_to() {
        if (!isset($this->where_equal_to) || !is_array($this->where_equal_to) || empty($this->where_equal_to)) {
            return '';
        } else {
            $where_equal_to = array();
            foreach ($this->where_equal_to as $k => $v) {
                $k = $this->replaceReservedWords($k);
                if (is_null($v)) {
                    $where_equal_to[] = $k . ' IS NULL';
                } elseif (is_int($k)) {
                    $where_equal_to[] = $v;
                } elseif (is_int($v)) {
                    $where_equal_to[] = sprintf($k . ' = %s', $this->_check_link_mysqli($v));
                } elseif (is_array($v)) {
                    foreach ($v as $key => $value) {
                        $where_equal_to[] = sprintf($key . ' = "%s"', $this->_check_link_mysqli($value));
                    }
                } else {
                    $where_equal_to[] = sprintf($k . ' = "%s"', $v);
                }
            }
            return implode(' AND' . "\n\t", $where_equal_to) . ' ';
        }
    }

    /**
     * = Equal to
     * Check the value on the type of data provided.
     * 
     * Note: this function is used only in _get_where_equal_to_and_or()
     * @param array $dataType Array collection
     * @return string
     */
    private function _get_where_equal_to_or($dataType) {
        if (!isset($dataType) || !is_array($dataType) || empty($dataType)) {
            return '';
        } else {
            $where_equal_to = array();
            foreach ($dataType as $k => $v) {
                $k = $this->replaceReservedWords($k);
                $v = $this->replaceReservedWords($v);
                if (is_null($v)) {
                    $where_equal_to[] = $k . ' IS NULL';
                } elseif (is_int($k)) {
                    $where_equal_to[] = $v;
                } elseif (is_int($v)) {
                    $where_equal_to[] = sprintf($k . ' = %s', $this->_check_link_mysqli($v));
                } elseif (is_array($v)) {
                    foreach ($v as $key => $value) {
                        $where_equal_to[] = sprintf($key . ' = "%s"', $this->_check_link_mysqli($value));
                    }
                } else {
                    $where_equal_to[] = sprintf($k . ' = "%s"', $this->_check_link_mysqli($v));
                }
            }
            return implode(' AND' . "\n\t", $where_equal_to) . ' ';
        }
    }

    /**
     * collection data <b>= Equal to</b> and another collection <b>data = Equal to</b>
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_equal_to_and_or() {
        if (!isset($this->where_equal_to_and_or) || !is_array($this->where_equal_to_and_or) || empty($this->where_equal_to_and_or)) {
            return '';
        } else {
            return "(" . self::_get_where_equal_to_or($this->where_equal_to_and_or[0]) . " OR \n\t" . self::_get_where_equal_to_or($this->where_equal_to_and_or[1]) . ")";
        }
    }

    /**
     * > greater than
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_greater_than() {
        if (!isset($this->where_greater_than) || !is_array($this->where_greater_than) || empty($this->where_greater_than)) {
            return '';
        } else {
            $where_greater_than = array();
            foreach ($this->where_greater_than as $k => $v) {
                $k = $this->replaceReservedWords($k);
                $v = $this->replaceReservedWords($v);
                if (is_null($v)) {
                    $where_greater_than[] = $k . ' IS NULL';
                } elseif (is_int($k)) {
                    $where_greater_than[] = $v;
                } elseif (is_int($v)) {
                    $where_greater_than[] = sprintf($k . ' > %s', $this->_check_link_mysqli($v));
                } else {
                    $where_greater_than[] = sprintf($k . ' > "%s"', $this->_check_link_mysqli($v));
                }
            }
            return implode(' AND' . "\n\t", $where_greater_than) . ' ';
        }
    }

    /**
     * Select Query >= greater than or equal to
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_greater_than_or_equal_to() {
        if (!isset($this->where_greater_than_or_equal_to) || !is_array($this->where_greater_than_or_equal_to) || empty($this->where_greater_than_or_equal_to)) {
            return '';
        } else {
            $where_greater_than_or_equal_to = array();
            foreach ($this->where_greater_than_or_equal_to as $k => $v) {
                $k = $this->replaceReservedWords($k);
                $v = $this->replaceReservedWords($v);
                if (is_null($v)) {
                    $where_greater_than_or_equal_to[] = $k . ' IS NULL';
                } elseif (is_int($k)) {
                    $where_greater_than_or_equal_to[] = $v;
                } elseif (is_int($v)) {
                    $where_greater_than_or_equal_to[] = sprintf($k . ' >= %s', $this->_check_link_mysqli($v));
                } else {
                    $where_greater_than_or_equal_to[] = sprintf($k . ' >= "%s"', $this->_check_link_mysqli($v));
                }
            }
            return implode(' AND' . "\n\t", $where_greater_than_or_equal_to) . ' ';
        }
    }

    /**
     * IN Checks for values in a list
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_in_or() {
        if (!isset($this->where_in_or) || !is_array($this->where_in_or) || empty($this->where_in_or)) {
            return '';
        } else {
            $where_in_or = array();
            foreach ($this->where_in_or as $k => $v) {
                $k = $this->replaceReservedWords($k);
                $v = $this->replaceReservedWords($v);
                if (is_null($v)) {
                    $where_in_or[] = $k . ' IS NULL';
                } elseif (is_int($k)) {
                    $where_in_or[] = $v;
                } elseif (is_int($v)) {
                    $where_in_or[] = sprintf($k . ' IN(%s)', $this->_check_link_mysqli($v));
                } elseif (is_array($v)) {
                    $values = array();
                    foreach ($v as $value) {
                        $values[] = '"' . $this->_check_link_mysqli($value) . '"';
                    }
                    $where_in_or[] = sprintf($k . ' IN(%s)', implode(', ', $values));
                } else {
                    $where_in_or[] = sprintf($k . ' IN(%s)', $this->_check_link_mysqli($v));
                }
            }

            return implode(' OR' . "\n\t", $where_in_or) . ' ';
        }
    }

    /**
     * IN Checks for values in a list
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_in() {
        if (!isset($this->where_in) || !is_array($this->where_in) || empty($this->where_in)) {
            return '';
        } else {
            $where_in = array();
            foreach ($this->where_in as $k => $v) {
                $k = $this->replaceReservedWords($k);
                $v = $this->replaceReservedWords($v);
                if (is_null($v)) {
                    $where_in[] = $k . ' IS NULL';
                } elseif (is_int($k)) {
                    $where_in[] = $v;
                } elseif (is_int($v)) {
                    $where_in[] = sprintf($k . ' IN(%s)', $this->_check_link_mysqli($v));
                } elseif (is_array($v)) {

                    $values = array();

                    foreach ($v as $value) {
                        $values[] = '"' . $this->_check_link_mysqli($value) . '"';
                    }
                    $where_in[] = sprintf($k . ' IN(%s)', implode(', ', $values));
                } else {
                    $where_in[] = sprintf($k . ' IN(%s)', $this->_check_link_mysqli($v));
                }
            }

            return implode(' AND' . "\n\t", $where_in) . ' ';
        }
    }

    /**
     * < Less than
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_less_than() {
        if (!isset($this->where_less_than) || !is_array($this->where_less_than) || empty($this->where_less_than)) {
            return '';
        } else {
            $where_less_than = array();
            foreach ($this->where_less_than as $k => $v) {
                $k = $this->replaceReservedWords($k);
                $v = $this->replaceReservedWords($v);
                if (is_null($v)) {
                    $where_less_than[] = $k . ' IS NULL';
                } elseif (is_int($k)) {
                    $where_less_than[] = $v;
                } elseif (is_int($v)) {
                    $where_less_than[] = sprintf($k . ' < %s', $this->_check_link_mysqli($v));
                } else {
                    $where_less_than[] = sprintf($k . ' < "%s"', $this->_check_link_mysqli($v));
                }
            }
            return implode(' AND' . "\n\t", $where_less_than) . ' ';
        }
    }

    /**
     * <= Less than or equal to
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_less_than_or_equal_to() {
        if (!isset($this->where_less_than_or_equal_to) || !is_array($this->where_less_than_or_equal_to) || empty($this->where_less_than_or_equal_to)) {
            return '';
        } else {
            $where_less_than_or_equal_to = array();
            foreach ($this->where_less_than_or_equal_to as $k => $v) {
                $k = $this->replaceReservedWords($k);
                $v = $this->replaceReservedWords($v);
                if (is_null($v)) {
                    $where_less_than_or_equal_to[] = $k . ' IS NULL';
                } elseif (is_int($k)) {
                    $where_less_than_or_equal_to[] = $v;
                } elseif (is_int($v)) {
                    $where_less_than_or_equal_to[] = sprintf($k . ' <= %s', $this->_check_link_mysqli($v));
                } else {
                    $where_less_than_or_equal_to[] = sprintf($k . ' <= "%s"', $this->_check_link_mysqli($v));
                }
            }
            return implode(' AND' . "\n\t", $where_less_than_or_equal_to) . ' ';
        }
    }

    /**
     * get where like after
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_like_after() {
        if (!isset($this->where_like_after) || !is_array($this->where_like_after) || empty($this->where_like_after)) {
            return '';
        } else {
            $where_like_after = array();
            foreach ($this->where_like_after as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $key => $value) {
                        $where_like_after[] = sprintf($k . ' LIKE "%s%%"', $this->_check_link_mysqli($value));
                    }
                } else {
                    $where_like_after[] = sprintf($k . ' LIKE "%s%%"', $this->_check_link_mysqli($v));
                }
            }
            return implode(' AND' . "\n\t", $where_like_after) . ' ';
        }
    }

    /**
     * get where like before
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_like_before() {
        if (!isset($this->where_like_before) || !is_array($this->where_like_before) || empty($this->where_like_before)) {
            return '';
        } else {
            $where_like_before = array();
            foreach ($this->where_like_before as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $key => $value) {
                        $where_like_before[] = sprintf($k . ' LIKE "%%%s"', $this->_check_link_mysqli($value));
                    }
                } else {
                    $where_like_before[] = sprintf($k . ' LIKE "%%%s"', $this->_check_link_mysqli($v));
                }
            }
            return implode(' AND' . "\n\t", $where_like_before) . ' ';
        }
    }

    /**
     * get where like both
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_like_both() {
        if (!isset($this->where_like_both) || !is_array($this->where_like_both) || empty($this->where_like_both)) {
            return '';
        } else {
            $where_like_both = array();
            foreach ($this->where_like_both as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $key => $value) {
                        $where_like_both[] = sprintf($k . ' LIKE "%%%s%%"', $this->_check_link_mysqli($value));
                    }
                } else {
                    $where_like_both[] = sprintf($k . ' LIKE "%%%s%%"', $this->_check_link_mysqli($v));
                }
            }
            return implode(' AND' . "\n\t", $where_like_both) . ' ';
        }
    }

    /**
     * get where like binary
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_like_binary() {
        if (!isset($this->where_like_binary) || !is_array($this->where_like_binary) || empty($this->where_like_binary)) {
            return '';
        } else {
            $where_like_binary = array();
            foreach ($this->where_like_binary as $k => $v) {
                if (!is_null($v)) {
                    $where_like_binary[] = sprintf($k . ' LIKE BINARY "%s"', $this->_check_link_mysqli($v));
                }
            }
            return implode(' AND' . "\n\t", $where_like_binary) . ' ';
        }
    }

    /**
     * get where like or
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_like_or() {
        if (!isset($this->where_like_or) || !is_array($this->where_like_or) || empty($this->where_like_or)) {
            return '';
        } else {
            $where_like_or = array();
            foreach ($this->where_like_or as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $key => $value) {
                        $where_like_or[] = sprintf($k . ' LIKE "%%%s%%"', $this->_check_link_mysqli($value));
                    }
                } else {
                    $where_like_or[] = sprintf($k . ' LIKE "%%%s%%"', $this->_check_link_mysqli($v));
                }
            }
            return '(' . "\n" . "\t\t" . implode(' OR' . "\n\t\t", $where_like_or) . "\n" . "\t" . ') ';
        }
    }

    /**
     * <> Not equal to | != Not equal to
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_not_equal_or() {
        if (!isset($this->where_not_equal_or) || !is_array($this->where_not_equal_or) || empty($this->where_not_equal_or)) {
            return '';
        } else {
            $where_not_equal_or = array();
            foreach ($this->where_not_equal_or as $k => $v) {
                $k = $this->replaceReservedWords($k);
                $v = $this->replaceReservedWords($v);
                if (is_array($v)) {
                    foreach ($v as $key => $value) {
                        $where_not_equal_or[] = sprintf($k . ' <> "%%%s%%"', $this->_check_link_mysqli($value));
                    }
                } else {
                    $where_not_equal_or[] = sprintf($k . ' <> "%%%s%%"', $this->_check_link_mysqli($v));
                }
            }
            return '(' . "\n" . "\t\t" . implode(' OR' . "\n\t\t", $where_not_equal_or) . "\n" . "\t" . ') ';
        }
    }

    /**
     * <> Not equal to | != Not equal to
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_not_equal_to() {
        if (!isset($this->where_not_equal_to) || !is_array($this->where_not_equal_to) || empty($this->where_not_equal_to)) {
            return '';
        } else {
            $where_not_equal_to = array();
            foreach ($this->where_not_equal_to as $k => $v) {
                $k = $this->replaceReservedWords($k);
                $v = $this->replaceReservedWords($v);
                // check type the data received
                if (is_array($v)) {
                    foreach ($v as $key => $value) {
                        $where_not_equal_to[] = is_null($value) ? $key . ' IS NOT NULL' : sprintf($k . ' != "%s"', $this->_check_link_mysqli($value));
                    }
                } else {
                    $where_not_equal_to[] = is_null($v) ? $k . ' IS NOT NULL' : sprintf($k . ' != "%s"', $this->_check_link_mysqli($v));
                }
            }
            return '(' . "\n" . "\t\t" . implode(' AND' . "\n\t\t", $where_not_equal_to) . "\n" . "\t" . ') ';
        }
    }

    /**
     * NOT IN Ensures the value is not in the list
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_not_in() {
        if (!isset($this->where_not_in) || !is_array($this->where_not_in) || empty($this->where_not_in)) {
            return '';
        } else {
            $where_not_in = array();

            foreach ($this->where_not_in as $key => $values) {
                $key = $this->replaceReservedWords($key);
                $values = $this->replaceReservedWords($values);

                if (is_array($values)) {
                    $vs = array();

                    foreach ($values as $k => $v) {
                        if (is_null($v)) {
                            $vs[] = 'NULL';
                        } elseif (is_int($v)) {
                            $vs[] = $v;
                        } else {
                            $vs[] = sprintf('"%s"', $this->_check_link_mysqli($v));
                        }
                    }

                    $where_not_in[] = $key . ' NOT IN (' . "\n\t\t" . implode(', ' . "\n\t\t", $vs) . "\n\t" . ')';
                } else {
                    $where_not_in[] = $key . ' NOT IN (' . "\n\t\t" . $values . "\n\t" . ')';
                }
            }

            return implode(' AND' . "\n\t", $where_not_in) . ' ';
        }
    }

    /**
     * NOT LIKE Used to compare strings
     * Check the value on the type of data provided.
     * 
     * @return string
     */
    private function _get_where_not_like() {
        if (!isset($this->where_not_like) || !is_array($this->where_not_like) || empty($this->where_not_like)) {
            return '';
        } else {
            $where_not_like = array();
            foreach ($this->where_not_like as $k => $v) {
                $k = $this->replaceReservedWords($k);
                $v = $this->replaceReservedWords($v);
                if (is_array($v)) {
                    foreach ($v as $key => $value) {
                        $where_not_like[] = sprintf($k . ' NOT LIKE "%%%s%%"', $this->_check_link_mysqli($value));
                    }
                } else {
                    $where_not_like[] = sprintf($k . ' NOT LIKE "%%%s%%"', $this->_check_link_mysqli($v));
                }
            }
            return implode(' AND' . "\n\t", $where_not_like) . ' ';
        }
    }

    /**
     * Function Query get affected
     * 
     * @access public
     * @return Integer Returns number of affected rows by the last INSERT, UPDATE, REPLACE or DELETE 
     */
    public function get_affected() {
        return mysqli_affected_rows($this->link_mysqi[0]);
    }

}
