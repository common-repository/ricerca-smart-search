<?php
namespace Ric\RicPro\Modules;
defined( 'ABSPATH' ) || exit;

 
 

TermsCorrector::init();


class TermsCorrector{


    private static $instance = null;

    public $ver = '1.0.0';
 
    public static function init(){
        if ( null == self::$instance ) {
                    self::$instance = new self;
            }
            return self::$instance;
    }


    public function __construct(){ 
 


        

        \add_action ( 'wp_ajax_ric_register_term_corrector', [$this,'ric_register_term_corrector']);
        \add_action ( 'wp_ajax_nopriv_ric_register_term_corrector',[$this,'ric_register_term_corrector'] );  


      
                
        \add_filter('ric_def_options', function($opt){
            if(empty($opt['termscorrector']['termscorrector_active'])){
                $opt['termscorrector']['termscorrector_active']='no';
            }
            return $opt;
        });
             
             
   
        \add_filter('ric_settings_fields_array', function($fields){
            $f=[];
            $f['key'] = 'termscorrector_active';
            $f['def'] = 'no';
           
            $f['type'] = 'checkbox';
            $f['subtype'] = 'none';
            $f['label'] =  __('Active','ric');
            $f['label_row_after'] = "";
            $f['ispro'] = true;
            $f['label_row_after_pro'] = sprintf( esc_html__('Too much terms to correct?  %s and start fix today.', 'ric' ), '<a href="https://my.myricerca.com/downloads/pro/" target="blank">'.esc_html__("Upgrade to Premium",'ric').'</a>');
            $fields['termscorrector'][]=$f;
            return $fields;
        });


        \add_filter('ric_settings_tabs', function($tabs){
            $tabs[]= [
                'name'=>__('Terms Corrector','ric'),
                'key'=>'termscorrector'
            ];
            return $tabs;
        });



        \add_action('ric_tab_content_after_button_termscorrector',function(){
                

            $args=[];
            $args['order']='DESC';
            $args['orderby']='count';
            $args['limit']=150;
        
            $lists = \Ric\DB::select('termscorrector',$args);

            
            ?><div class="notfoundcorrection_list">
                <h2><?php echo __('Monitor and review this feature to find terms which leads to "Not found results" on your site in order to suggest and show alternative results by fixing terms.','ric'); ?></h2>
                <h3><?php echo __('Make sure to set the right uppercase letters and lowercase letters.','ric'); ?></h3>
                <?php
                if(!empty($lists)){
                    echo '<table class="ric_table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<td>#</td>';
                    echo '<td>'.__('Term','ric').'</td>';
                    echo '<td>'.__('Correct Term','ric').'</td>';
                    echo '<td>'.__('Count','ric').'</td>';
                    echo '<td>'.__('Delete','ric').'</td>';
                    echo '<td>'.__('Fix term','ric').'</td>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    $nonce= wp_create_nonce('ric_nonce');

                    foreach($lists as $li){
                        echo '<tr data-id="'.$li->id.'">';
                        echo '<td>'.$li->id.'</td>';
                        echo '<td>'.$li->term.'</td>';
                        echo '<td>'.$li->correct_term.'</td>';
                        echo '<td>'.$li->count.'</td>';

                        $deleteLink = '<a href="#"  data-txt="'.esc_attr('Upgrade to premium in order to fix this term and expose visitors for existing items on your site.').'" class="ric_upgrademodal">'.__('Delete','ric').'</a>';
                        $deleteLink = apply_filters('ric_termscorrector_admin_link_delete',$deleteLink,$nonce,$li->id);

                        echo '<td>'.$deleteLink.'</td>';

                        $deleteLink = '<a href="#"  data-txt="'.esc_attr('Upgrade to premium in order to fix this term and expose visitors for existing items on your site.').'" class="ric_upgrademodal">'.__('Fix term','ric').'</a>';
                        $deleteLink = apply_filters('ric_termscorrector_admin_link_fix',$deleteLink,$li);

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

  




    
 
    public  function ric_register_term_corrector(){
    
        global $_POST;

   
        /*
        * security verify
        */
        if (!isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'],RIC_NONCE_KEY)) {
            echo json_encode(['error'=>true ]);
           die();
       }
           

        
        if(empty($_POST)){
            echo json_encode(['error'=>true ]);
            die();
        }
            
        /*
        * security verify
        */
        if (!isset( $_POST['term'] ) ) {
            echo json_encode(['error'=>true ]);
            die();
        }
        
        
      
        $t= esc_attr(wp_kses_data( $_POST['term']));
 
        if(mb_strlen($t)<2){
            echo json_encode(['error'=>true ]);
            die();
        }
   
       


        $lang=\Ric\Func::get_locale();


        $livepost = \Ric\Db::getRowByColumn('termscorrector','term',$t);
        if(empty($livepost)){
            \Ric\Db::insert('termscorrector',array(
                'term' => $t, 
                'lang'=>$lang,
                'count' =>1,
                ));
        
        }else{

            $currCount= (int)$livepost->count + 1; 

            $args=[];
            $args['count']=$currCount;
            \Ric\Db::updateById('termscorrector',$livepost->id,$args);


        }

         
    
        echo json_encode(['error'=>false ]);
        die();
    }


 


    
}
   

  


 

 