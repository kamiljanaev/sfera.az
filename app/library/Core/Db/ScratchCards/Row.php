<?php
class Core_Db_ScratchCards_Row extends Core_Db_Table_Row
{
    public function checkUsed()
    {
        return $this->is_used;
    }

    public function useCard($profile = null)
    {
        if (!$this->checkUsed()) {
            try {
                if ($profile instanceof Core_Db_Profiles_Row) {
                    $currentProfile = $profile;
                } else {
                    $profileModel = new Core_Db_Profiles();
                    $currentProfile = $profileModel->getRowInfo($profile);
                }
                if ($currentProfile) {
                    $this->_getTable()->getAdapter()->beginTransaction();
                    if ($currentProfile->appendAmount($this->amount)) {
                        $this->is_used = 1;
                        $this->save();
                        $this->_getTable()->getAdapter()->commit();
                        return true;
                    } else {
                        $this->_getTable()->getAdapter()->rollBack();
                    }
                }
            } catch (Exception $e) {
                Core_Vdie::_($e->getMessage());
                $this->_getTable()->getAdapter()->rollBack();
            }
        }
        return false;
    }
}