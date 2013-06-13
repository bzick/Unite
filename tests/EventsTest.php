<?php

namespace Unite;

class EventsTest extends TestCase {

    public static function providerValues() {
        return array(
            array(1,2,3),
            array(1,2,4),
            array(1,2,5),
        );
    }

    /**
     * @dataProvider providerValues
     * @param int $a
     * @param int $b
     * @param int $c
     */
    public function testProvider() {

    }
}