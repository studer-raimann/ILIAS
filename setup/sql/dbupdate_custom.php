<#1>
<?php
require_once './Services/Migration/DBUpdate_3560/classes/class.ilDBUpdateNewObjectType.php';
ilDBUpdateNewObjectType::addAdminNode('adn', 'Administrative Notifications');

$ilCtrlStructureReader->getStructure();
// END MME
?>
<#2>
<?php
$fields = array(
    'id' => array(
        'type' => 'integer',
        'length' => '8',

    ),
    'title' => array(
        'type' => 'text',
        'length' => '256',

    ),
    'body' => array(
        'type' => 'clob',

    ),
    'event_start' => array(
        'type' => 'timestamp',

    ),
    'event_end' => array(
        'type' => 'timestamp',

    ),
    'display_start' => array(
        'type' => 'timestamp',

    ),
    'display_end' => array(
        'type' => 'timestamp',

    ),
    'type' => array(
        'type' => 'integer',
        'length' => '1',

    ),
    'type_during_event' => array(
        'type' => 'integer',
        'length' => '1',

    ),
    'dismissable' => array(
        'type' => 'integer',
        'length' => '1',

    ),
    'permanent' => array(
        'type' => 'integer',
        'length' => '1',

    ),
    'allowed_users' => array(
        'type' => 'text',
        'length' => '256',

    ),
    'parent_id' => array(
        'type' => 'integer',
        'length' => '8',

    ),
    'create_date' => array(
        'type' => 'timestamp',

    ),
    'last_update' => array(
        'type' => 'timestamp',

    ),
    'created_by' => array(
        'type' => 'integer',
        'length' => '8',

    ),
    'last_update_by' => array(
        'type' => 'integer',
        'length' => '8',

    ),
    'active' => array(
        'type' => 'integer',
        'length' => '1',

    ),
    'limited_to_role_ids' => array(
        'type' => 'text',
        'length' => '256',

    ),
    'limit_to_roles' => array(
        'type' => 'integer',
        'length' => '1',

    ),
    'interruptive' => array(
        'type' => 'integer',
        'length' => '1',

    ),
    'link' => array(
        'type' => 'text',
        'length' => '256',

    ),
    'link_type' => array(
        'type' => 'integer',
        'length' => '1',

    ),
    'link_target' => array(
        'type' => 'text',
        'length' => '256',

    ),

);
if (! $ilDB->tableExists('il_adn_notifications')) {
    $ilDB->createTable('il_adn_notifications', $fields);
    $ilDB->addPrimaryKey('il_adn_notifications', array( 'id' ));

    if (! $ilDB->sequenceExists('il_adn_notifications')) {
        $ilDB->createSequence('il_adn_notifications');
    }

}
?>
<#3>
<?php
$fields = array(
    'id' => array(
        'type' => 'integer',
        'length' => '8',

    ),
    'usr_id' => array(
        'type' => 'integer',
        'length' => '8',

    ),
    'notification_id' => array(
        'type' => 'integer',
        'length' => '8',

    ),

);
if (! $ilDB->tableExists('il_adn_dismiss')) {
    $ilDB->createTable('il_adn_dismiss', $fields);
    $ilDB->addPrimaryKey('il_adn_dismiss', array( 'id' ));

    if (! $ilDB->sequenceExists('il_adn_dismiss')) {
        $ilDB->createSequence('il_adn_dismiss');
    }
}

