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
     * The plugin which has been called from
     * 
     * @var string
     */
    public $called_from;

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
     * Extracts the plugin slug from from which this check has been called
     * 
     * @var array $backtrace
     */
    public function get_plugin_slug( $backtrace )
    {
        $backtrace  = end( $backtrace );

        $file_path  = $backtrace['file'] ?? false;
        if ( !$file_path ) return 'undefined';

        $pos        = strpos( $file_path, 'plugins/' ) + strlen( 'plugins/' );
        $sub_path   = substr( $file_path, $pos );
        $next_slash = strpos( $sub_path, '/' );
        $name       = substr( $sub_path, 0, $next_slash );

        return $name;
    }

    /**
     * Check constructor
     */
    public function __construct( $value, $description = '', $importance = 'error', $callback = null )
    {
        $this->value        = $value;
        $this->importance   = $importance;
        $this->description  = $description;

        $construct_trace    = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 1 );
        $this->called_from  = $this->get_plugin_slug( $construct_trace );

        $this->passed       = $this->perform_check();

        if ( !$this->passed && is_callable( $callback ) ) {
            call_user_func( $callback );
        }
    }
}