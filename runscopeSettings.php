<?php
class runscopeSettings {
    public function __construct() {
        if ( is_admin() ){
            add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
            add_action( 'admin_init', array( $this, 'page_init' ) );
        }
    }

    public function add_plugin_page(){
        // This page will be under "Settings"
        add_options_page( 'Runscope Settings', 'Runscope Settings', 'manage_options', 'runscope-settings-admin', array( $this, 'create_admin_page' ) );
    }

    public function create_admin_page() {
        ?>
	<div class="wrap">
	    <?php screen_icon(); ?>
	    <h2>Settings</h2>
	    <form method="post" action="options.php">
	        <?php

		    settings_fields( 'runscope_option_group' );
		    do_settings_sections( 'runscope-settings-admin' );
		?>
	        <?php submit_button(); ?>
	    </form>
	</div>
	<?php
    }

    public function page_init() {
        register_setting( 'runscope_option_group', 'array_key', array( $this, 'check_ID' ) );

            add_settings_section(
            'setting_section_id',
            'Bucket Settings',
            array( $this, 'print_section_info' ),
            'runscope-settings-admin'
        );

        add_settings_field(
            'runscope_bucket_id',
            'Bucket ID',
            array( $this, 'create_an_id_field' ),
            'runscope-settings-admin',
            'setting_section_id'
        );
    }

    public function check_ID( $input ) {
        $mid = $input['runscope_bucket_id'];

        if ( get_option( 'runscope_bucket_id' ) === FALSE ) {
            add_option( 'runscope_bucket_id', $mid );
        } else {
            update_option( 'runscope_bucket_id', $mid );
        }

        return $mid;
    }

    public function print_section_info(){
        print 'Enter your bucket ID below:';
    }

    public function create_an_id_field(){
        ?><input type="text" id="bucket" name="array_key[runscope_bucket_id]" value="<?php echo get_option( 'runscope_bucket_id' ); ?>" /><?php
    }
}

$runscopeSettings = new runscopeSettings();