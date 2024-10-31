<?php
namespace Ric\RicPro\Modules;
defined( 'ABSPATH' ) || exit;

 
 

Popularterms::init();


class Popularterms{
    private static $instance = null;

    public static function init(){
        if ( null == self::$instance ) {
                    self::$instance = new self;
            }
            return self::$instance;
    }


    public function __construct(){ 
 
        

        
        \add_filter('ric_settings_tabs', function($tabs){
            $tabs[]= [
                'name'=>__('Popular terms','ric'),
                'key'=>'popularterms'
            ];
            return $tabs;
        });


        \add_filter('ric_settings_fields_array', function($fields){

 
            $f=[];
            $f['key'] = 'popularterms_active';
            $f['def'] = 'no';
            $f['type'] = 'checkbox';
            $f['subtype'] = 'none';
            $f['label'] = __('Show popular searched terms','ric');
            $f['label_row_after'] = '';
            $f['ispro'] = true;
            $f['label_row_after_pro'] = sprintf( esc_html__('%s to show popular search terms.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>');
            $fields['popularterms'][]=$f;

            $f=[];
            $f['key'] = 'popularterms_badlist';
            $f['def'] = 'sex, drugs, sexy, pussy';
            $f['type'] = 'textarea';
            $f['subtype'] = 'commaseprated';
            $f['label'] = esc_html__('Block terms from being shown as popular terms','ric');
            $f['label_row_after'] = esc_html__('(Comma separated, lowercase)','ric');
            $f['ispro'] = true;
            $f['label_row_after_pro'] = sprintf( esc_html__("Premium users can block terms. %s and start blocking your site's negative terms from being shown under Populat terms", 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade here",'ric').'</a>');
            $fields['popularterms'][]=$f;


       
        
            return $fields;
        });



        
        \add_action('ric_tab_content_after_button_popularterms',function(){
                

            $args=[];
       
            $args['limit'] = 30;
            $args['orderby'] = 'count';
            $args['order'] = 'DESC';
            //$args['fields'] ='term';
            $lists = \Ric\Db::select('reports_terms',$args);
 
            
            ?><div class="popularterms_list">
                <h2><?php echo __('Monitor and review this feature to delete unwanted terms to being shown as popular terms.','ric'); ?></h2>
                <?php
                if(!empty($lists)){
                    echo '<table class="ric_table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<td>#</td>';
                    echo '<td>'.__('Term','ric').'</td>';
                    echo '<td>'.__('Count','ric').'</td>';
                    echo '<td>'.__('Delete','ric').'</td>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    $nonce= wp_create_nonce('ric_nonce');

                    foreach($lists as $li){
                        echo '<tr data-id="'.$li->id.'">';
                        echo '<td>'.$li->id.'</td>';
                        echo '<td>'.$li->term.'</td>';
                        echo '<td>'.$li->count.'</td>';

                        $deleteLink = '<a href="#"  data-txt="'.esc_attr('Upgrade to premium in order to delete this term and expose visitors for existing items on your site.').'" class="ric_upgrademodal">'.__('Delete','ric').'</a>';
                        $deleteLink = apply_filters('ric_popularterms_admin_link_delete',$deleteLink,$nonce,$li->id);

                        echo '<td>'.$deleteLink.'</td>';

                     
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';
                }
                ?>
                


                

                </div><?php
        });
 
         


    


    }

 
   


  

}
   

  


 

 