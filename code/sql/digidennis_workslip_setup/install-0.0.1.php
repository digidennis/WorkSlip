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
        'nullable' => false
    ), 'Creation Date')
    ->addColumn('estimateddone_date', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
        'nullable' => false
    ), 'Estimated Done Date')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Name')
    ->addColumn('address', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
    ), 'Address')
    ->addColumn('zip', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
    ), 'Zip')
    ->addColumn('city', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
    ), 'City')
    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'E-mail')
    ->addColumn('phone', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'Phone')
    ->addColumn('whattodo', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
    ), 'What To Do')
    ->addColumn('mediafiles', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
    ), 'Media Files')
    ->addColumn('offer_price', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
    ), 'Offer Price')
    ->addForeignKey(
        $installer->getFkName('digidennis_workslip/workslip', 'order_id', 'sales/order', 'entity_id'),
        'order_id',
        $installer->getTable('sales/order'),
        'entity_id',
        ACTION_CASCADE,
        ACTION_NO_ACTION
    );

$installer->getConnection()->createTable($workslip);
$installer->endSetup();
