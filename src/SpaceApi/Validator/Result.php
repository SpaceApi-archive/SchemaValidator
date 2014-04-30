<?php

namespace SpaceApi\Validator;


class Result implements ResultInterface
{
    protected $space;
    protected $draft;
    protected $errors;
    protected $warnings;
    protected $valid_versions;
    protected $invalid_versions;

    function __construct() {
        $this->clear();
    }

    public function clear() {
        $this->space = '';
        $this->draft = '';
        $this->errors = array();
        $this->warnings = array();
        $this->valid_versions = array();
        $this->invalid_versions = array();
    }

    public function addErrors($version, $errors) {
        foreach ($errors as $error) {
            $property = $error['property'];
            $message = $error['message'];
            $error = new \stdClass();
            $error->msg = "Property '$property' $message.";
            $error->description = '';
            $this->errors["0.$version"][] = $error;
        }
    }

    public function getErrors() {
        return $this->errors;
    }

    public function addWarnings($version, $warnings) {
        foreach ($warnings as $warning) {
            $property = $warning['property'];
            $message = $warning['message'];
            $warning = new \stdClass();
            $warning->msg = "Property '$property' $message.";
            $warning->description = '';
            $this->errors["0.$version"][] = $warning;
        }
    }

    public function getWarnings() {
        return $this->warnings;
    }

    public function getValid() {
        return $this->valid_versions;
    }

    public function getInvalid() {
        return $this->invalid_versions;
    }

    public function getSpace() {
        return $this->space;
    }

    public function getDraft() {
        return $this->draft;
    }

    public function addValidVersion($version) {
        $this->valid_versions[] = $version;
    }

    public function addInvalidVersion($version) {
        $this->invalid_versions[] = $version;
    }

    public function setDraftVersion($version) {
        if ($version > 0) {
            $this->draft = "0.$version";
        }
    }

    public function searilize() {
        $ser = new \stdClass();
        $ser->space = $this->space;
        $ser->draft = $this->draft;

        // @todo prefix the version numbers
        $ser->valid = $this->valid_versions;
        $ser->invalid = $this->invalid_versions;

        $ser->errors = $this->errors;
        $ser->warnings = $this->warnings;

        // @todo sort $ser->{valid,invalid,errors,warnings}
        return json_encode($ser, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
} 