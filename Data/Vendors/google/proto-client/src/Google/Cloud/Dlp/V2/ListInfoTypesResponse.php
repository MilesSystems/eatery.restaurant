<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/privacy/dlp/v2/dlp.proto

namespace Google\Cloud\Dlp\V2;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Response to the ListInfoTypes request.
 *
 * Generated from protobuf message <code>google.privacy.dlp.v2.ListInfoTypesResponse</code>
 */
class ListInfoTypesResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Set of sensitive infoTypes.
     *
     * Generated from protobuf field <code>repeated .google.privacy.dlp.v2.InfoTypeDescription info_types = 1;</code>
     */
    private $info_types;

    public function __construct() {
        \GPBMetadata\Google\Privacy\Dlp\V2\Dlp::initOnce();
        parent::__construct();
    }

    /**
     * Set of sensitive infoTypes.
     *
     * Generated from protobuf field <code>repeated .google.privacy.dlp.v2.InfoTypeDescription info_types = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getInfoTypes()
    {
        return $this->info_types;
    }

    /**
     * Set of sensitive infoTypes.
     *
     * Generated from protobuf field <code>repeated .google.privacy.dlp.v2.InfoTypeDescription info_types = 1;</code>
     * @param \Google\Cloud\Dlp\V2\InfoTypeDescription[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setInfoTypes($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Google\Cloud\Dlp\V2\InfoTypeDescription::class);
        $this->info_types = $arr;

        return $this;
    }

}

