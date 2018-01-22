<?php

$installer = $this;
$installer->startSetup();

$workslip = $installer->getConnection()->newTable( $installer->getTable('digidennis_workslip/workslip'))
    ->addColumn('workslip_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        'identity' => true), 'Workslip Id')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => true), 'Order ID')
    ->addColumn('state', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => 0
    ), 'Order ID')
    ->addColumn('create_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
    ), 'Creation Date')
    ->addColumn('estimateddone_date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
    ), 'Estimated Done Date')
    ->addColumn('firstname', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Firstname')
    ->addColumn('lastname', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Lastname')
    ->addColumn('address', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Address')
    ->addColumn('zip', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Zip')
    ->addColumn('city', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'City')
    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'E-mail')
    ->addColumn('phone', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Phone')
    ->addColumn('whattodo', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'What To Do')
    ->addColumn('mediafiles', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
    ), 'Media Files')
    ->addForeignKey(
        $installer->getFkName('digidennis_dimensionit/workslip', 'order_id', 'sales/order', 'entity_id'),
        'order_id',
        $installer->getTable('sales/order'),
        'entity_id',
        ACTION_CASCADE,
        ACTION_NO_ACTION
    );
$installer->getConnection()->createTable($workslip);
$installer->endSetup();
