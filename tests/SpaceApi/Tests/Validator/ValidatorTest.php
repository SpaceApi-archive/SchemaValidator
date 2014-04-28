<?php

namespace SpaceApi\Tests\Validator;

use SpaceApi\Validator\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    // sample test to see if phpunit and composer's autoloader work
    public function testCreateInstance() {

        $validator = new Validator();
        $this->assertEquals(false, is_null($validator));
    }
}
