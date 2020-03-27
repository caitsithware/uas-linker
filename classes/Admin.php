<?php

namespace caitsithware\UASLinker {

    class Admin {

        const PAGE_SLUG = "uas_linker_settings";
        const OPTION_GROUP_NAME = 'uas_option_group';
        const SECTION_ID = 'uas_setting_section_id';
        const USER_SETTINGS = Plugin::USER_SETTINGS;
        const USER_AFFILIATE_ID = Plugin::USER_AFFILIATE_ID;

        private static $instance;
    
        private $initiated = false;

        public static function init() {
            if( isset(self::$instance) ) {
                return;
            }

            self::$instance = new Admin();
        }
    
        private function __construct() {
            add_action( 'init', array( $this, 'on_init' ) );
        }
    
        public function on_init() {
            if( $this->initiated ) {
                return;
            }

            $this->initiated = true;
    
            add_action( 'admin_init', array( $this, 'on_admin_init' ) );
            add_action( 'admin_menu', array( $this, 'on_admin_menu' ) );
        }
    
        public function on_admin_init() {
            register_setting( self::OPTION_GROUP_NAME, self::USER_SETTINGS );
    
            add_settings_section( self::SECTION_ID, '', '', self::PAGE_SLUG );
    
            self::add_settings_text_field( self::USER_AFFILIATE_ID, "Affiliate ID (aid)" );
        }
    
        private function add_settings_text_field( $id, $label ) {
            $field_option = array(
                'field_id' => $id,
            );
    
            add_settings_field( $field_option['field_id'], $label, array( $this, 'on_show_text_input_field'), self::PAGE_SLUG, self::SECTION_ID, $field_option );
        }
    
        public function on_show_text_input_field( $args ) {
            $field_id = $args['field_id'];
            $id = self::USER_SETTINGS.'['.$field_id.']';
    
            $uas_user_settings = Plugin::get_user_settings();
            $value = $uas_user_settings[$field_id];
            ?>
            <input type="text" id="<?php echo $field_id; ?>" name="<?php echo $id; ?>" value="<?php echo $value;?>" />
            <?php
        }
    
        public function on_admin_menu() {
            add_plugins_page("Unity Asset Store Linker Settings", "Unity Asset Store Linker", "manage_options", self::PAGE_SLUG, array( $this, "on_show_config" ) );
        }
    
        public function on_show_config() {
            if ( !current_user_can( 'manage_options' ) ) {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
                return;
            }

            global $parent_file;
            if ( $parent_file != 'options-general.php' ) {
                require(ABSPATH . 'wp-admin/options-head.php');
            }
    
            ?>
            <div class="wrap">
            <h2>Unity Asset Store Linker Settings</h2>
            <form method="post" action="options.php">
            
            <?php
                settings_fields( self::OPTION_GROUP_NAME );
                do_settings_sections( self::PAGE_SLUG );
                submit_button(); 
            ?>
    
            </form>
            </div>
            <?php
        }
    }
}