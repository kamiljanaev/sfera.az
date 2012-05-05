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
 * Below code reproduced with permission from Hector Virgen (http://www.virgentech.com)
 *
 */

class Zend_Db_Table_Mptt extends Core_Db_Table
{
    /**
     * Class constants
     *
     */
    const MPTT_LEFT = 'left';
    const MPTT_RIGHT = 'right';
    const MPTT_COLUMN = 'column';
    const MPTT_REFCOLUMN = 'refColumn';
    const MPTT_ORDER = 'order';
    const MPTT_DELETE_MAKE_NULL = 'makeNull';
    const MPTT_DELETE_RESTRICT = 'deleteRestrict';
    const MPTT_DELETE_CASCADE = 'deleteCascade';
    const MPTT_DELETE_REATTACH = 'deleteReattach';
    
    /**
     * Classname for row
     *
     * @var string
     */
    protected $_rowClass = 'Zend_Db_Table_Mptt_Row';
    
    /**
     * Traversal tree information.
     * 
     * Values:
     *   self::MPTT_LEFT         => column name for left value
     *   self::MPTT_RIGHT        => column name for right value
     *   self::MPTT_COLUMN       => column name for identifying row (primary key assumed)
     *   self::MPTT_REFCOLUMN    => column name for parent id (if not set, will look in reference map for own table match)
     *   self::MPTT_ORDER        => order by for rebuilding tree (e.g. "`name` ASC, `age` DESC")
     *
     * @var array $_traversal
     */
    protected $_traversal = array();
    
    /**
     * Automatically set to true once traversal info is set and verified.
     *
     * @var boolean $_isTraversable
     */
    protected $_isTraversalInitialized = false;
    protected $_traversalInitialized = false;
    
    /**
     * Sets traversal info and resets isTraversalIntialized flag.
     *
     * @param array $traversable
     * @return $this - Fluent interface
     */
    public function setTraversal(array $traversal)
    {
        $this->_traversal = $traversal;
        $this->_isTraversalInitialized = false;
        return $this;
    }
    
    /**
     * Returns traversal information.
     *
     * @return array
     */
    public function getTraversal()
    {
        if (!$this->_traversalInitialized) {
            $this->_initTraversal();
        }
        
        return $this->_traversal;
    }
    
    /**
     * Returns the table name and schema separated by a dot for use in sql queries
     *
     * @return string schema.name || name
     */
    public function getName()
    {
        return $this->_schema ? "{$this->_schema}.{$this->_name}" : $this->_name;
    }
    
    /**
     * Override delete method.
     * 
     * @param integer $id
     * @param const $mode
     * @param mixed $newParent
     */
    public function delete($id, $mode = self::MPTT_DELETE_RESTRICT, $newParent = null)
    {
        // TODO: Implement this with various modes
    }
    
    /**
     * Public function to rebuild tree traversal. The recursive function
     * _rebuildTreeTraversal() must be called without arguments.
     *
     * @return $this - Fluent interface
     */
    public function rebuildTreeTraversal()
    {
        $this->_rebuildTreeTraversal();
        return $this;
    }
    
    /**
     * Recursively rebuilds the modified preorder tree traversal
     * data based on a parent id column
     *
     * @param int $parentId
     * @param int $leftValue
     * @return int new right value
     */
    protected function _rebuildTreeTraversal($parentId = null, $leftValue = 0)
    {
		$traversal = $this->getTraversal();
        $select = $this->select();
        if ($parentId > 0) {
            $select->where("{$traversal[self::MPTT_REFCOLUMN]} = ?", $parentId);
        } else {
            $select->where("{$traversal[self::MPTT_REFCOLUMN]} IS NULL OR {$traversal[self::MPTT_REFCOLUMN]} = 0");
        }
        if (array_key_exists(self::MPTT_ORDER, $traversal)) {
            $select->order($traversal[self::MPTT_ORDER]);
        }
        $rightValue = $leftValue + 1;
        $rowset = $this->fetchAll($select);
        foreach ($rowset as $row) {
            $rightValue = $this->_rebuildTreeTraversal($row->{$traversal[self::MPTT_COLUMN]}, $rightValue);
        }
        if ($parentId > 0) {
            $node = $this->fetchRow($this->select()->where("{$traversal[self::MPTT_COLUMN]} = ?", $parentId));
            if (null !== $node) {
                $node->{$traversal[self::MPTT_LEFT]} = $leftValue;
                $node->{$traversal[self::MPTT_RIGHT]} = $rightValue;
                $node->save();
            }
        }
        return $rightValue + 1;
    }
    
    /**
     * Calculates left and right values for new row and inserts it.
     * Also adjusts all rows to make room for the new row.
     *
     * @param array $data
     * @return int $id
     */
    public function insert(array $data)
    {
        $traversal = $this->getTraversal();
        if (array_key_exists($traversal[self::MPTT_REFCOLUMN], $data)
        AND $data[$traversal[self::MPTT_REFCOLUMN]] > 0) {
            // Find parent row
            $parent_id = $data[$traversal[self::MPTT_REFCOLUMN]];
            $parent = $this->find($parent_id)->current();
            if (null === $parent) {
                require_once 'Zend/Db/Table/Mptt/Exception.php';
                throw new Zend_Db_Table_Mptt_Exception("Traversable error: "
                    . "Parent id {$parent_id} not found");
            }
            $left = (double) $parent->{$traversal[self::MPTT_LEFT]};
            $right = (double) $parent->{$traversal[self::MPTT_RIGHT]};
            // Make room for the new node
            parent::update(
                array(
                    $traversal[self::MPTT_LEFT] => new Zend_Db_Expr(
                        $this->getAdapter()->quoteInto("{$traversal[self::MPTT_LEFT]} + ?", 2)
                    ),
                ),
                array(
                    $this->getAdapter()->quoteInto("{$traversal[self::MPTT_LEFT]} > ?", $left)
                )
            );
            parent::update(
                array(
                    $traversal[self::MPTT_RIGHT] => new Zend_Db_Expr(
                        $this->getAdapter()->quoteInto("{$traversal[self::MPTT_RIGHT]} + ?", 2)
                    ),
                ),
                array(
                    $this->getAdapter()->quoteInto("{$traversal[self::MPTT_RIGHT]} > ?", $left)
                )
            );
            $data[$traversal[self::MPTT_LEFT]] = $left + 1;
            $data[$traversal[self::MPTT_RIGHT]] = $left + 2;
        } else {
            $maxRt = (double) $this->fetchRow($this->select()->from($this, array(
                'theMax' => "MAX(`{$traversal[self::MPTT_RIGHT]}`)"
            )))->theMax;
            $data[$traversal[self::MPTT_LEFT]] = $maxRt + 1;
            $data[$traversal[self::MPTT_RIGHT]] = $maxRt + 2;
        }
    
        // Do insert
        $id = parent::insert($data);
        
        return $id;
    }
    
    /**
     * Fetches all descendents of a given node
     *
     * @param Zend_Db_Table_Row_Abstract|string $row - Row object or value of row id
     * @param Zend_Db_Select $select - optional custom select object
     * @return Zend_Db_Table_Rowset|null
     */
    public function fetchAllDescendents($row, Zend_Db_Select $select = null)
    {
        $traversal = $this->getTraversal();
        
        if ($row instanceof Zend_Db_Table_Row_Abstract) {
            $_row = $row;
        } else if (is_string($row) or is_numeric($row)) {
            $_row = $this->fetchRow($this->select()->where($traversal[self::MPTT_COLUMN] . ' = ?', $row));
            if (null === $_row) {
                require_once 'Zend/Db/Table/Mptt/Exception.php';
                throw new Zend_Db_Table_Mptt_Exception("Cannot find row '{$traversal[self::MPTT_COLUMN]}' = {$row}");
            }
        } else {
            require_once 'Zend/Db/Table/Mptt/Exception.php';
            throw new Zend_Db_Table_Mptt_Exception("Expecting instance of Zend_Db_Table_Row_Abstract, a string, or numeric");
        }
        
        $left = $_row->{$traversal[self::MPTT_LEFT]};
        $right = $_row->{$traversal[self::MPTT_RIGHT]};
        if (null === $select) {
            $select = $this->select();
        }
        $select->where("{$traversal[self::MPTT_LEFT]} > ?", (double) $left)->where("{$traversal[self::MPTT_LEFT]} < ?", (double) $right);
        
        // Add "order" unless already set in select
        $orderPart = $select->getPart('order');
        if (empty($orderPart)) {
            $select->order($traversal[self::MPTT_LEFT]);
        }
        
        return $this->fetchAll($select);
    }
    
    /**
     * Fetches all descendents of a given node and returns them as a tree
     *
     * @param Zend_Db_Table_Row_Abstract|string|int $rows- Row object or value of row id or array of rows
     * @param Zend_Db_Select $select - optional select object
     * @return Zend_Db_Table_Rowset|null
     */
    public function fetchTree($row = null, Zend_Db_Select $select = null)
    {
        $traversal = $this->getTraversal();
        if (null === $select) {
            $select = $this->select();
        }
        
        // Add node, parent, and tree depth to select.
        $select->setIntegrityCheck(false)
            ->from(
                array(
                    'node'      => $this->getName()
                )
            )
            ->join(
                array(
                    'parent'    => $this->getName()),
                    null,
                    array(
                        'tree_depth'    => new Zend_Db_Expr(
                            "COUNT(parent.{$traversal[self::MPTT_REFCOLUMN]})"
                        )
                    )
                )
            ->group("node.{$traversal[self::MPTT_COLUMN]}");
            
        if (null !== $row) {
            if ($row instanceof Zend_Db_Table_Row_Abstract) {
                $_row = $row;
            } else if (is_string($row) or is_numeric($row)) {
                $_row = $this->fetchRow($this->select()->where($traversal[self::MPTT_COLUMN] . ' = ?', $row));
                if (null === $_row) {
                    require_once 'Zend/Db/Table/Mptt/Exception.php';
                    throw new Zend_Db_Table_Mptt_Exception("Cannot find row '{$traversal[self::MPTT_COLUMN]}' = {$row}");
                }
            } else {
                require_once 'Zend/Db/Table/Mptt/Exception.php';
                throw new Zend_Db_Table_Mptt_Exception("Expecting instance of Zend_Db_Table_Row_Abstract, a string, or numeric");
            }
            $left = (double) $_row->{$traversal[self::MPTT_LEFT]};
            $right = (double) $_row->{$traversal[self::MPTT_RIGHT]};
            if ($left > 0 and $right > 0) {
                $select->where("node.{$traversal[self::MPTT_LEFT]} >= {$left} AND node.{$traversal[self::MPTT_LEFT]} < {$right}");
            } else {
                // Traversal information is bad, throw an exception
                $id = $_row->{$traversal[self::MPTT_COLUMN]};
                require_once 'Zend/Db/Table/Mptt/Exception.php';
                throw new Zend_Db_Table_Mptt_Exception("Left/right values for row '{$traversal[self::MPTT_COLUMN]}' = '{$id}' in table '{$this->_name}' must be greater than zero to fetch tree.");
            }
        }
        
        $select->where("node.{$traversal[self::MPTT_LEFT]} BETWEEN parent.{$traversal[self::MPTT_LEFT]} AND parent.{$traversal[self::MPTT_RIGHT]}");
        $orderPart = $select->getPart('order');
        if (empty($orderPart)) {
            $select->order("node.{$traversal[self::MPTT_LEFT]}");
        }
//        echo $select;die;
        return $this->fetchAll($select);
    }
    
    /**
     * Fetches all ancestors of a given node
     *
     * @param Zend_Db_Table_Row_Abstract|string $row - Row object or value of row id
     * @param Zend_Db_Select $select - optional custom select object
     * @return Zend_Db_Table_Rowset|null
     */
    public function fetchAllAncestors($row, Zend_Db_Select $select = null)
    {
        $traversal = $this->getTraversal();
        if ($row instanceof Zend_Db_Table_Row_Abstract) {
            $_row = $row;
        } else if (is_string($row) or is_numeric($row)) {
            $_row = $this->fetchRow($this->select()->where("{$traversal[self::MPTT_COLUMN]} = ?", $row));
            if (null === $_row) {
                require_once 'Zend/Db/Table/Mptt/Exception.php';
                throw new Zend_Db_Table_Mptt_Exception("Cannot find row '{$traversal[self::MPTT_COLUMN]}' = {$row}");
            }
        } else {
            require_once 'Zend/Db/Table/Mptt/Exception.php';
            throw new Zend_Db_Table_Mptt_Exception("Expecting instance of Zend_Db_Table_Row_Abstract, a string, or numeric");
        }
        $left = $_row->{$traversal[self::MPTT_LEFT]};
        $right = $_row->{$traversal[self::MPTT_LEFT]};
        if (null === $select) {
            $select = $this->select();
        }
        $select->where("{$traversal[self::MPTT_LEFT]} < ?", (double) $left)->where("{$traversal[self::MPTT_RIGHT]} > ?", (double) $right);
        $orderPart = $select->getPart('order');
        if (empty($orderPart)) {
            $select->order($traversal[self::MPTT_LEFT]);
        }
        return $this->fetchAll($select);
    }
    
    /**
     * Prepares the traversal information
     *
     */
    protected function _initTraversal()
    {
        if (empty($this->_traversal)) {
            require_once 'Zend/Db/Table/Mptt/Exception.php';
            throw new Zend_Db_Table_Mptt_Exception('No traversal information specified.');
        }
            
        $columns = $this->_getCols();
        // Verify 'left' value and column
        if (!isset($this->_traversal[self::MPTT_LEFT])) {
            require_once 'Zend/Db/Table/Mptt/Exception.php';
            throw new Zend_Db_Table_Mptt_Exception(self::MPTT_LEFT . ' value must be specified for tree traversal.');
        }
        if (!in_array($this->_traversal[self::MPTT_LEFT], $columns)) {
            require_once 'Zend/Db/Table/Mptt/Exception.php';
            throw new Zend_Db_Table_Mptt_Exception("Column '" . $this->_traversal[self::MPTT_LEFT] . "' not found in table for tree traversal");
        }
        
        // Verify 'right' value and column
        if (!isset($this->_traversal[self::MPTT_RIGHT])) {
            require_once 'Zend/Db/Table/Mptt/Exception.php';
            throw new Zend_Db_Table_Mptt_Exception(self::MPTT_RIGHT . ' value must be specified for tree traversal.');
        }
        if (!in_array($this->_traversal[self::MPTT_RIGHT], $columns)) {
            require_once 'Zend/Db/Table/Mptt/Exception.php';
            throw new Zend_Db_Table_Mptt_Exception("Column '" . $this->_traversal[self::MPTT_RIGHT] . "' not found in table for tree traversal");
        }
        
        // Check for identifying column
        if (!isset($this->_traversal[self::MPTT_COLUMN])) {
            if (!isset($this->_primary)) {
                require_once 'Zend/Db/Table/Mptt/Exception.php';
                throw new Zend_Db_Table_Mptt_Exception("Unable to determine primary key for tree traversal");
            }
            if (count($this->_primary) > 1) {
                require_once 'Zend/Db/Table/Mptt/Exception.php';
                throw new Zend_Db_Table_Mptt_Exception("Cannot use compound primary key as identifying column for tree traversal, please specify the column manually");
            }
            $this->_traversal[self::MPTT_COLUMN] = current((array) $this->_primary);
        }
        
        // Check for reference column
        if (!isset($this->_traversal[self::MPTT_REFCOLUMN])) {
            if (!array_key_exists('Parent', $this->_referenceMap)) {
                require_once 'Zend/Db/Table/Mptt/Exception.php';
                throw new Zend_Db_Table_Mptt_Exception("Unable to determine reference column for traversal, and did not find reference rule 'Parent' in reference map");
            }
            $refColumn = $this->_referenceMap['Parent']['refColumns'];
            if (!is_string($refColumn) and count($refColumn) > 1) {
                require_once 'Zend/Db/Table/Mptt/Exception.php';
                throw new Zend_Db_Table_Mptt_Exception("Cannot use compound primary key as reference column for tree traversal, please specify the reference column manually");
            }
            $this->_traversal[self::MPTT_REFCOLUMN] = $refColumn;
        }
        
        $this->_traversableInitialized = true;
    }
}