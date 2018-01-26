<?php

$installer = $this;
$installer->startSetup();

$material = $installer->getConnection()->newTable( $installer->getTable('digidennis_workslip/material'))
    ->addColumn('material_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        'identity' => true), 'Material Id')
    ->addColumn('workslip_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => true), 'WorkSlip ID')
    ->addColumn('state', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => 0
    ), 'State')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Description')
    ->addColumn('price', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Price')
    ->addForeignKey(
        $installer->getFkName('digidennis_workslip/material', 'workslip_id', 'digidennis_workslip/workslip', 'workslip_id'),
        'workslip_id',
        $installer->getTable('digidennis_workslip/workslip'),
        'workslip_id',
        ACTION_CASCADE,
        ACTION_NO_ACTION
    );

$installer->getConnection()->createTable($material);
$installer->endSetup();
