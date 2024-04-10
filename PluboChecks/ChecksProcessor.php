<?php

namespace PluboChecks;

use PluboChecks\Check;

// Processor class
class ChecksProcessor
{
    // Processor Constants
    private static $PLUBO_CHECKS_SITE_HEALTH_TAB = 'plubo-checks-tab';

    /**
     * The plugin supervised by the checker.
     * 
     * @var string
     */
    private $plugin = '';

    /**
     * The checks performed by the checker.
     * 
     * @var Check[]
     */
    private $checks = [];

    /**
     * The general status of the plugin supervised by the checker.
     * 
     * @var string
     */
    private $status;

    /**
     * The checker instance.
     * 
     * @var ChecksProcessor|null
     */
    private static $instance = null;

    /**
     * Constructor.
     * 
     * @param string $plugin_name The name of the plugin supervised by the checker
     */
    public function __construct( string $plugin_name )
    {
        $this->plugin = $plugin_name;
        $this->init_hooks();
    }

    /**
     * Initialize hooks with WordPress
     */
    private function init_hooks()
    {
        add_action( 'admin_menu', [ $this, 'add_checks' ] );
        add_filter( 'site_health_navigation_tabs', [ $this, 'add_plugin_checks_tab' ] );
        add_action( 'site_health_tab_content', [ $this, 'prepare_tab_content' ] );
        add_action( 'plubo/display_check_interface', [ $this, 'display_check_interface' ] );
        add_action( 'plubo/display_plugin_checks', [ $this, 'display_plugin_checks' ] );
    }

    /**
     * Clone not allowed.
     */
    private function __clone() {}

    /**
     * Initialize processor with WordPress
     * 
     * @param string $plugin_name The name of the plugin supervised by the checker
     */
    public static function init( string $plugin_name )
    {
        if ( self::$instance === null ) {
            self::$instance = new self( $plugin_name );
        }

        // Custom action for checker initialization
        do_action( 'plubo/checker_init' );

        return self::$instance;
    }

    /**
     * Registers the different plugin checks to supervise
     */
    public function add_checks()
    {
        $checks         = apply_filters( "plubo/checks", [] );
        $this->checks   = is_array( $checks ) ? $checks[$this->plugin] ?? [] : [];
        
        $this->sync_check_status();
    }

    /**
     * Adds a plugin checks tab in the site health menu
     */
    public function add_plugin_checks_tab( array $tabs )
    {
        if ( !isset( $tabs[self::$PLUBO_CHECKS_SITE_HEALTH_TAB] ) ) {
            $tabs[self::$PLUBO_CHECKS_SITE_HEALTH_TAB] = 'Plugin Checks';
        }

        return $tabs;
    }

    /**
     * Prepares the contents in the plugin check the site health menu
     */
    public function prepare_tab_content( string $tab )
    {
        if ( $tab !== self::$PLUBO_CHECKS_SITE_HEALTH_TAB ) return;

        do_action( 'plubo/display_check_interface' );
    }

    /**
     * Displays the general interface for the plugin check site health menu
     */
    public function display_check_interface()
    {
        if ( did_action( 'plubo/display_check_interface' ) === 1 ):
            ?>
            <div class="health-check-body">
                <h2>Plugin Checks Information</h2>
                <p>This page shows the defined checks of each plugin</p>

                <?php do_action('plubo/display_plugin_checks'); ?>
            </div>
            <?php
        endif;
    }

    /**
     * After getting the plugin checks, it syncs the general status with the passed / failed checks
     */
    private function sync_check_status()
    {
        $this->status = Check::$CORRECT;

        foreach( $this->checks as $check ) {
            if ( !$check->passed ) {
                $this->status = Check::$WARNING;
                if ( $check->importance === Check::$ERROR ) {
                    $this->status = Check::$ERROR;
                    return;
                }
            }
        }
    }

    private function display_status_icon( string $status )
    {
        switch( $status ) {
            case Check::$WARNING:
                ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#f1ff33" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                <?php
                break;

            case Check::$ERROR:
                ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#ff3333" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <?php
                break;

            default:
                ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#33ff5c" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <?php
        }
    }

    /**
     * Displays the defined plugin checks
     */
    public function display_plugin_checks()
    {
        ?>
            <div id="health-check-debug" class="health-check-accordion">
                <h3 class="health-check-accordion-heading">
                    <button aria-expanded="false" class="health-check-accordion-trigger" aria-controls="health-check-accordion-block-wp-core" type="button">
                        <span class="icon"></span>
                        <span class="title" style="display: flex; align-items: center; gap: 0.5rem;"><?php $this->display_status_icon( $this->status ); ?> <?php echo $this->plugin ?></span>
                    </button>
                </h3>
                <div id="health-check-accordion-block-wp-core" class="health-check-accordion-panel" hidden="hidden">
                    <table class="widefat striped health-check-table" role="presentation">
                        <tbody>
                            <?php foreach( $this->checks as $check ): ?>
                                <tr>
                                    <td style="display: flex; align-items: center; gap: 0.5rem; min-width: fit-content;">
                                        <?php $this->display_status_icon( $check->passed ? Check::$CORRECT : $check->importance ); ?>
                                        <span style="font-weight: 800;"><?php echo $check->name; ?></span>
                                    </td>
                                    <?php if( $check->description ): ?>
                                        <td>
                                            <?php echo $check->description; ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
    }
}