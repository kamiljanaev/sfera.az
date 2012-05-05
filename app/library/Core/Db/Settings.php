<?php
class Core_Db_Settings extends Core_Db_Table
{
    protected
        $_name = 'site_settings',
        $_rowClass = 'Core_Db_Settings_Row',
        $_rowsetClass = 'Core_Db_Settings_Rowset';
}