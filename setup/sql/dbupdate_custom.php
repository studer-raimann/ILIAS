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
if (!$ilDB->tableColumnExists('il_dcl_tview_set', 'required')) {
    $ilDB->addTableColumn(
        'il_dcl_tview_set',
        'required',
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
if (!$ilDB->tableColumnExists('il_dcl_tview_set', 'locked')) {
    $ilDB->addTableColumn(
        'il_dcl_tview_set',
        'locked',
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
if ($ilDB->tableColumnExists('il_dcl_field', 'required')) {
    // Migration
    $res = $ilDB->query("SELECT id, required FROM il_dcl_field");
    while ($rec = $ilDB->fetchAssoc($res)) {
        $ilDB->queryF(
            "UPDATE il_dcl_tview_set SET required = %s WHERE field = %s",
            array('integer', 'text'),
            array($data['required'], $data['id'])
        );
    }

    $ilDB->dropTableColumn('il_dcl_field', 'required');
}
?>
<#7>
<?php
if ($ilDB->tableColumnExists('il_dcl_field', 'is_locked')) {
    // Migration
    $res = $ilDB->query("SELECT id, is_locked FROM il_dcl_field");
    while ($rec = $ilDB->fetchAssoc($res)) {
        $ilDB->queryF(
            "UPDATE il_dcl_tview_set SET locked = %s WHERE field = %s",
            array('integer', 'text'),
            array($data['is_locked'], $data['id'])
        );
    }

    $ilDB->dropTableColumn('il_dcl_field', 'is_locked');
}
?>
<#8>
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
<#9>
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
<#10>
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
<#11>
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
<#12>
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