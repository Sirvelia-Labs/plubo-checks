<?php

namespace PluboChecks;

// Check class
class Check
{
    // Check status constants
    public static $CORRECT          = 'correct';
    public static $WARNING          = 'warning';
    public static $ERROR            = 'error';

    // Check type constants
    public static $TYPE_ENV         = 'env';
    public static $TYPE_CONSTANT    = 'constant';
    public static $TYPE_FUNCTION    = 'function';
    public static $TYPE_CLASS       = 'class';

    /**
     * The name which references the check object being tested
     * 
     * @var string
     */
    public $name;

    /**
     * The type of the check
     * Can be TYPE_ENV, TYPE_CONSTANT, TYPE_FUNCTION or TYPE_CLASS
     * 
     * @var string
     */
    public $type;

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
        switch( $this->type )
        {
            case self::$TYPE_ENV:       return getenv( $this->name );
            case self::$TYPE_CONSTANT:  return constant( $this->name );
            case self::$TYPE_FUNCTION:  return is_callable( $this->name );
            case self::$TYPE_CLASS:     return class_exists( $this->name );
        }
    }

    /**
     * Check constructor
     */
    public function __construct( $name, $type, $importance = 'error', $description = '' )
    {
        $this->name         = $name;
        $this->type         = $type;
        $this->importance   = $importance;
        $this->description  = $description;

        $this->passed       = $this->perform_check();
    }
}