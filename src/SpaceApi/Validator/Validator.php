<?php

namespace SpaceApi\Validator;

use JsonSchema\Validator as JsonSchemaValidator;
use SpaceApi\Schema\Schema;

class Validator {

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

    /**
     * @param int $version
     * @param object|string $json SpaceApi endpoint contents
     * @return ResultInterface
     */
    public function validate($version, $json) {
        $this->clearResult();
        // @todo implement
        return $this->result;
    }

    /**
     * @param object|string $endpoint_data SpaceApi endpoint contents
     * @return ResultInterface
     */
    public function validateStableVersion($endpoint_data) {
        $this->clearResult();

        $schema = $this->schema->get(
            Schema::SCHEMA_STABLE,
            Schema::SCHEMA_OBJECT
        );

        if (is_string($endpoint_data)) {
            $endpoint_data = json_decode($endpoint_data);
        }

        $this->jsonValidator->check($endpoint_data, $schema);

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

    /**
     * @param object|string $json SpaceApi endpoint contents
     * @return ResultInterface
     */
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