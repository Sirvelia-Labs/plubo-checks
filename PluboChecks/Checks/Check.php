<?php

namespace PluboChecks\Checks;

// Check class
class Check
{
    // Check status constants
    public static $CORRECT          = 'correct';
    public static $WARNING          = 'warning';
    public static $ERROR            = 'error';

    /**
     * The value which references the check object being tested
     * 
     * @var string
     */
    public $value;

    /**
     * The importance of the check
     * Can be WARNING or ERROR
     * 
     * @var string
     */
    public $importance;

    /**
     * A description given for the check
     * 
     * @var string
     */
    public $description;
    
    /**
     * Shows if the check has passed or not
     * 
     * @var boolean
     */
    public $passed;

    /**
     * Performs a test to see if the check has passed
     */
    public function perform_check()
    {
        return true;
    }

    /**
     * Check constructor
     */
    public function __construct( $value, $description = '', $importance = 'error', $callback = null )
    {
        $this->value         = $value;
        $this->importance   = $importance;
        $this->description  = $description;

        $this->passed       = $this->perform_check();

        if ( !$this->passed && is_callable( $callback ) ) {
            call_user_func( $callback );
        }
    }
}