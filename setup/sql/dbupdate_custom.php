<#1>
<?php
if (!$ilDB->tableColumnExists('il_dcl_tview_set', 'default_value')) {
    $ilDB->addTableColumn(
        'il_dcl_tview_set',
        'default_value',
        array(
            'type'    => 'text',
            'length'  => 255,
            'notnull' => false
        )
    );
}
?>
<#2>
<?php
if (!$ilDB->tableColumnExists('il_dcl_tview_set', 'required_create')) {
    $ilDB->addTableColumn(
        'il_dcl_tview_set',
        'required_create',
        array(
            'type'    => 'integer',
            'length'  => 1,
            'notnull' => true,
            'default' => 0
        )
    );
}
?>
<#3>
<?php
if (!$ilDB->tableColumnExists('il_dcl_tview_set', 'locked_create')) {
    $ilDB->addTableColumn(
        'il_dcl_tview_set',
        'locked_create',
        array(
            'type'    => 'integer',
            'length'  => 1,
            'notnull' => true,
            'default' => 0
        )
    );
}
?>
<#4>
<?php
if (!$ilDB->tableColumnExists('il_dcl_tview_set', 'visible_create')) {
    $ilDB->addTableColumn(
        'il_dcl_tview_set',
        'visible_create',
        array(
            'type'    => 'integer',
            'length'  => 1,
            'notnull' => true,
            'default' => 1
        )
    );
}
?>
<#5>
<?php
if (!$ilDB->tableColumnExists('il_dcl_tview_set', 'visible_edit')) {
    $ilDB->addTableColumn(
        'il_dcl_tview_set',
        'visible_edit',
        array(
            'type'    => 'integer',
            'length'  => 1,
            'notnull' => true,
            'default' => 1
        )
    );
}
?>
<#6>
<?php
if (!$ilDB->tableColumnExists('il_dcl_tview_set', 'required_edit')) {
    $ilDB->addTableColumn(
        'il_dcl_tview_set',
        'required_edit',
        array(
            'type'    => 'integer',
            'length'  => 1,
            'notnull' => true,
            'default' => 0
        )
    );
}
?>
<#7>
<?php
if (!$ilDB->tableColumnExists('il_dcl_tview_set', 'locked_edit')) {
    $ilDB->addTableColumn(
        'il_dcl_tview_set',
        'locked_edit',
        array(
            'type'    => 'integer',
            'length'  => 1,
            'notnull' => true,
            'default' => 0
        )
    );
}
?>
<#8>
<?php
if ($ilDB->tableColumnExists('il_dcl_field', 'required')) {
    // Migration
    $res = $ilDB->query("SELECT id, required FROM il_dcl_field");
    while ($rec = $ilDB->fetchAssoc($res)) {
        $ilDB->queryF(
            "UPDATE il_dcl_tview_set SET required_create = %s WHERE field = %s",
            array('integer', 'text'),
            array($rec['required'], $rec['id'])
        );
    }

    $ilDB->dropTableColumn('il_dcl_field', 'required');
}
?>
<#9>
<?php
if ($ilDB->tableColumnExists('il_dcl_field', 'is_locked')) {
    // Migration
    $res = $ilDB->query("SELECT id, is_locked FROM il_dcl_field");
    while ($rec = $ilDB->fetchAssoc($res)) {
        $ilDB->queryF(
            "UPDATE il_dcl_tview_set SET required_locked = %s WHERE field = %s",
            array('integer', 'text'),
            array($rec['is_locked'], $rec['id'])
        );
    }

    $ilDB->dropTableColumn('il_dcl_field', 'is_locked');
}
?>
<#10>
<?php
if (!$ilDB->tableColumnExists('il_dcl_tableview', 'step_vs')) {
    $ilDB->addTableColumn(
        'il_dcl_tableview',
        'step_vs',
        array(
            'type'    => 'integer',
            'length'  => 1,
            'notnull' => true,
            'default' => 1
        )
    );
}
?>
<#11>
<?php
if (!$ilDB->tableColumnExists('il_dcl_tableview', 'step_c')) {
    $ilDB->addTableColumn(
        'il_dcl_tableview',
        'step_c',
        array(
            'type'    => 'integer',
            'length'  => 1,
            'notnull' => true,
            'default' => 0
        )
    );
}
?>
<#12>
<?php
if (!$ilDB->tableColumnExists('il_dcl_tableview', 'step_e')) {
    $ilDB->addTableColumn(
        'il_dcl_tableview',
        'step_e',
        array(
            'type'    => 'integer',
            'length'  => 1,
            'notnull' => true,
            'default' => 0
        )
    );
}
?>
<#13>
<?php
if (!$ilDB->tableColumnExists('il_dcl_tableview', 'step_o')) {
    $ilDB->addTableColumn(
        'il_dcl_tableview',
        'step_o',
        array(
            'type'    => 'integer',
            'length'  => 1,
            'notnull' => true,
            'default' => 0
        )
    );
}
?>
<#14>
<?php
if (!$ilDB->tableColumnExists('il_dcl_tableview', 'step_s')) {
    $ilDB->addTableColumn(
        'il_dcl_tableview',
        'step_s',
        array(
            'type'    => 'integer',
            'length'  => 1,
            'notnull' => true,
            'default' => 0
        )
    );
}
?>
<#15>
<?php
$fields = array(
    'id'       => array(
        'type'   => 'integer',
        'length' => '4',

    ),
    'tview_set_id' => array(
        'type'   => 'integer',
        'length' => '4',

    ),
    'value'      => array(
        'type'   => 'text',
        'length' => '4000',

    )
);

if (!$ilDB->tableExists('il_dcl_stloc1_default')) {
    $ilDB->createTable('il_dcl_stloc1_default', $fields);
    $ilDB->addPrimaryKey('il_dcl_stloc1_default', array('id'));
    $ilDB->createSequence("il_dcl_stloc1_default");
}
?>
<#16>
<?php
$fields = array(
    'id'       => array(
        'type'   => 'integer',
        'length' => '4',

    ),
    'tview_set_id' => array(
        'type'   => 'integer',
        'length' => '4',

    ),
    'value'      => array(
        'type'   => 'integer',
        'length' => '4',

    )
);

if (!$ilDB->tableExists('il_dcl_stloc2_default')) {
    $ilDB->createTable('il_dcl_stloc2_default', $fields);
    $ilDB->addPrimaryKey('il_dcl_stloc2_default', array('id'));
    $ilDB->createSequence("il_dcl_stloc2_default");
}
?>
<#17>
<?php
$fields = array(
    'id' => array(
        'type' => 'integer',
        'length' => 4,
        'notnull' => true
    ),
    'tview_set_id' => array(
        'type' => 'integer',
        'length' => 4,
        'notnull' => true
    ),
    'value' => array(
        'type' => 'timestamp',
        'notnull' => true
    ),
);

if (!$ilDB->tableExists('il_dcl_stloc3_default')) {
    $ilDB->createTable('il_dcl_stloc3_default', $fields);
    $ilDB->addPrimaryKey('il_dcl_stloc3_default', array('id'));
    $ilDB->createSequence("il_dcl_stloc3_default");
}
?>

