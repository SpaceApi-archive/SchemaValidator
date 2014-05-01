<?php

namespace SpaceApi\Validator;

use JsonSchema\Validator as JsonSchemaValidator;
use SpaceApi\Schema\Schema;

class Validator implements ValidatorInterface
{
    /**
     * @var \JsonSchema\Validator
     */
    protected $jsonValidator = null;

    /**
     * @var Result
     */
    protected $result = null;

    /**
     * @var Schema
     */
    protected $schema = null;

    function __construct() {
        $this->jsonValidator = new JsonSchemaValidator();
        $this->schema = new Schema();
    }

    /** @inheritdoc */
    public function validate($version, $json) {
        $this->clearResult();
        // @todo implement
        return $this->result;
    }

    /** @inheritdoc */
    public function validateStableVersion($json) {
        $this->clearResult();

        $schema = $this->schema->get(
            Schema::SCHEMA_STABLE,
            Schema::SCHEMA_OBJECT
        );

        if (is_string($json)) {
            $json = json_decode($json);
        }

        $this->jsonValidator->check($json, $schema);

        if ($this->jsonValidator->isValid()) {
            $this->result->addValidVersion(
                $this->schema->getStableVersion()
            );
        } else {
            $this->result->addInvalidVersion(
                $this->schema->getStableVersion()
            );
            $this->result->addErrors(
                $this->schema->getStableVersion(),
                $this->jsonValidator->getErrors()
            );
        }

        return $this->result;
    }

    /** @inheritdoc */
    public function validateAll($json) {
        $this->clearResult();
        // @todo implement
        return $this->result;
    }

    protected function clearResult() {
        $this->result = new Result;
        $this->result->setDraftVersion(
            $this->schema->getDraftVersion()
        );
    }
} 