<?php

class ilDclDefaultValueFactory
{
    /**
     * @return ilDclTableViewBaseDefaultValue
     */
    public function create($data_type_id) {
        $storage_location = ilDclCache::getDatatype($data_type_id)->getStorageLocation();
        switch ($storage_location) {
            case 1:
                return new ilDclTableViewTextDefaultValue();
                break;
            case 2:
                return new ilDclTableViewNumberDefaultValue();
                break;
            case 3:
                return new ilDclTableViewDateDefaultValue();
                break;
        }
    }


    /**
     * @param $data_type_id
     * @param $tview_id
     *
     * @return ilDclTableViewBaseDefaultValue
     * @throws ilDclException
     */
    public function find($data_type_id, $tview_id) {
        $storage_location = ilDclCache::getDatatype($data_type_id)->getStorageLocation();
        switch ($storage_location) {
            case 1:
                return ilDclTableViewTextDefaultValue::getCollection()->where(array("tview_set_id" => $tview_id))->first();
                break;
            case 2:
                return ilDclTableViewNumberDefaultValue::getCollection()->where(array("tview_set_id" => $tview_id))->first();
                break;
            case 3:
                return ilDclTableViewDateDefaultValue::getCollection()->where(array("tview_set_id" => $tview_id))->first();
                break;
            default:
                return null;
        }
    }

}