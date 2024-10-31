<?php
namespace Ric;
defined( 'ABSPATH' ) || exit;


new AdminMenu();

class AdminMenu{

    public function __construct(){
        \add_action('admin_menu', function(){
              /*
                * main menu
                */
                \add_menu_page( esc_html__('Ricerca Search','ric'),
                esc_html__('Ricerca Search','ric'),'manage_options', 'ric_plugin_admin_menu_page', [$this,'mainMenuCb'], 'dashicons-media-spreadsheet' );

            
                /*
                * sync sub menu
                */
                \add_submenu_page(
                        'ric_plugin_admin_menu_page',
                        esc_html__('Index','ric'), 
                        esc_html__('Index','ric'), 
                        'manage_options',
                        'ric_plugin_admin_menu_sync',
                        [$this,'syncMenu']
                        ,5
                        );



              
                        //collection free menu
            add_submenu_page(
                    'ric_plugin_admin_menu_page',
                    __('Collections Creator','ric'), 
                    __('Collections Creator','ric'), 
                    'manage_options',
                    'ric_plugin_admin_menu_collections',
                    '\Ric\AdminMenu::collectionAdminMenu'
                    ,10
                    );
      


        });


        add_action('ric_admin_collection_menu','\Ric\AdminMenu::collectionAdminMenuFreeHtml');
    }
 
    
    public static function collectionAdminMenuFreeHtml(){
        
      
    
        ?>
        <div class="ric_admin_container ric_admin_container_collection">
            <h1><?php echo __('Collections Creator','ric'); ?></h1>

            <h2><?php echo __("Upgrade to premium and create items collections and expose your visitore for more items on search results",'ric'); ?></h2>
           
           <?php
            
                echo '<a href="#ricaddcollection" class="disabled ricmodal_open button button-primary button-large" >'. __('Add new collection','ric').'</a>';
            
                echo '<img src="'.Func::getAssets('images/c3.png').'" class="ric_col_ad_placeholder" alt="" />';
                echo '<img src="'.Func::getAssets('images/c1.png').'" class="ric_col_ad_placeholder" alt="" />';
                echo '<img src="'.Func::getAssets('images/c2.jpg').'" class="ric_col_ad_placeholder" alt="" />';


            echo '</div>';
    }



    public static function collectionAdminMenu(){


         
    
        $page_type=!empty($_GET['page']) ? wp_kses_post($_GET['page']) :false;
         
        if(!$page_type || $page_type!='ric_plugin_admin_menu_collections'){
                return false;
        }
    
        $screen = get_current_screen();
        if ( $screen->parent_file != 'ric_plugin_admin_menu_page' )
                return;
    


        do_action('ric_admin_collection_menu');



    }


    public function mainMenuCb(){

    
        $page_type=!empty($_GET['page']) ? wp_kses_post($_GET['page']) :false;
        if(!$page_type || $page_type!='ric_plugin_admin_menu_page'){
                return false;
        }
    
        $opt = Func::getOption();
    
        $tabs=[];
        $tabs[]= [
            'name'=>esc_html__('General','ric'),
            'key'=>'general'
        ];
        $tabs[]= [
            'name'=>esc_html__('Posts indexing options','ric'),
            'key'=>'indexing'
        ];
        $tabs[]= [
            'name'=>esc_html__('Categories results - terms indexing options','ric'),
            'key'=>'indexing_tax'
        ];
        $tabs[]= [
            'name'=>esc_html__('Post types to sync','ric'),
            'key'=>'sync'
        ];
        $tabs[]= [
            'name'=>esc_html__('Taxonomies to sync','ric'),
            'description'=>esc_html__('Example: In case your site has Brands as taxonomy and you would like to show search results of items of a certain brand.','ric'),
            'key'=>'sync_tax'
        ];
        $tabs[]= [
            'name'=>esc_html__('Thumbnails','ric'),
            'key'=>'thumbnails'
        ];
        if ( Func::isWoocommerceActive() ){
            $tabs[]= [
                'name'=>esc_html__('Woocommerce compatibility','ric'),
                'key'=>'woocommerce'
            ];
        } 
        $tabs[]= [
            'name'=>esc_html__('Advanced rules','ric'),
            'key'=>'advanced_roles'
        ];
        $tabs[]= [
            'name'=>esc_html__('Miscellaneous','ric'),
            'key'=>'miscellaneous'
        ];
        $tabs[]= [
            'name'=>esc_html__('Performance','ric'),
            'key'=>'performance'
        ];
         
     
    
        $tabs = \apply_filters('ric_settings_tabs', $tabs);
    
     
        echo '<div class="ric ric_admin_container">';
            echo '<h1>'.esc_html__('Ricerca Search','ric').'</h1>';
            echo '<div class="ric_admin_cols">';
          
            echo '<form class="ric_admin_settings ">';
    
            wp_nonce_field( 'ric_nonce', 'ric_nonce' );
    
           echo '<input type="hidden" name="action" value="ric_plugin_save_settings" />';
              
     
                echo '<div class="metabox-holder ric_tabs">';
    
                echo '<div class="ric_tabs_head">';
                    echo '<img src="'.Func::getAssets('images/logo.png').'" alt="logo" />';


                foreach($tabs as $index=>$tab){
                    echo '<a class="ric_tab_toggle '.($index==0 ? 'current' : '').'" href="#ric-tab-'.esc_attr($tab['key']).'">'.esc_attr($tab['name']).'</a>';
                }
            
                do_action('ric_after_settings_tabs');
                 
            echo '</div>';//ric_tabs_head
    
    
      
              
    
                 
                    echo '<div class="ric_tab_content">';
    
    
                        foreach($tabs as $index=>$tab){
                            echo '<div id="ric-tab-'.esc_attr($tab['key']).'" class=" ric_tab_content_item '.($index==0 ? 'current' : '').'">';
                               
                                echo '<h3 class="hndle"><span>'.esc_attr($tab['name']).'</span></h3>';
    
                                if(!empty($tab['description'])){
                                    echo '<p>'.esc_attr($tab['description']).'</p>';
                                }
                                    echo '<div class="ric_grid">';
                                         $fields = Fields::getFieldsSettings();
     
    
                                        $section=esc_attr($tab['key']);
                                        foreach ($fields[$section] as $field){
                                                $key=$field['key'];
 
                                                $field['ispro'] = apply_filters('ric_admin_ispro_field',!empty($field['ispro']) ? $field['ispro'] : false,$field);

                                            echo '<div class="ric_grid_item '.(isset($field['ispro'])&&$field['ispro'] ? 'ric_grid_item_profield' : '').'">';
                                                echo '<div class="ric_grid_item_head">';
                                                    echo '<label for="ric_field'.$key.'">'.$field['label'].'</label>';
                                                echo '</div>';//ric_grid_item_head
                                                echo '<div class="ric_grid_item_content">';
                                                $value= isset($opt[$section][$key]) ? $opt[$section][$key] : $field['def'];
                                                $field['value']=$value;
                                                echo Fields::getFieldInput($field,$opt[$section]);
                                                echo '</div>';//ric_grid_item_content
                                            echo '</div>';//ric_grid_item
                                        } 
                                    echo '</div>';//ric_grid
    
    
    
                                    echo '<button class="button button-primary button-large">'.esc_html__('Save settings','ric').'</button>';
                                    echo '<a role="button" class="ric_save_and_sync button button-large">'.esc_html__('Save settings and sync','ric').'</a>';
                                echo '<div class="ric_err"></div>';
    
    
    
                                do_action('ric_tab_content_after_button_'.esc_attr($tab['key']));
                                
                        
                            echo '</div>';// ric_tab_content_item
    
                        }
    
    
    
     
                        do_action('ric_after_settings_tabs_content');
    
        
    
     
                            
                    
                        echo '</div>';// ric_tab_content
                  
                 
                     
                      
                    do_action('ric_after_settings_html');
                    
            
    
    
                echo '</div>';//metabox-holder ric_tabs
                
                
                
        
                
            echo '</form>';
     
     
         
    


            ?>
            <div class="ric_mailchimp">


                <div class="ric_banner">
                        <h2><?= __('Have A Question?','ric'); ?></h2>
                        <p><?= __('Drop us a line and we will get back at you as soon as possible','ric'); ?></p>


                    <a target="_blank" href="https://www.myricerca.com/#Contact"><?= __('Contact Us','ric'); ?></a>
                </div>








                 <!-- Begin Mailchimp Signup Form -->
<link href="//cdn-images.mailchimp.com/embedcode/classic-071822.css" rel="stylesheet" type="text/css">
<style type="text/css">
	#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif;  width:280px;}
	/* Add your own Mailchimp form style overrides in your site stylesheet or in this style block.
	   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
</style>
<style type="text/css">
	#mc-embedded-subscribe-form input[type=checkbox]{display: inline; width: auto;margin-right: 10px;}
	#mergeRow-gdpr {margin-top: 20px;}
	#mergeRow-gdpr fieldset label {font-weight: normal;}
	#mc-embedded-subscribe-form .mc_fieldset{border:none;min-height: 0px;padding-bottom:0px;}
</style>
<div id="mc_embed_signup">
    <form action="https://myricerca.us12.list-manage.com/subscribe/post?u=b20b8b84839bc7a1e0cce10a3&amp;id=53dbdee12a&amp;v_id=6825&amp;f_id=00bebde0f0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
        <div id="mc_embed_signup_scroll">
        <h2>Subscribe to our newsletter to make sure you get all the cool updates you need!</h2>
        <div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
<div class="mc-field-group">
	<label for="mce-FNAME">First Name </label>
	<input type="text" value="" name="FNAME" class="" id="mce-FNAME">
	<span id="mce-FNAME-HELPERTEXT" class="helper_text"></span>
</div>
<div class="mc-field-group">
	<label for="mce-LNAME">Last Name </label>
	<input type="text" value="" name="LNAME" class="" id="mce-LNAME">
	<span id="mce-LNAME-HELPERTEXT" class="helper_text"></span>
</div>
<div class="mc-field-group">
	<label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
</label>
	<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" required>
	<span id="mce-EMAIL-HELPERTEXT" class="helper_text"></span>
</div>
<div id="mergeRow-gdpr" class="mergeRow gdpr-mergeRow content__gdprBlock mc-field-group">
    <div class="content__gdpr">
        <label>Marketing Permissions</label>
        <p>Please select all the ways you would like to hear from MyRicerca:</p>
        <fieldset class="mc_fieldset gdprRequired mc-field-group" name="interestgroup_field">
		<label class="checkbox subfield" for="gdpr_91057"><input type="checkbox" id="gdpr_91057" name="gdpr[91057]" value="Y" class="av-checkbox gdpr"><span>Email</span> </label>
        </fieldset>
        <p>You can unsubscribe at any time by clicking the link in the footer of our emails. For information about our privacy practices, please visit our website.</p>
    </div>
    <div class="content__gdprLegal">
        <p>We use Mailchimp as our marketing platform. By clicking below to subscribe, you acknowledge that your information will be transferred to Mailchimp for processing. <a href="https://mailchimp.com/legal/terms" target="_blank">Learn more about Mailchimp's privacy practices here.</a></p>
    </div>
</div>
	<div id="mce-responses" class="clear foot">
		<div class="response" id="mce-error-response" style="display:none"></div>
		<div class="response" id="mce-success-response" style="display:none"></div>
	</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_b20b8b84839bc7a1e0cce10a3_53dbdee12a" tabindex="-1" value=""></div>
        <div class="optionalParent">
            <div class="clear foot">
                <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
                <p class="brandingLogo"><a href="http://eepurl.com/irtkhc" title="Mailchimp - email marketing made easy and fun"><img src="https://eep.io/mc-cdn-images/template_images/branding_logo_text_dark_dtp.svg"></a></p>
            </div>
        </div>
    </div>
</form>
</div>
<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
<!--End mc_embed_signup-->
           <style>
            #mc_embed_signup .mc-field-group input{
                padding:2px 0;height: 30px;
            }
           </style>
                </div>
            <?php

echo '</div>';

do_action('ric_after_settings_form');
    
        echo '</div>';//ric_admin_container
    }



    


    /**
    * print sync menu page
    *
    * @since 1.0.0
    * @param none
    * @return none;
    */
    public function syncMenu(){
    

        $page_type=!empty($_GET['page']) ? wp_kses_post($_GET['page']) :false;

        if(!$page_type || $page_type!='ric_plugin_admin_menu_sync'){
                return false;
        }

        $screen = get_current_screen();
        if ( $screen->parent_file != 'ric_plugin_admin_menu_page' )
                return;

            
    
    

        ?>
    <div class="ric_admin_container">
    <?php
    if(!empty($_GET['firstsync'])){

    ?>
    <h1><?php echo esc_html__('Running first Sync, please wait...','ric'); ?></h1>

    <?php
    }
    
    echo Func::getSyncAdmin();



    if(!empty($_GET['firstsync'])){
        ?>
        <script>
    document.addEventListener('DOMContentLoaded', function () {
        $('.ric_sync_data').trigger('submit');
    
    });
    

    </script><?php
    }
    ?>
    </div>


        <div data-nonce="<?php echo wp_create_nonce('ric_nonce'); ?>" class="items_in_index">
            <img class="items_in_index_loader" style="width:30px;height:auto;" src="<?php echo Func::getAssets('images/loader.gif'); ?>" alt="" />
        </div>
        <?php
        



        
    }

}


  

