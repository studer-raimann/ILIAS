<#1>
<?php
// create object data entry
$id = $ilDB->nextId("object_data");
$ilDB->manipulateF(
    "INSERT INTO object_data (obj_id, type, title, description, owner, create_date, last_update) " .
    "VALUES (%s, %s, %s, %s, %s, %s, %s)",
    array("integer", "text", "text", "text", "integer", "timestamp", "timestamp"),
    array($id, "tala", "__TalkTemplateAdministration", "Talk Templates", -1, ilUtil::now(), ilUtil::now())
);

// create object reference entry
$ref_id = $ilDB->nextId('object_reference');
$res = $ilDB->manipulateF(
    "INSERT INTO object_reference (ref_id, obj_id) VALUES (%s, %s)",
    array("integer", "integer"),
    array($ref_id, $id)
);

// put in tree
$tree = new ilTree(ROOT_FOLDER_ID);
$tree->insertNode($ref_id, SYSTEM_FOLDER_ID);
?>
<#2>
<?php
try {
    if ($ilDB->supportsTransactions()) {
        $ilDB->beginTransaction();
    }
    $etalTableName = 'etal_data';

    $ilDB->createTable($etalTableName, [
        'object_id' => ['type' => 'integer', 'length' => 8, 'notnull' => true],
        'series_id' => ['type' => 'text', 'length' => 36, 'notnull' => true, 'fixed' => true],
        'start_date' => ['type' => 'integer', 'length' => 8, 'notnull' => true],
        'end_date' => ['type' => 'integer', 'length' => 8, 'notnull' => true],
        'all_day' => ['type' => 'integer', 'length' => 1, 'notnull' => true],
        'employee' => ['type' => 'integer', 'length' => 8, 'notnull' => true],
        'location' => ['type' => 'text', 'length' => 200, 'notnull' => false, 'fixed' => false],
        'completed' => ['type' => 'integer', 'length' => 1, 'notnull' => true]
    ]);

    $ilDB->addPrimaryKey($etalTableName, ['object_id']);
    $ilDB->addIndex($etalTableName, ['series_id'], 'ser');
    $ilDB->addIndex($etalTableName, ['employee'], 'emp');

    if ($ilDB->supportsTransactions()) {
        $ilDB->commit();
    }
} catch (\Exception $exception) {
    if ($ilDB->supportsTransactions()) {
        $ilDB->rollback();
    }
    throw $exception;
}
?>
<#3>
<?php
try {
    if ($ilDB->supportsTransactions()) {
        $ilDB->beginTransaction();
    }
    $etalTableName = 'etal_data';

    $ilDB->addTableColumn($etalTableName, 'standalone_date',
        [
            'type' => 'integer',
            'length' => 1,
            'notnull' => true,
            'default' => 0
        ]
    );

    if ($ilDB->supportsTransactions()) {
        $ilDB->commit();
    }
} catch (\Exception $exception) {
    if ($ilDB->supportsTransactions()) {
        $ilDB->rollback();
    }
    throw $exception;
}
?>
