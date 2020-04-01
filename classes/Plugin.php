<?php

namespace caitsithware\UASLinker {

    class Plugin {

        const USER_SETTINGS = 'uas_user_settings';
        const USER_AFFILIATE_ID = 'affiliate_id';

        private static $instance;

        private $initiated = false;
        private $plguin_file_path;

        public static function get_user_settings() {
            return get_site_option( self::USER_SETTINGS );
        }

        public static function update_user_settings($uas_user_settings) {
            update_option( self::USER_SETTINGS, $uas_user_settings );
        }

        public static function init( $plugin_file_path ){
            if( isset(self::$instance) ) {
                return;
            }

            self::$instance = new Plugin($plugin_file_path);
        }

        private function __construct($plugin_file_path) {
            $this->plguin_file_path = $plugin_file_path;
            register_activation_hook( $plugin_file_path, array( $this, 'on_activation' ) );
            register_uninstall_hook( $plugin_file_path, array( __CLASS__, 'on_uninstall' ) );
            
            add_action( 'init', array( $this, 'on_init' ) );
        }

        public function on_activation() {
            $uas_user_settings = self::get_user_settings();
            if( ! $uas_user_settings ) {
                $uas_user_settings = array(
                    self::USER_AFFILIATE_ID => ''
                );

                self::update_user_settings($uas_user_settings);
            }
        }

        public static function on_uninstall() {
            delete_option( self::USER_SETTINGS );
        }

        public function on_init() {
            if( $this->initiated ) {
                return;
            }

            $this->initiated = true;

            add_action( 'wp_enqueue_scripts', array( $this, 'on_enqueue_scripts' ) );

            add_shortcode('uas', array( $this, 'on_shortcode_uas') );
            add_shortcode('uas_list', array( $this, 'on_shortcode_uas_list' ) );
        }

        public function on_enqueue_scripts() {
            wp_enqueue_style( 'uas_linker_style', plugins_url('style.css', $this->plguin_file_path));
        }

        static function get_locale() 
        {
            $locale = get_locale();
            if($locale == 'ja' ) {
                $locale = 'ja_JP';
            }

            if( $locale == 'en_US' || $locale == 'zh-CN' || $locale == 'ko_KR' || $locale == 'ja_JP' ) {
                return '&locale=' . $locale;
            } else {
                return '';
            }
        }

        public function on_shortcode_uas($atts, $content=null) {
            $params = shortcode_atts(
                array( 'id' => '', 'class' => '', 'pubref' => '', 'type' => 'widget'
                ), $atts );
                
            if( !isset($params['id']) || $params['id'] == '' ) {
                return '[uas] Error: id is required.';
            }

            $locale = self::get_locale();
                    
            $pubref = empty($params['pubref']) ? '' : ('&pubref=' . $params['pubref']);

            $uas_user_settings = self::get_user_settings();
            $key = $uas_user_settings[self::USER_AFFILIATE_ID];
            
            switch(trim(strtolower($params['type'])))
            {
            case 'link':
                $result = "<a href=\"https://assetstore.unity.com/packages/slug/{$params['id']}?aid={$key}{$pubref}{$locale}\" target=\"_blank\" rel=\"noopener\" class=\"uas uas_link {$params['class']}\">{$content}</a>";
                break;
        
            case 'icon':
                $result = "<a href=\"https://assetstore.unity.com/packages/slug/{$params['id']}?aid={$key}{$pubref}{$locale}\" target=\"_blank\" rel=\"noopener\" class=\"uas uas_icon {$params['class']}\"><img src=\"https://api.assetstore.unity3d.com/affiliate/embed/package/{$params['id']}/icon\" width=\"128\" height=\"128\" class=\"uas uas_icon\"></a>";
                break;
        
            case 'banner':
                $result = "<div class=\"uas_banner {$params['class']}\"><iframe src=\"https://assetstore.unity.com/linkmaker/embed/package/{$params['id']}/widget-wide?aid={$key}{$pubref}{$locale}\" class=\"uas uas_banner\" scrolling=\"no\" frameborder=\"no\"></iframe></div>";
                break;
        
            case 'banner-light':
                $result = "<div class=\"uas_banner {$params['class']}\"><iframe src=\"https://assetstore.unity.com/linkmaker/embed/package/{$params['id']}/widget-wide-light?aid={$key}{$pubref}{$locale}\" class=\"uas uas_banner\" scrolling=\"no\" frameborder=\"no\"></iframe></div>";
                break;
                
            case 'widget-light':
                $result = "<div class=\"uas_widget {$params['class']}\"><iframe src=\"https://assetstore.unity.com/linkmaker/embed/package/{$params['id']}/widget-light?aid={$key}{$pubref}{$locale}\" class=\"uas uas_widget\" scrolling=\"no\" frameborder=\"no\"></iframe></div>"; 
                break;
                
            case 'widget':
            default:
                $result = "<div class=\"uas_widget {$params['class']}\"><iframe src=\"https://assetstore.unity.com/linkmaker/embed/package/{$params['id']}/widget?aid={$key}{$pubref}{$locale}\" class=\"uas uas_widget\" scrolling=\"no\" frameborder=\"no\"></iframe></div>"; 
                break;
            }
            return $result;
        }

        public function on_shortcode_uas_list($atts, $content=null)
        {
            $params = shortcode_atts(
                array( 'id' => '', 'class' => '', 'pubref' => '', 'type' => 'widget'
                ), $atts );
                
            if( !isset($params['id']) || $params['id'] == '' ) {
                return '[uas_link] Error: id is required.';
            }

            $locale = self::get_locale();
                    
            $pubref = empty($params['pubref']) ? '' : ('&pubref=' . $params['pubref']);

            $uas_user_settings = self::get_user_settings();
            $key = $uas_user_settings[self::USER_AFFILIATE_ID];
        
            switch(trim(strtolower($params['type'])))
            {
            case 'link':
                $result = "<a href=\"https://assetstore.unity.com/lists/{$params['id']}?aid={$key}{$pubref}{$locale}\" target=\"_blank\" rel=\"noopener\" class=\"uas uas_list_link {$params['class']}\">{$content}</a>";
                break;
        
            case 'banner':
                $result = "<div class=\"uas_list_banner {$params['class']}\"><iframe src=\"https://assetstore.unity.com/linkmaker/embed/list/{$params['id']}/widget-wide?aid={$key}{$pubref}{$locale}\" class=\"uas uas_list_banner\" scrolling=\"no\" frameborder=\"no\"></iframe></div>";
                break;
                
            case 'banner-light':
                $result = "<div class=\"uas_list_banner {$params['class']}\"><iframe src=\"https://assetstore.unity.com/linkmaker/embed/list/{$params['id']}/widget-wide-light?aid={$key}{$pubref}{$locale}\" class=\"uas uas_list_banner\" scrolling=\"no\" frameborder=\"no\"></iframe></div>";
                break;
        
            case 'widget-light':
                $result = "<div class=\"uas_list_widget {$params['class']}\"><iframe src=\"https://assetstore.unity.com/linkmaker/embed/list/{$params['id']}/widget-medium-light?aid={$key}{$pubref}{$locale}\" class=\"uas uas_list_widget\" scrolling=\"no\" frameborder=\"no\"></iframe></div>"; 
                break;
                
            case 'widget':
            default:
                $result = "<div class=\"uas_list_widget {$params['class']}\"><iframe src=\"https://assetstore.unity.com/linkmaker/embed/list/{$params['id']}/widget-medium?aid={$key}{$pubref}{$locale}\" class=\"uas uas_list_widget\" scrolling=\"no\" frameborder=\"no\"></iframe></div>"; 
                break;
            }
            return $result;
        }
    }

}