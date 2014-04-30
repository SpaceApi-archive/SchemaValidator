<?php

namespace SpaceApi\Tests\Schema;

use SpaceApi\Schema\Schema;

/**
 * Class SchemaTest
 * @package SpaceApi\Tests\Schema
 */
class SchemaTest extends \PHPUnit_Framework_TestCase
{
    protected $specsRoot = '';

    public function testSpecsDirExists() {
        $this->assertFileExists($this->specsRoot);
    }

    public function testIsDraftVersion() {
        // @todo: mockup a draft file to be injected in $schema
        $schema = new Schema();
        $mockup_draft_version = 42;
        $isDraft = $schema->isDraftVersion($mockup_draft_version);
//        $this->assertEquals(true, $isDraft);
    }

    public function testGetDraftVersion() {
        // @todo: mockup a draft file to be injected in $schema
        $schema = new Schema();
        $mockup_draft_version = 42;
        $draft_version = $schema->getDraftVersion($mockup_draft_version);
//        $this->assertEquals(true, $draft_version);
    }

    /**
     * @depends testSpecsDirExists
     */
    public function testMultipleDraftsExistence() {
        $amount = 0;
        foreach (glob($this->specsRoot ."/*.json") as $filename) {
            if (strpos($filename, '-draft') !== false) {
                $amount++;
            }
        }
        $this->assertLessThanOrEqual(1, $amount);
    }

    /**
     * @depends testSpecsDirExists
     */
    public function testNonDraftSchemasAreValidJson() {

        $valid = true;

        foreach (glob($this->specsRoot ."/*.json") as $filename) {
            if (strpos($filename, '-draft') !== false) {
                continue;
            }
            if (is_null(json_decode(file_get_contents($filename)))) {
                $valid = false;
            }
        }

        $this->assertEquals(true, $valid);
    }

    /**
     * @depends testSpecsDirExists
     */
    public function testDraftIsValidOrEmpty() {
        $valid_or_empty = true;
        foreach (glob($this->specsRoot ."/*.json") as $filename) {
            if (strpos($filename, '-draft') !== false) {
                $json = file_get_contents($filename);
                $valid_or_empty = filesize($filename) === 0 ||
                    ! is_null(json_decode($json));
            }
        }

        $this->assertEquals(true, $valid_or_empty);
    }

    /**
     * @depends testSpecsDirExists
     */
    public function testSchemaVersionsLoaded() {
        $schema = new Schema();
        $versions = $schema->getVersions();

        // if the draft file is empty the schema class skips it
        if (filesize($this->specsRoot . '/14-draft.json')) {
            $amount = 6;
        } else {
            $amount = 5;
        }

        $this->assertEquals($amount, count($versions));
    }

    /**
     * @depends testSchemaVersionsLoaded
     */
    public function testStableVersionIs13() {
        $schema = new Schema();
        $this->assertEquals(13, $schema->getStableVersion());
    }

    /**
     * @depends testSpecsDirExists
     */
    public function testFileVersionMatchesApiField() {
        foreach (glob($this->specsRoot ."/*.json") as $filename) {

            // is the schema a draft and the file empty?
            // if so, skip it
            if (strpos($filename, '-draft') !== false &&
                filesize($filename) === 0) {
                continue;
            }

            $schema_file = basename($filename);

            // extract the version number from the file name
            $version = basename($schema_file);
            $version = str_replace('-draft', '', $version);
            $version = str_replace('.json', '', $version);
            $version = intval($version);

            $this->assertEquals(true, $this->isVersionInApiField(
                intval($version), $filename
            ), "Mismatch: $version not found in $schema_file");
        }
    }

    /**
     * @param int $version version number without the '0.' prefix
     * @param string $schema_file_path
     * @return boolean if version of api field matches the file name
     */
    private function isVersionInApiField($version, $schema_file_path) {
        $this->assertFileExists($schema_file_path);
        $schema_content = file_get_contents($schema_file_path);
        $schema_content = json_decode($schema_content);
        $version_strings = @$schema_content->properties->api->enum;
        $this->assertEquals(true, is_array($version_strings));
        return in_array("0.$version", $version_strings);
    }

    public function testGetStableSchemaAsJson() {
        $schema = new Schema();
        $schema_string = $schema->get($schema->getStableVersion());
        $this->assertEquals(true, is_string($schema_string));
    }

    public function testGetStableSchemaAsObject() {
        $schema = new Schema();
        $schema_object = $schema->get($schema->getStableVersion(), Schema::SCHEMA_OBJECT);
        $this->assertEquals(true, is_object($schema_object));
    }

    // @todo add more tests for Schema::get(), use version numbers, use
    //       the constants, use strings, use wrong numbers/strings

    public function setUp() {
        parent::setUp();
        $this->specsRoot = realpath(
            __DIR__ . '/../../../../data/specs/'
        );
    }
}
