<?php
namespace Ric;
defined( 'ABSPATH' ) || exit;

class Fields{
        
    /**
    * Return admin settings fields
    *
    * @since 1.0.0
    * @param none
    * @return array;
    */
    public static function getFieldsSettings(){
    
        $r=[];
        

            
        $f=[];
        $f['key'] = 'ric_status';
        $f['def'] = 'active_preview';
        $f['type'] = 'select';
        $f['subtype'] = 'none';
        $f['options'] = [
            'active' => esc_html__('Active','ric'),
            'active_preview' => esc_html__('Preview mode','ric')
        ];
        $f['label'] = esc_html__('Status','ric');
        $f['label_row_after'] = '';
        $f['label_row_after'] = esc_html__('When preview mode is activated you can view Ricerca search by including this query in your url','ric').'<code>'.home_url('?ric_prev_mode=true').'</code>';
        $r['general'][]=$f;

        $f=[];
        $f['key'] = 'newtab';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Open items in a new tab','ric');
        $f['label_row_after'] = '';
        $r['general'][]=$f;

 
        $f=[];
        $f['key'] = 'redirect_to_search';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Show link to a search results page at the bottom of the search drop down','ric');
        $f['label_row_after'] = '';
        $r['general'][]=$f;
        
    
    
            
        
        $f=[];
        $f['key'] = 'results_to_show';
        $f['def'] = 5;
        $f['type'] = 'number';
        $f['max'] =10;
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Maximum results limit','ric');
        $f['label_row_after'] = ''; 
        $f['label_row_after_pro'] = sprintf( esc_html__('No enough search reults?  %s and get up to 150 results.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>');
        $r['general'][]=$f;
        


        $f=[];
        $f['key'] = 'search_tax_terms';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Show categories list on search results layout','ric');
        $f['label_row_after'] = '';
        $r['general'][]=$f;

        $f=[];
        $f['key'] = 'show_user_history';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Show user searches history','ric');
        $f['label_row_after'] = esc_html__('Last five search terms','ric');
        $r['general'][]=$f;




         
            
        $f=[];
        $f['key'] = 'selector_run';
        $f['def'] = '';
        $f['type'] = 'text';
        $f['disablefield'] = 'layout';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('CSS selector to run the search modal.','ric');
        $f['label_row_after'] = 'On filling this, the option to choose layout will be disabled, and the modal design will be shown by default.';
        $r['general'][]=$f;
        

    
        $f=[];
        $f['key'] = 'search_tax_terms_count';
        $f['def'] = 5;
        $f['type'] = 'number';
        $f['max'] =5;
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Maximum categories results limit','ric');
        $f['label_row_after'] = '';
        $f['label_row_after_pro'] = sprintf( esc_html__('No enough search reults?  %s and get up to 30 results.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>');
        $r['general'][]=$f;


    
        
            
        $f=[];
        $f['key'] = 'show_images';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Show thumbnails in search results','ric');
        $f['label_row_after'] = '';
        $r['thumbnails'][]=$f;
    

            
        $f=[];
        $f['key'] = 'image_fit';
        $f['def'] = 'contain';
        $f['type'] = 'select';
        $f['subtype'] = 'none';
        $f['options'] = [
            'contain' => esc_html__('Displying full image - maintaining its aspect ratio','ric'),
            'cover' => esc_html__("Cover - Displying part of the image",'ric')
        ];
        $f['label'] = esc_html__('How image should be resized to fit its container?','ric');
        $f['label_row_after'] = '';
        $r['thumbnails'][]=$f;
    

 

    

        
        
        $f=[];
        $f['key'] = 'woocommerce';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Woocommerce support active','ric');
        $f['label_row_after'] = '';
        $r['woocommerce'][]=$f;



        $f=[];
        $f['key'] = 'redirect_to_search_product';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Link to a search results should show only products','ric');
        $f['label_row_after'] = '';
        $r['woocommerce'][]=$f;
        


        
        
 


        
                
        $f=[];
        $f['key'] = 'free_hideoutofstock';
    
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Hide out of stock items from the search results','ric');
        $f['label_row_after'] = '';
   
        $f['ispro'] = true;
        $f['label_row_after_pro'] = sprintf( esc_html__('%s and start excluding Not In Stock items from being shown in search results.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>');
       
        $r['woocommerce'][]=$f;


 

        
        
        $f=[];
        $f['key'] = 'addtocart';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Show add to cart url','ric');
        $f['label_row_after'] = '';
        $r['woocommerce'][]=$f;
        
        $f=[];
        $f['key'] = 'prices';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Show prices','ric');
        $f['label_row_after'] = '';
        $r['woocommerce'][]=$f;
            

        
        $f=[];
        $f['key'] = 'sku';
        $f['def'] = 'yes';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] =  esc_html__('Index product SKU','ric');
        $f['label_row_after'] =  '';
        $r['woocommerce'][]=$f;

        
        $f=[];
        $f['key'] = 'woocommerce_salefirst';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Pin purchasable products at the top of the search results.','ric');
        $f['ispro'] = true;
        $f['label_row_after_pro'] = sprintf( esc_html__('%s and show purchasable products first.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>');
        $f['label_row_after'] = '';
        $r['woocommerce'][]=$f;

        


        /*
            indexing
        */

            
  
                
        $f=[];
        $f['key'] = 'indexing_title';
        $f['def'] = 'yes';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__("Post's name sync",'ric');
        $f['label_row_after'] =esc_html__("Will show search results based on the post's name: for example: product name",'ric');
        $r['indexing'][]=$f;

                
    

        $f=[];
        $f['key'] = 'indexing_post_content';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] =  esc_html__('Main content in posts','ric');
        $f['label_row_after'] =esc_html__("Will show search results based on content appearing in the visual editor",'ric');
        $r['indexing'][]=$f;



    
        
        $f=[];
        $f['key'] = 'indexing_post_excerpt';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] =  esc_html__('Excerpt sync','ric');
        $f['label_row_after'] = esc_html__("Will show search results based on the post's excerpt: for example: product excerpt",'ric');
        $r['indexing'][]=$f;
    
 
 
        
        $f=[];
        $f['key'] = 'indexing_term_title';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Categories name sync','ric');
        $f['label_row_after'] = esc_html__("will show results on the categories list based on the category name. For example: Searching the term Chairs will show categories on the categories list that it's name contains the term Chairs.",'ric');
        $r['indexing_tax'][]=$f;

        $f=[];
        $f['key'] = 'indexing_term_description';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] =  esc_html__('Categories description sync','ric');
        $f['label_row_after'] =esc_html__('will show results on the categories list based on the category description. For example: Searching the term Chairs will show categories on the categories list that their description contains the term Chairs.','ric');
        $r['indexing_tax'][]=$f;
        
    

    

        

        /* 
        * post types to sync
        */
        $r['sync']=[];
        $post_types = Func::getPostTypes();
        if(!empty($post_types)){
            foreach ($post_types as $post_type){
                if($post_type=='attachment'){
                    continue;
                }
                $f=[];
                $f['key'] = $post_type;
                $f['def'] = 'no';
                $f['type'] = 'checkbox';
                $f['subtype'] = 'none';
                $f['label'] = $post_type;
                $f['label_row_after'] = '';
                $r['sync'][]=$f;
            }
        }

        /* 
        * taxonomies to sync
        */
        $taxonomies = Func::getTaxonomies(); 
        if(!empty($taxonomies)){
            foreach ($taxonomies as $tax){
                $f=[];
                $f['key'] = $tax;
                $f['def'] = 'no';
                $f['type'] = 'checkbox';
                $f['subtype'] = 'none';
                $f['label'] = $tax;
                $f['label_row_after'] = '';
                $r['sync_tax'][]=$f;
            }
        }

        
        
        
        $f=[];
        $f['key'] = 'layout';
        $f['def'] = 'simple';
        $f['type'] = 'select';
        $f['subtype'] = 'none';
        $f['options'] = [
            'simple' => esc_html__('Simple layout','ric'),
            'wide' => esc_html__('Wide layout','ric'),
            'modal' => esc_html__('Modal layout','ric')
        ];
        $f['label'] = esc_html__('Search drop layout','ric');
        $f['label_row_after'] = '';

        $f['subfields_condition'] =[]; 
        $f2=[];
        $f2['key'] = 'layout_wide_def';
        $f2['def'] = 1065;
        $f2['type'] = 'unit';
        $f2['label'] = esc_html__('Default width for wide screens','ric');
        $f2['label_row_after'] = ''; 
        $f['subfields_condition']['wide'][] =$f2;

        $f2=[];
        $f2['key'] = 'layout_wide_1400';
        $f2['def'] = 940;
        $f2['type'] = 'unit';
        $f2['label'] = sprintf( esc_html__('Default width for screens under %d px', 'ric' ), 1400);
        $f2['label_row_after'] = ''; 
        $f['subfields_condition']['wide'][] =$f2;

        $f2=[];
        $f2['key'] = 'layout_wide_1280';
        $f2['def'] = 940;
        $f2['type'] = 'unit';
        $f2['label'] =sprintf( esc_html__('Default width for screens under %d px', 'ric' ), 1280);
        $f2['label_row_after'] = ''; 
        $f['subfields_condition']['wide'][] =$f2;

        $f2=[];
        $f2['key'] = 'layout_wide_980';
        $f2['def'] = 600;
        $f2['type'] = 'unit';
        $f2['label'] = sprintf( esc_html__('Default width for screens under %d px', 'ric' ), 1120);
        $f2['label_row_after'] = ''; 
        $f['subfields_condition']['wide'][] =$f2;

        $f2=[];
        $f2['key'] = 'layout_wide_980';
        $f2['def'] = 600;
        $f2['type'] = 'unit';
        $f2['label'] =sprintf( esc_html__('Default width for screens under %d px', 'ric' ), 980);
        $f2['label_row_after'] = ''; 
        $f['subfields_condition']['wide'][] =$f2;

        $f2=[];
        $f2['key'] = 'layout_wide_750';
        $f2['def'] = '100%';
        $f2['type'] = 'unit';
        $f2['label'] = sprintf( esc_html__('Default width for screens under %d px', 'ric' ), 750);
        $f2['label_row_after'] = ''; 
        $f['subfields_condition']['wide'][] =$f2;

        $r['general'][]=$f;

 
            
        $f=[];
        $f['key'] = 'search_field_label';
        $f['def'] = 'What you are looking for today?';
        $f['type'] = 'text';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Search field label','ric');
        $f['label_row_after'] = '';
        $r['miscellaneous'][]=$f;
        
        




                
        $f=[];
        $f['key'] = 'free_indexingpostscategories';
    
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] =  esc_html__("Name crossing - Crossing a search query with category name in order to display it's items",'ric');
        $f['label_row_after'] =  esc_html__("In addition to the existing search results, this feature shows product search results from the category whose it's named meets the requested term. For example, when searching Beds the system shows products that do not contain the word Beds but their category is Beds",'ric');
   
        $f['ispro'] = true;
        $f['label_row_after_pro'] = sprintf( esc_html__('%s and improve the way you customize your search results.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>');
       
        $r['indexing'][]=$f;


                                
        $f=[];
        $f['key'] = 'free_indexingpostscategoriesaddition';
        $f['ispro'] = true;
        $f['def'] = '';
        $f['type'] = 'textarea';
        $f['subtype'] = 'commaseprated';
        $f['label'] = esc_html__("Index custom taxonomies names (comma seprated)",'ric');
        $f['label_row_after'] = esc_html__("Fill the taxonomy slug key  (Comma separated, recommended to consult an expert)",'ric');
        $f['ispro'] = true;
        $f['label_row_after_pro'] = sprintf( esc_html__('%s, start syncing your custome taxonomies fields and get better control on your search results.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>');
       
        $r['indexing'][]=$f;


   
        $f=[];
        $f['key'] = 'meta_image_key';
        $f['def'] = '_thumbnail_id';
        $f['type'] = 'text';
        $f['subtype'] = 'none';
        $f['label'] = __('Custom image field meta key','ric');
        $f['label_row_after'] = '';
        $r['thumbnails'][]=$f;

   
      

            
        $f=[];
        $f['key'] = 'image_size_slug';
        $f['def'] = 'thumbnail';
        $f['type'] = 'select';
        $f['subtype'] = 'none';
        $f['options']=[];

        if(function_exists('get_intermediate_image_sizes')){
            $intermediate_image_sizes = get_intermediate_image_sizes();
            if(!empty($intermediate_image_sizes)){
                foreach($intermediate_image_sizes as $size){
                    $f['options'][] = $size;
                }
            }
        }
        
        if(empty($f['options'])){
            $f['options'][] = 'thumbnail';
        }
        $f['label'] = __('Image size slug','ric');
        $f['label_row_after'] = esc_html__("For best performance, please keep it as lower size you can.",'ric');
        $r['thumbnails'][]=$f;

 

        $f=[];
        $f['key'] = 'free_indexingpostmeta';
        $f['ispro'] = true;
        $f['def'] = '';
        $f['type'] = 'textarea';
        $f['subtype'] = 'commaseprated';
        $f['label'] = esc_html__("Index additional meta fields Fill meta field's key names (comma seprated)",'ric');
        $f['label_row_after'] = esc_html__("Fill meta field's key  (Comma separated, recommended to consult an expert)",'ric');
        $f['ispro'] = true;
        $f['label_row_after_pro'] = sprintf( esc_html__('%s, start syncing your custome meta fields and get better control on your search results.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>');
       


        $r['indexing'][]=$f;




        $attrs = [];
        $attrs['none']='none';
    //  $taxonomies = ric_get_taxonomies(); 
        if(!empty($taxonomies)){
            foreach ($taxonomies as $tax){
                if(strpos($tax,'pa_')!==false){
                    $attrs[$tax]=$tax;
                }
            }
        }

     /*   $f=[];
        $f['key'] = 'free_colorattribute';
        $f['ispro'] = true;
        $f['def'] = 'none';
        $f['type'] = 'select';
        $f['subtype'] = 'none';
        $f['options'] = $attrs;
        $f['label'] = esc_html__('Color attribute','ric');
        $f['label_row_after'] = '';
        $f['label_row_after_pro'] = esc_html__('Go Primium','ric');
        $r['woocommerce'][]=$f;*/

        $f=[];
        $f['key'] = 'order_items_by';
        $f['ispro'] = true;
        $f['def'] = 'default';
        $f['type'] = 'select';
        $f['subtype'] = 'none';
        $f['options'] = [
            'default' => __('Automatic','ric'),
            'topclickeditems' => __('Most cliked items','ric'),
            'toppurchaseditems' => __('Best sellers from search results','ric'),
            'topaddedtocartitems' => __('Most added to cart from search results','ric')
        ];
        $f['label'] = esc_html__('Order items by - Choose the order of the items on the search results layout','ric');
        $f['label_row_after'] = '';
        
        $f['label_row_after_pro'] = sprintf( esc_html__('%s and change items ordering.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>');
        $r['general'][]=$f;





             
        $f=[];
        $f['key'] = 'advanced_roles_exclude_posts';
        $f['def'] = '';
        $f['placeholder'] =   esc_html__('Type at least two letters','ric');
        $f['type'] = 'items_select';
        $f['source'] = 'items';
        $f['max'] =5;
        $f['label'] =  esc_html__('Exclude posts','ric');
        $f['label_row_after'] = esc_html__("Exclude posts from shown in search results",'ric');
        $f['label_row_after_pro'] = sprintf( esc_html__('Not enough posts to exclude?  %s and get up unlimited posts.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>');
        $r['advanced_roles'][]=$f;

             
        $f=[];
        $f['key'] = 'advanced_roles_exclude_categories';
        $f['def'] = '';
        $f['placeholder'] =   esc_html__('Type at least two letters','ric');
        $f['type'] = 'items_select';
        $f['source'] = 'terms';
        $f['label'] =  esc_html__('Exclude categories','ric');
        $f['label_row_after'] = esc_html__("Exclude posts of categories from shown in search results",'ric');
        $f['ispro'] = true;
        $f['label_row_after_pro'] = sprintf( esc_html__('%s and start hide unwanted categories.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>');
        $r['advanced_roles'][]=$f;


 


                    
        $f=[];
        $f['key'] = 'perf_front_fetch_items';
        $f['def'] = 'onload';
        $f['type'] = 'select';
        $f['subtype'] = 'none';
        $f['options'] = [
            'onload' => esc_html__('On page load','ric'),
            'afterload' => esc_html__("Directly after load, using AJAX",'ric'),
            'onengagement' => esc_html__("After first engagement (Scroll, Touch Click.., this is best for page speed load), using AJAX",'ric')
        ];
        $f['label'] = esc_html__('When Index items should loaded in the front?','ric');
        $f['label_row_after'] = esc_html__('We prefer On page load. but in large amount of posts this can make conflict with some page cache plugins','ric');
        $r['performance'][]=$f;

                    

        $f=[];
        $f['key'] = 'perf_loadcssinline';
        $f['def'] = 'no';
        $f['type'] = 'checkbox';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Load CSS as inline instead of files','ric');
        $f['label_row_after'] = '';
        $r['performance'][]=$f;
 
                    
        $f=[];
        $f['key'] = 'perf_front_cache_expire';
        $f['def'] = 60;
        $f['type'] = 'select';
        $f['subtype'] = 'none';
        $f['options'] = [
            'none' =>  __("None",'ric'),
            '60' =>  __("1 Minute",'ric'),
            '180' =>  sprintf( esc_html__('%d Minutes', 'ric' ), 3),
            '300' =>  sprintf( esc_html__('%d Minutes', 'ric' ), 5),
            '600' =>  sprintf( esc_html__('%d Minutes', 'ric' ), 10),
            '3600' =>  sprintf( esc_html__('%d Minutes', 'ric' ), 60)
        ];
        $f['label'] = esc_html__('Cache expire time for get items query on the frontend?','ric');
        $f['label_row_after'] = esc_html__('In case of busy site, keep it as higher you can','ric');
        $r['performance'][]=$f;


               
        $f=[];
        $f['key'] = 'items_per_sync';
        $f['def'] = 100;
        $f['type'] = 'number';
        $f['subtype'] = 'none';
        $f['label'] = esc_html__('Amount of items to be synced in each automatic or manual request (every 5 min)','ric');
        $f['label_row_after'] = esc_html__('In case of busy site, keep it as lower you can','ric');
        $r['performance'][]=$f;

        

        
        /*
        * filter to add more fields
        */
        $r = \apply_filters('ric_settings_fields_array', $r);
        return $r;
    }


    /**
    * Return admin html field
    *
    * @since 1.0.0
    * @param array $args
    * @return string;
    */
    public static function getFieldInput($args,$section){


        if(empty($args['key'])){
            return false;
        }
        if(empty($args['type'])){
            return false;
        }

        $key = wp_kses_data($args['key']);
        $placeholder = !empty($args['placeholder']) ? wp_kses_data($args['placeholder']) : false;
        $max = !empty($args['max']) ? wp_kses_data($args['max']) : false;
        $val = !empty($args['value']) && !is_array($args['value']) ? wp_kses_data($args['value']) : false;
        $after = !empty($args['label_row_after']) ? wp_kses_post($args['label_row_after']) : false;
        $afterPro = !empty($args['label_row_after_pro']) ? wp_kses_post($args['label_row_after_pro']) : false;
        $subfields_condition = !empty($args['subfields_condition']) ?$args['subfields_condition'] : [];

        $isPro =isset($args['ispro'])&&$args['ispro'];
       // $html='Unkown field '.$args['type'];
       //$html='';
        switch ($args['type']){
            case 'items_select':


                $html='<div data-max="'.$max.'" data-key="'.$key.'" data-nonce="'.wp_create_nonce('ric_nonce').'" data-params="'.htmlspecialchars(json_encode($args), ENT_QUOTES, 'UTF-8').'" class="ric_items_select">';
                    $html.='<div class="items_select_field">';    
                        $html.='<input '.(!empty($placeholder) ? 'placeholder="'.$placeholder.'"' : '').' '.($isPro ? 'disabled' : '').' type="text"  id="ric_field'.$key.'" name="search_'.$key.'"  '.($val=='yes' ? 'checked' : '').' value="'.($val ? $val : '').'" />';
                    $html.='</div>';    
                
                    $html.='<div class="items_select_list_outer"><div class="items_select_list"></div></div>';
                    $html.='<div class="items_select_target">';
                 
                      if(!empty($args['value'])){
                            foreach($args['value'] as $id=>$title){
                                $id = \absint($id);
                                $title=\esc_attr($title);
                                $html.= '<div data-id="'.$id.'" class="items_select_target_item">';
                                    $html.= '<input type="hidden" name="'.$key.'[id][]" value="'.$id.'" >';
                                    $html.= '<input type="hidden" name="'.$key.'[title][]" value="'.$title.'" >';
                                    $html.= '<span>'.$title.'</span>';
                                    $html.= '<a href="#" role="button"  class="items_select_target_remove">x</a>';
                                $html.= '</div>';
                            }
                        }
 
                    $html.='</div>';
                $html.='</div>';
            break;
            case 'color':
                $html='<input '.($isPro ? 'disabled' : '').' type="color"  id="ric_field'.$key.'" name="'.$key.'"  '.($val=='yes' ? 'checked' : '').' value="'.($val ? $val : '').'" />';
            break;
            case 'checkbox':
                $html='<input '.($isPro ? 'disabled' : '').' type="checkbox"  id="ric_field'.$key.'" name="'.$key.'"  '.($val=='yes' ? 'checked' : '').' value="yes" />';
            break;
            case 'number':
                $html='<input '.($isPro ? 'disabled' : '').' type="number" '.($max ? 'max="'.$max.'"' : '').' id="ric_field'.$key.'" name="'.$key.'"   value="'.($val ? $val : '0').'" />';
            break;
            case 'text':
                //disablefield
                $html='<input '.((!empty($args['disablefield']) ? 'data-todisable="'.$args['disablefield'].'"' : '')).' '.(!empty($placeholder) ? 'placeholder="'.$placeholder.'"' : '').' '.($isPro ? 'disabled' : '').' type="text"  id="ric_field'.$key.'" name="'.$key.'"   value="'.($val ? $val : '').'" />';
            break;
            case 'unit':
                $html='<div style="clear: both"></div>';
                $html.='<div class="ric_unit_field">';
                    $unitKey=$key.'_unit';
                    $unitValue= isset($section[$unitKey]) ? $section[$unitKey] : 'px';
                     
                    $html.='<select  '.($isPro ? 'disabled' : '').' id="ric_field'.$unitKey.'" name="'.$unitKey.'">';
                        $html.='<option '.($unitValue == 'percent' ? 'selected' : '').' value="percent">%</option>';
                        $html.='<option  '.($unitValue == 'px' ? 'selected' : '').' value="px">px</option>';
                        $html.='<option  '.($unitValue == 'rem' ? 'selected' : '').' value="rem">rem</option>';
                        $html.='<option  '.($unitValue == 'em' ? 'selected' : '').' value="em">em</option>';
                        $html.='<option  '.($unitValue == 'vw' ? 'selected' : '').' value="vw">vw</option>';
                    $html.='</select>';
                    $html.='<input '.(!empty($placeholder) ? 'placeholder="'.$placeholder.'"' : '').' '.($isPro ? 'disabled' : '').' type="number"  id="ric_field'.$key.'" name="'.$key.'"   value="'.($val ? $val : '').'" />';

                $html.='</div>';
            break;
            case 'textarea':
                $html='<textarea '.(!empty($placeholder) ? 'placeholder="'.$placeholder.'"' : '').' '.($isPro ? 'disabled' : '').'  id="ric_field'.$key.'" name="'.$key.'" >'.($val ? $val : '').'</textarea>';
            break;
            case 'select':
                $html='<select class="'.(!empty($subfields_condition) ? 'ric_condition_field' : '').'"  '.($isPro ? 'disabled' : '').' id="ric_field'.$key.'" name="'.$key.'">';
                foreach ($args['options'] as $k=>$v){
                    $html.='<option '.($val==wp_kses_data($k) ? 'selected' : '').' value="'.wp_kses_data($k).'">'.wp_kses_data($v).'</option>';
                }
                $html.='</select>';
            break;
        }



        
        $html.='<p>'.$after.'</p>';

        if($afterPro){
            $html.= '<div class="label_row_after_pro">'.\apply_filters('label_row_after_pro', $afterPro).'</div>';
        }

        if(!empty($subfields_condition)){
            foreach($subfields_condition as $conditionValue=>$fielArgs){
                $isActive = $conditionValue==$val ? 'active' : '';
                $html.='<div data-k="'.$conditionValue.'" class="subfields_condition '.$isActive.'">';
                foreach($fielArgs as $s){
                    $fieldKey= $s['key'];
                    $value= isset($section[$fieldKey]) ? $section[$fieldKey] : $s['def'];
                    $s['value']=$value;
                    $html.=  '<label for="ric_field'.$fieldKey.'">'.$s['label'].'</label>';;
                    $html.= Fields::getFieldInput($s,$section); 
                }
                $html.='</div>';
            }
        }

     
        
        return $html;
    }

}
