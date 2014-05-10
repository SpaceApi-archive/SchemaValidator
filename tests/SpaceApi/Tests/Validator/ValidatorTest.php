<?php

namespace SpaceApi\Tests\Validator;

use SpaceApi\Schema\Schema;
use SpaceApi\Validator\Validator;

/**
 * Class ValidatorTest
 * @package SpaceApi\Tests\Validator
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $specsRoot = '';

    // sample test to see if phpunit and composer's autoloader work
    public function testCreateInstance() {
        $validator = new Validator();
        $this->assertNotNull($validator);
    }

    public function testValidateStableWithGoodJson() {
        $validator = new Validator();
        $schema = new Schema();
        $good_json = <<<JSON
{
    "api": "0.13",
    "space": "Slopspace",
    "logo": "http://your-space.org/img/logo.png",
    "url": "http://your-space.org",
    "location": {
        "address": "Ulmer Strasse 255, 70327 Stuttgart, Germany",
        "lon": 9.236,
        "lat": 48.777
    },
    "contact": {
        "twitter": "@spaceapi"
    },
    "issue_report_channels": [
        "twitter"
    ],
    "state": {
        "open": false
    }
}
JSON;

        $result = $validator->validateStableVersion($good_json);
        $this->assertEmpty($result->getErrors());
        $this->assertContains($schema->getStableVersion(), $result->getValid());
        $this->assertNotContains($schema->getStableVersion(), $result->getInvalid());
    }

    public function testValidateStableWithBadJson() {
        $validator = new Validator();

        $schema = new Schema();
        $stableVersionWithPrefix = '0.' . $schema->getStableVersion();

        $bad_json = "{}";

        $result = $validator->validateStableVersion($bad_json);

        $this->assertNotEmpty($result->getErrors());
        $this->assertArrayHasKey(
            $stableVersionWithPrefix,
            $result->getErrors()
        );
        $this->assertContains($schema->getStableVersion(), $result->getInvalid());
        $this->assertNotContains($schema->getStableVersion(), $result->getValid());
    }

    public function testValidateStableRoundTrip() {
        $json = <<<JSON
{
    "api": "0.13",
    "space": "Slopspace",
    "logo": "http://your-space.org/img/logo.png",
    "url": "http://your-space.org",
    "location": {
        "address": "Ulmer Strasse 255, 70327 Stuttgart, Germany",
        "lon": 9.236,
        "lat": 48.777
    },
    "contact": {
        "twitter": "@spaceapi"
    },
    "issue_report_channels": [
        "twitter"
    ],
    "state": {
        "open": false
    }
}
JSON;

        $validator = new Validator();
        $schema = new Schema();

        $result = $validator->validateStableVersion($json);
        $this->assertEmpty($result->getErrors());
        $this->assertContains($schema->getStableVersion(), $result->getValid());
        $this->assertNotContains($schema->getStableVersion(), $result->getInvalid());

        $object = json_decode($json);
        unset($object->space);
        $json = json_encode($object);

        $result = $validator->validateStableVersion($json);
        $this->assertNotEmpty($result->getErrors());
        $this->assertNotContains($schema->getStableVersion(), $result->getValid());
        $this->assertContains($schema->getStableVersion(), $result->getInvalid());
    }

    public function setUp() {
        parent::setUp();
        $this->specsRoot = realpath(
            __DIR__ . '/../../../../data/specs'
        );
    }
}
