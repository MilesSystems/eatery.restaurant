<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/privacy/dlp/v2/dlp.proto

namespace Google\Cloud\Dlp\V2;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Location of a finding within a row or record.
 *
 * Generated from protobuf message <code>google.privacy.dlp.v2.RecordLocation</code>
 */
class RecordLocation extends \Google\Protobuf\Internal\Message
{
    /**
     * Key of the finding.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.RecordKey record_key = 1;</code>
     */
    private $record_key = null;
    /**
     * Field id of the field containing the finding.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.FieldId field_id = 2;</code>
     */
    private $field_id = null;
    /**
     * Location within a `ContentItem.Table`.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.TableLocation table_location = 3;</code>
     */
    private $table_location = null;

    public function __construct() {
        \GPBMetadata\Google\Privacy\Dlp\V2\Dlp::initOnce();
        parent::__construct();
    }

    /**
     * Key of the finding.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.RecordKey record_key = 1;</code>
     * @return \Google\Cloud\Dlp\V2\RecordKey
     */
    public function getRecordKey()
    {
        return $this->record_key;
    }

    /**
     * Key of the finding.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.RecordKey record_key = 1;</code>
     * @param \Google\Cloud\Dlp\V2\RecordKey $var
     * @return $this
     */
    public function setRecordKey($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Dlp\V2\RecordKey::class);
        $this->record_key = $var;

        return $this;
    }

    /**
     * Field id of the field containing the finding.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.FieldId field_id = 2;</code>
     * @return \Google\Cloud\Dlp\V2\FieldId
     */
    public function getFieldId()
    {
        return $this->field_id;
    }

    /**
     * Field id of the field containing the finding.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.FieldId field_id = 2;</code>
     * @param \Google\Cloud\Dlp\V2\FieldId $var
     * @return $this
     */
    public function setFieldId($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Dlp\V2\FieldId::class);
        $this->field_id = $var;

        return $this;
    }

    /**
     * Location within a `ContentItem.Table`.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.TableLocation table_location = 3;</code>
     * @return \Google\Cloud\Dlp\V2\TableLocation
     */
    public function getTableLocation()
    {
        return $this->table_location;
    }

    /**
     * Location within a `ContentItem.Table`.
     *
     * Generated from protobuf field <code>.google.privacy.dlp.v2.TableLocation table_location = 3;</code>
     * @param \Google\Cloud\Dlp\V2\TableLocation $var
     * @return $this
     */
    public function setTableLocation($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Dlp\V2\TableLocation::class);
        $this->table_location = $var;

        return $this;
    }

}

