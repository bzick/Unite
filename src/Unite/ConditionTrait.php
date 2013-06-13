<?php

namespace Unite;

/**
 * Tests condition switcher
 * @package Unite
 */
trait ConditionTrait {

    /**
     * Mark test as failed
     * @param string $reason
     * @throws FailureException
     */
    public function fail($reason) {
        throw new FailureException($reason);

    }

    /**
     * Mark test as success
     * @param string $message
     * @throws SuccessException
     */
    public function success($message = "") {
        throw new SuccessException($message);
    }

    /**
     * Skip test
     * @param string $reason
     * @throws SkipException
     */
    public function skip($reason) {
        throw new SkipException($reason);
    }
}