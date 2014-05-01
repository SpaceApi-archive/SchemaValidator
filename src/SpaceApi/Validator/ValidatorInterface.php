<?php

namespace SpaceApi\Validator;

interface ValidatorInterface
{
    /**
     * @param int $version
     * @param object|string $json SpaceApi endpoint contents
     * @return ResultInterface
     */
    public function validate($version, $json);

    /**
     * @param object|string $json SpaceApi endpoint contents
     * @return ResultInterface
     */
    public function validateStableVersion($json);

    /**
     * @param object|string $json SpaceApi endpoint contents
     * @return ResultInterface
     */
    public function validateAll($json);
} 