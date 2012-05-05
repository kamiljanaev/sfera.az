<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Db_Table_Mptt
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version
 *
 */

class Zend_Db_Table_Mptt_Row extends Core_Db_Table_Row
{
    /**
     * Fetches all ancestors of this row.
     *
     * @param Zend_Db_Select $select
     * @return Zend_Db_Table_Rowset|null
     */
    public function fetchAllAncestors(Zend_Db_Select $select = null)
    {
        if ($select instanceof Zend_Db_Select) {
            return $this->getTable()->fetchAllAncestors($this, $select);
        } else {
            return $this->getTable()->fetchAllAncestors($this);
        }
    }
    
    /**
     * Fetches all descendents of this row.
     *
     * @param Zend_Db_Select $select
     * @return Zend_Db_Table_Rowset|null
     */
    public function fetchAllDescendents(Zend_Db_Select $select = null)
    {
        if ($select instanceof Zend_Db_Select) {
            return $this->getTable()->fetchAllDescendents($this, $select);
        } else {
            return $this->getTable()->fetchAllDescendents($this);
        }
    }
    
    /**
     * Fetches all descendents of this row as a tree.
     *
     * @param Zend_Db_Select $select
     * @return Zend_Db_Table_Rowset|null
     */
    public function fetchTree(Zend_Db_Select $select = null)
    {
        if ($select instanceof Zend_Db_Select) {
            return $this->getTable()->fetchTree($this, $select);
        } else {
            return $this->getTable()->fetchTree($this);
        }
    }
    
    /**
     * Moves a node to another node when parent changes
     *
     */ 
    protected function _update() 
    {
        $traversal = $this->getTable()->getTraversal();
        $refColumn = $traversal[Zend_Db_Table_Mptt::MPTT_REFCOLUMN];
        if ($this->_cleanData[$refColumn] != $this->{$refColumn}) {
            $column = $traversal[Zend_Db_Table_Mptt::MPTT_COLUMN];
            $leftColumn = $traversal[Zend_Db_Table_Mptt::MPTT_LEFT];
            $rightColumn = $traversal[Zend_Db_Table_Mptt::MPTT_RIGHT];
            $db = $this->getTable()->getAdapter();
            $connection = $db->getConnection(); 
 
            $nodeSize = ($this->_cleanData[$rightColumn] - $this->_cleanData[$leftColumn]) + 1; 
            if ($this->{$refColumn}) {
                // node has been assigned to new parent 
                $parentRow  = $this->getTable()->fetchRow("{$column} = " . $this->{$refColumn});
                $maxRt      = $parentRow->{$rightColumn}; 
            } else { 
                // node has had parent removed add to end of root node 
                $maxRt = (double) $this->getTable()->fetchRow($this->getTable()->select(array('theMax' => "MAX({$db->quoteIdentifier($rightColumn)})")))->theMax + 1; 
            } 
 
            // lock tables 
            $connection->exec("LOCK TABLE `".$this->getTable()->info('name')."` WRITE;"); 
     
            //temporary remove moving node and children 
            $data = array( 
                    $leftColumn => new Zend_Db_Expr("0 - {$db->quoteIdentifier($leftColumn)}"), 
                    $rightColumn => new Zend_Db_Expr("0 - {$db->quoteIdentifier($rightColumn)}") 
                ); 
            $where = "{$db->quoteIdentifier($leftColumn)} >= {$this->_cleanData[$leftColumn]} AND {$db->quoteIdentifier($rightColumn)} <= {$this->_cleanData[$rightColumn]}"; 
            $this->getTable()->update($data,$where); 
 
                //step 2: decrease left and/or right position values of currently 'lower' items (and parents) 
            $data = array( 
                $leftColumn => new Zend_Db_Expr("{$db->quoteIdentifier($leftColumn)} - {$nodeSize}") 
            ); 
            $where = "{$db->quoteIdentifier($leftColumn)} > {$this->_cleanData[$rightColumn]}"; 
            $this->getTable()->update($data,$where); 
 
            $data = array( 
                $rightColumn => new Zend_Db_Expr("{$db->quoteIdentifier($rightColumn)} - {$nodeSize}") 
            ); 
            $where = "{$db->quoteIdentifier($rightColumn)} > {$this->_cleanData[$rightColumn]}"; 
            $this->getTable()->update($data,$where); 
 
            //step 3: increase left and/or right position values of future 'lower' items (and parents) 
            $data = array( 
                    $leftColumn => new Zend_Db_Expr("{$db->quoteIdentifier($leftColumn)} + {$nodeSize}")
                ); 
            $limit = ($maxRt > $this->_cleanData[$rightColumn]) ? $maxRt - $nodeSize : $maxRt ; 
            $where = "{$db->quoteIdentifier($leftColumn)} >= {$limit}"; 
            $this->getTable()->update($data,$where); 
 
            $data = array( 
                    $rightColumn => new Zend_Db_Expr("{$db->quoteIdentifier($rightColumn)} + {$nodeSize}") 
                ); 
            $limit = ($maxRt > $this->_cleanData[$rightColumn]) ? $maxRt - $nodeSize : $maxRt; 
            $where = "{$db->quoteIdentifier($rightColumn)} >= {$limit}"; 
            $this->getTable()->update($data, $where); 
 
            //step 4: move node (and it's subnodes) 
            $limit1 = ($maxRt > $this->_cleanData[$rightColumn]) ? $maxRt - $this->_cleanData[$rightColumn] - 1 : $maxRt - $this->_cleanData[$rightColumn] - 1 + $nodeSize; 
            $limit2 = ($maxRt > $this->_cleanData[$rightColumn]) ? $maxRt - $this->_cleanData[$rightColumn] - 1 : $maxRt - $this->_cleanData[$rightColumn] - 1 + $nodeSize; 
            $data = array( 
                $leftColumn => new Zend_Db_Expr("0 - {$db->quoteIdentifier($leftColumn)} + {$limit1}"), 
                $rightColumn => new Zend_Db_Expr("0 - {$db->quoteIdentifier($rightColumn)} + {$limit2}"), 
            ); 
            $where = "{$db->quoteIdentifier($leftColumn)} <= (0 - {$this->_cleanData[$leftColumn]}) AND {$db->quoteIdentifier($rightColumn)} >= (0 - {$this->_cleanData[$rightColumn]})"; 
            $this->getTable()->update($data,$where); 
 
            // unlock tables 
            $connection->exec("UNLOCK TABLES;"); 
        } else {
            parent::_update();
        }
    } 
}