<?php
namespace Ric;
defined( 'ABSPATH' ) || exit;



/**
* Main plugin class
*
* @since 1.0.0
* @param none
* @return none;
*/
class RicMain{
    
    private static $instance = null;
 
    /**
    * Get instance
    *
    * @since 1.0.0
    * @param none
    * @return none;
    */
    public static function get_instance() {
        if ( null == self::$instance ) {
                self::$instance = new self;
        }
        return self::$instance;
    } 
    
    
    /**
    * __construct
    *
    * @since 1.0.0
    * @param none
    * @return none;
    */
    private function __construct() {

     
      

        
        /*
         * functions
         */
        require RIC_DIR.'inc/functions.php';
      


        /*
        * wp hooks
        */
        require RIC_DIR.'inc/hooks.php';
        
          
        /*
         * admin fields
         */
        require RIC_DIR.'inc/admin_fields.php';
        
        /*
         * ajax
         */
        require RIC_DIR.'inc/public_ajax.php';


 
        /*
        * modules
        */
        require RIC_DIR.'inc/modules/modules.php';
         
        
        /* save def settings */
        $saved= \get_option(RIC_SETTINGS_KEY);
        if(!$saved){
        
            $data=[];
            $sections=Fields::getFieldsSettings();
            foreach ($sections as $section=>$fields){
                 foreach ($fields as $field){
                     $key=$field['key'];
                     $def=$field['def'];
                     if(!isset($data[$section])){
                         $data[$section]=[];
                     }
                         $data[$section][$key] = $def;
                 }
            }
            \update_option(RIC_SETTINGS_KEY, $data);


 

    
        }
        
    
        if(is_admin()){

            require RIC_DIR.'inc/admin_menu.php';
            require RIC_DIR.'inc/admin_ajax.php';
            require RIC_DIR.'inc/status.php';
   
            
            /*
             * main setting link in plugins list admin
             */
            \add_filter( 'plugin_action_links_' . \plugin_basename(RIC_FILE ), array( $this, 'plugin_action_links' ) );
            
            /*
            * load admin
            */
            \add_action( 'admin_init', array( $this, 'admin_init' ), 12, 1 );



           

        }else if(Func::isActive()){
 
          
            /*
             * public staff
             */
 
            require RIC_DIR.'inc/public.php';


            
        
              
            /*
             * front end scrips
             */
            \add_action( 'wp_enqueue_scripts',  array( $this, 'register_front_plugin_scripts' ) );


                /*

                
                wp_add_inline_script( 'wpdocs-my-script', 'const MYSCRIPT = ' . json_encode( array(
                    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                    'otherParam' => 'some value',
                ) ), 'before' );
            $settings=Func::getOption('performance');
            if(!empty($settings['perf_loadcssinline']) && $settings['perf_loadcssinline']=='yes'){
 
                \add_action('wp_head',function(){

                    echo '<style id="ricerca-inline-css">';
                    ob_start("Ric\Func::compress");

            
                        include(RIC_DIR.'assets/css/front.css');

                        $cssContent = ob_get_contents();
                        ob_end_flush();

 
                    echo '</style>';
                });

                
            }*/
    
        }


                
        /*
            * register cron job five minutes
            */
        if (! \wp_next_scheduled ( 'ric_everyfiveminutes_cron_jobs' )) {
            \wp_schedule_event(time()+1, 'everyfiveminutes', 'ric_everyfiveminutes_cron_jobs');
        }

        /*
            * register cron job hourly
            */
        if (! \wp_next_scheduled ( 'ric_hourly_cron_jobs' )) {
            \wp_schedule_event(time()+1, 'hourly', 'ric_hourly_cron_jobs');
        }

        /*
            * detect missed trashed items
            */
        if (! \wp_next_scheduled ( 'ric_missed_trashed' )) {
            \wp_schedule_event(time()+1, 'hourly', 'ric_missed_trashed');
        }
        
        


        
        /*
         * run cron job
         */
        \add_action('ric_everyfiveminutes_cron_jobs',array( $this, 'do_cron_jobs_everyfiveminutes' ) );

        \add_action('ric_hourly_cron_jobs',array( $this, 'do_cron_jobs_hourly' ) );

        \add_action('ric_missed_trashed',array( $this, 'ric_missed_trashed_cb' ) );
        
 
       
        /*
         * hook plugion loaded
         */
        \do_action('ric_plugin_loaded');
         

    }
    

    /**
    * Run cron job
    *
    * @since 1.0.0
    * @param none
    * @return none;
    */
    public function do_cron_jobs_everyfiveminutes(){
        /*
         * sync posts by cron job
         */
        Func::doSyncCronPosts();

     
    }
    
    /**
    * Run cron job do_cron_jobs_hourly
    *
    * @since 1.0.0
    * @param none
    * @return none;
    */
    public function do_cron_jobs_hourly(){

        
 

      
        //clear deleted & not relative items

        // unmatches the settings
        $reg_post_types = Func::getPostTypes();
        $selectes = Func::getPostTypesToSync();

     
        if(!empty($reg_post_types)){
            $unmateched = [];
            foreach($reg_post_types as $reg_post_type){
                if(!in_array($reg_post_type,$selectes)){
                    $unmateched[]=$reg_post_type;
                }
            }

       
           
            if(!empty($unmateched)){
                try{
                    Db::deleteByPostType('items',$unmateched);
                }catch(Exception $e){

                }
            }
        }


        //deleted unselected terms
        $selectes = Func::getTaxonomoesToSync();
        $taxonomies = Func::getTaxonomies(); 
 
        if(!empty($taxonomies)){
            $unmateched = [];
            foreach($taxonomies as $tax){
                if(!in_array($tax,$selectes)){
                    $unmateched[]=$tax;
                }
            }
            if(!empty($unmateched)){
                try{
                    Db::deleteByPostType('taxonomies',$unmateched);
                }catch(Exception $e){

                }
            }
        }



        
    }
    

    /**
    * Run cron job missed items, in trash | draft etc
    *
    * @since 1.0.0
    * @param none
    * @return none;
    */
    public function ric_missed_trashed_cb(){

        $args=[];
      //  $args['limit']=-1;
        $args['fields']='id,wp_id';
        $items = Db::select('items',$args);
        if(!empty($items)){
            foreach($items as $item){ 
                $poObject = \get_post(intval($item->wp_id));

                if(!$poObject || (!empty($poObject) && $poObject->post_status!='publish')){
                    Db::deleteById('items',$item->id);
                }
              
            }
        }


        $lang=\Ric\Func::get_locale();

        $tble= 'termscorrector';
        $args=[];
        $args['lang']='';
        $withoutLang = Db::select($tble,$args);
        if(!empty($withoutLang)){
            foreach($withoutLang as $w){
                $args=[
                    'lang' => $lang
                ];
                Db::updateById($tble,$w->id,$args);
            }
        }
 

        $tble= 'taxonomies';
        $args=[];
        $args['lang']='';
        $withoutLang = Db::select($tble,$args);
        if(!empty($withoutLang)){
            foreach($withoutLang as $w){
                $args=[
                    'lang' => $lang
                ];
                Db::updateById($tble,$w->id,$args);
            }
        }
 
        

        /*   
        $selectes = Func::getPostTypesToSync();
     
        $args=[]; 
        $args['fields']='ids';
        $args['orderby']='rand';
        $args['posts_per_page']=400;
        $args['post_status']=array('pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash');
        $args['post_type']=$selectes;
        $prs= \get_posts($args);
        if(!empty($prs)){
            foreach($prs as $pr){
                $pdata = Db::getRowByColumn('items','wp_id',$pr);
                if(!empty($pdata)){
                    Db::deleteById('items',$pdata->id);
                }
            } 
        }*/
    }
    
    
    
    /**
    * Run admin init callbacks
    *
    * @since 1.0.0
    * @param none
    * @return none;
    */
    public function admin_init() {
        /*
         * register admin css & js
         */
        \add_action('admin_enqueue_scripts', array( $this, 'admin_plugin_styles_cb' ));
        
        /*
         * put some ric variables in admin footer
         */
        \add_action( 'admin_footer', array( $this, 'admin_plugin_footer_cb' ));
    }
    
  
    /**
    * Print admin footer js variables
    *
    * @since 1.0.0
    * @param none
    * @return none;
    */
    public function admin_plugin_footer_cb(){
        $args=[];
        $args['assets_url'] = Func::getAssets();
        $args['crons'] = Func::getCronsList();
        $args['strings'] = [];
        $args['strings']['msg1'] =esc_html__('Done','ric');
        $args['strings']['msg2'] =esc_html__('Finalizing.','ric');
        $args['strings']['msg3'] =esc_html__('Search','ric');
        $args['strings']['msg4'] =esc_html__('Are you sure to delete? this item will back to the index if there is any misconfiguration setting','ric');
        $args['strings']['msg5'] =esc_html__('Items found','ric');
     
        ?><script>
            var ricAdminConfig = <?php echo \json_encode($args); ?>;
            var ricSyncModal = <?php echo \json_encode(\Ric\Func::getModalSync()); ?>;
            var ricUpgradeModal = <?php echo \json_encode(\Ric\Func::getUpgradeModal()); ?>;
        </script><?php
    }
    
  
    /**
    * print admin css & js
    *
    * @since 1.0.0
    * @param none
    * @return none;
    */
    public function admin_plugin_styles_cb(){


         
        /*
         * main admin css
         */
        \wp_register_style( RIC_SLUG.'-admin', Func::getAssets('css/admin.css'),array(),RIC_VER);
        \wp_register_style( RIC_SLUG.'-table-admin', Func::getAssets('css/light-table.css'),array(),RIC_VER);
        \wp_enqueue_style( RIC_SLUG.'-admin');
        \wp_enqueue_style( RIC_SLUG.'-table-admin');
        //add rtl support
      //  \wp_style_add_data(  RIC_SLUG.'-admin', 'rtl', 'replace' );
     
        /*
        * main admin js
        */ 
	    \wp_enqueue_script(RIC_SLUG.'-table-js',Func::getAssets('js/light-table.js'),[],RIC_VER,true);
	    \wp_enqueue_script(RIC_SLUG.'-js',Func::getAssets('js/admin.js'),[],RIC_VER,true);
    }
    

    /**
    * print front css & js
    *
    * @since 1.0.0
    * @param none
    * @return none;
    */
    public function register_front_plugin_scripts(){

        \wp_enqueue_script(RIC_SLUG.'-front-js',Func::getAssets('js/front.js') ,array('jquery'),RIC_VER,true);


        $settings=Func::getOption('performance');

        if(!empty($settings['perf_loadcssinline']) && $settings['perf_loadcssinline']=='yes'){

            ob_start();

    
                include(RIC_DIR.'assets/css/front.css');

                $cssContent = ob_get_contents();
            ob_end_clean();

            $cssContent = \Ric\Func::compress($cssContent);

            wp_register_style( RIC_SLUG.'-front-css', false );
            wp_enqueue_style(RIC_SLUG.'-front-css' );
            wp_add_inline_style( RIC_SLUG.'-front-css',$cssContent);


        }else{

            \wp_register_style( RIC_SLUG.'-front-css', Func::getAssets('css/front.css'),array(),RIC_VER);
            \wp_enqueue_style( RIC_SLUG.'-front-css');  

        }

    

        
    
     



//\wp_style_add_data(  RIC_SLUG.'-front-css', 'rtl', 'replace' );
    }


    /**
    * append main settings menu 
    *
    * @since 1.0.0
    * @param none
    * @return none;
    */
    public function plugin_action_links( $actions ) {
        $custom_actions = array();
        $custom_actions['settings'] =sprintf( '<a href="%s" >%s</a>', admin_url('admin.php?page=ric_plugin_admin_menu_page'), esc_html__( 'Settings', 'ric' ) ); 

        // add the links to the front of the actions list
        return array_merge( $custom_actions, $actions );
    }

  
}