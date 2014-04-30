<?php

namespace SpaceApi\Validator;


interface ResultInterface
{
    public function getErrors();
    public function getWarnings();
    public function getValid();
    public function getInvalid();
    public function getSpace();
    public function getDraft();
    public function searilize();
} 