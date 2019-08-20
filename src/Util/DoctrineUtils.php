<?php

/**
 * $Id:$
 * $HeadURL:$
 * $Date:$
 * $Author:$
 * $Revision: $
 * 
 * FilterUtils.php
 * 
 * @since 01.07.2017
 * @author Alexej Kisselev <alexej.kisselev@gmail.com>
 */

namespace Aklib\Stdlib\Util;

use Doctrine\ORM\QueryBuilder;

abstract class DoctrineUtils {

    const NONE = 'NONE';
    const EQUALS = 'EQUALS';
    const GT = 'GT';
    const GTE = 'GTE';
    const LT = 'LT';
    const LTE = 'LTE';
    const RIGHT = 'RIGHT';
    const IN = 'IN';
    const LEFT = 'LEFT';
    const LIKE = 'LIKE';

    public static function addFilter(QueryBuilder $qb, $rawTerm, $field, $isAssociation) {

        $term = self::prepareTerm($rawTerm);
        if (empty($term)) {
            return;
        }

        $alias = self::getRootAlias($qb);

        if ($isAssociation) {
            $qb->andWhere($qb->expr()->eq("IDENTITY($alias$field)", ":$field"))->setParameter($field, $term);
            return;
        }
        $filterType = self::getFilterType($term);
        $expr = null;

        switch ($filterType) {
            case self::NONE:
                $expr = $qb->expr()->like("$alias$field", ":$field");
                $term = "%$term%";
                break;
            case self::EQUALS:
                $expr = $qb->expr()->eq("$alias$field", ":$field");
                break;
            case self::GT:
                $expr = $qb->expr()->gt("$alias$field", ":$field");
                break;
            case self::GTE:
                $expr = $qb->expr()->gte("$alias$field", ":$field");
                break;
            case self::LT:
                $expr = $qb->expr()->lt("$alias$field", ":$field");
                break;
            case self::LTE:
                $expr = $qb->expr()->lte("$alias$field", ":$field");
                break;
            case self::LIKE:
                $expr = $qb->expr()->like("$alias$field", ":$field");
                $term = "%$term%";
                break;
            case self::LEFT:
                $expr = $qb->expr()->like("$alias$field", ":$field");
                $term = "%$term";
                break;
            case self::RIGHT:
                $expr = $qb->expr()->like("$alias$field", ":$field");
                $term = "$term%";
                break;
            case self::IN:
                $expr = $qb->expr()->in("$alias$field", ":$field");
                $term = explode(',', $term);
                break;
            default:
                throw new \Exception("No filter type[$filterType] found");
        }
        $qb->andWhere($expr);
        if (is_numeric($term)) {
            //  handle double or float
            $term = intval($term);
        }

        $qb->setParameter($field, $term);
    }

    private static function getFilterType(& $term) {
        if (is_array($term) || preg_match('/[,]/', $term)) {
            return self::IN;
        }
        if (preg_match('/[*]/', $term)) {
            $param = explode('*', preg_replace('/[*]+/', '*', $term));
            $term = str_replace('*', '', $term);
            $function = self::LIKE;

            if (count($param) == 2) {
                $function = empty($param[0]) ? self::LEFT : self::RIGHT;
            }
            return $function;
        }
        if (preg_match('/^[\>]/', $term)) {
            $term = preg_replace('/^[\>]/', '', $term);
            $f = self::GT;
            if (preg_match('/^[=]/', $term)) {
                $term = preg_replace('/^[=]+/', '', $term);
                $f = self::GTE;
            }
            return $f;
        }
        if (preg_match('/^[\<]/', $term)) {
            $term = preg_replace('/^[\<]/', '', $term);
            $f = self::LT;
            if (preg_match('/^[=]/', $term)) {
                $term = preg_replace('/^[=]+/', '', $term);
                $f = self::LTE;
            }
            return $f;
        }
        if (preg_match('/^[=]/', $term)) {
            $term = preg_replace('/^[=]+/', '', $term);
            return self::EQUALS;
        }
        return self::NONE;
    }

    /**
     * 
     * @param type $rawTerm
     * @return string
     */
    private static function prepareTerm($rawTerm) {
        $term = $rawTerm;
        if (empty($term) && !is_numeric($term)) {
            return null;
        }
        if (is_array($rawTerm)) {
            $param = preg_grep('/^(\w+)$/', array_map('trim', $term));
            $term = implode(',', $param);
            if (empty($term)) {
                return null;
            }
        }
        return $term;
    }

    public static function addSort(QueryBuilder $qb, $rawOrder) {
        if (empty($rawOrder) || !is_string($rawOrder)) {
            return;
        }
        $order = explode(URL_VALUE_SEPARATOR, $rawOrder);
        $field = $order[0];
        $dir = empty($order[1]) ? 'ASC' : $order[1];

        $alias = self::getRootAlias($qb);
        $qb->orderBy("$alias$field", $dir);
    }

    public static function getRootAlias(QueryBuilder $qb, $dot = true) {
        $all = $qb->getRootAliases();
        if($dot){
            return !empty($all[0]) ? $all[0] . '.' : '';
        }
        return !empty($all[0]) ? $all[0] : '';
    }

}

//class FilterTypePeer {
//
//    /**
//     * 
//     * @param string $term
//     * @param string $type
//     * @param string $fieldName
//     * @return null|\Application\Soap\PIM\Type\Filter
//     */
//    public function createFilter($term, $type = 'string', $fieldName = '') {
//        if (empty($term) && $term != '0') {
//            return null;
//        }
//
//        if (is_array($term)) {
//            $param = preg_grep('/^(\w+)$/', array_map('trim', $term));
//            $term = implode(',', $param); 
//            if (empty($term)) {
//                return null;
//            }
//        }
//
//        $filter = new Filter();
//        $filter->setType($type);
//        $term = trim($term);
//        
//        if (preg_match('/^[=]/', $term)) {
//            //equals
//            $term = preg_replace('/^[=]+/', '', $term);
//            $filter->setType(\Application\Soap\PIM\Type\FilterType::equals);
//            $filter->setTerm($term);
//            $filter->setField($fieldName);
//            return $filter;
//        } elseif (preg_match('/[\s, ]/', $term)) {
//            $param = preg_split('/[\f\n\r\t\v\s,]/', $term, -1, PREG_SPLIT_NO_EMPTY);
//            $term = implode(',', $this->_prepare($param, $type));
//            $function = count($param) > 1 ? \Application\Soap\PIM\Type\FilterType::in : \Application\Soap\PIM\Type\FilterType::equals;
//            $filter->setType($function);
//            $filter->setTerm($term);
//            $filter->setField($fieldName);
//            return $filter;
//        } else
//        if (preg_match('/[*]/', $term)) {
//            $param = explode('*', preg_replace('/[*]+/', '*', $term));
//            $term = str_replace('*', '', $term);
//            $function = \Application\Soap\PIM\Type\FilterType::like;
//
//            if (count($param) == 2) {
//                $function = empty($param[0]) ? \Application\Soap\PIM\Type\FilterType::left : \Application\Soap\PIM\Type\FilterType::right;
//            }
//
//            $filter->setType($function);
//            $filter->setTerm($term);
//            $filter->setField($fieldName);
//            return $filter;
//        } 
//        elseif (preg_match('/[-]/', $term && $type == 'int')) {
//            $term = preg_replace('/[\s]+/', '', $term);
//            $param = explode('-', $term);
//
//            if (count($param) != 2) {
//                $term = preg_replace('/[-]+/', '', $term);
//                $filter->setType(\Application\Soap\PIM\Type\FilterType::equals);
//                $filter->setTerm($term);
//                $filter->setField($fieldName);
//                return $filter;
//            }
//
//            if (empty($param[0])) {
//                $filter->setType(\Application\Soap\PIM\Type\FilterType::lte);
//                $filter->setTerm($term);
//                $filter->setField($fieldName);
//                return $filter;
//            } elseif (empty($param[1])) {
//                $filter->setType(\Application\Soap\PIM\Type\FilterType::gte);
//                $filter->setTerm($term);
//                $filter->setField($fieldName);
//                return $filter;
//            }
//
//            $filter->setField($fieldName);
//            $filter2 = clone $filter;
//            $vartype = $type == 'string' ? 'int' : $type;
//
//            $filter->setType(\Application\Soap\PIM\Type\FilterType::gte);
//            $filter->setTerm(implode('', $this->_prepare($param[0])));
//            $filter->setField($vartype);
//
//            $filter2->setType(\Application\Soap\PIM\Type\FilterType::lte);
//            $filter2->setTerm(implode('', $this->_prepare($param[1])));
//            $filter2->setField($vartype);
//
//            return array(
//                $filter,
//                $filter2
//            );
//        }
//
//        switch ($type) {
//            case 'select':
//            case 'multiselect':
//            case 'checkbox':
//                $filter->setType(\Application\Soap\PIM\Type\FilterType::equals);
//                $filter->setTerm($term);
//                $filter->setField($fieldName);
//                return $filter;
//        }
//
//        $filter->setType(\Application\Soap\PIM\Type\FilterType::like);
//        $filter->setTerm($term);
//        $filter->setField($fieldName);
//        return $filter;
//    }
//
//    protected function _prepare($param, $type = 'string') {
//        $param = (array) $param;
//        $term = [];
//
//        switch ($type) {
//            case 'float':
//                $term = filter_var_array($param, FILTER_VALIDATE_FLOAT);
//                break;
//            case 'bool':
//            case 'int':
//            case 'select':
//            case 'multiselect':
//                $term = filter_var_array($param, FILTER_VALIDATE_INT);
//                break;
//            default:
//                $term = $param;
//                break;
//        }
//
//        return array_diff($term, array(null, ''));
//    }
//
//}
