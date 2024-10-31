<?php
namespace Ric;

defined( 'ABSPATH' ) || exit;

PrintFooter::init();

class PrintFooter{

    public static function init(){

        
                 /*
                * Hook footer as down we can
                */
                \add_action( 'wp_footer', function(){
                    $config= Func::getPublicSettings();
 
                    $front=[];
                    
                    if($config['fetch_items_on']=='onload'){
                        $front=Func::getItems();
                    }
 

                    
                        /*
                            let ric_Data=<?php echo \json_encode($front); 
                        */
                        $frontTerms=Func::getItemsTerms();
                        ?><script type="text/javascript">document.addEventListener('DOMContentLoaded', function () {

                            document.documentElement.style.setProperty('--ricph', `${window.innerHeight}px`);
                            document.documentElement.style.setProperty('--ricphwide', `${window.innerHeight-50}px`); 
                        
                        });
                        let ric_DataKey='ric_f8j34j';
                        let ric_Data=<?php echo \json_encode($front);  ?>;
                        let ric_DataTerms=<?php echo \json_encode($frontTerms); ?>;
                        let ric_Config = <?php echo \json_encode($config); ?>;
                        <?php
                        \do_action( 'ric_add_footer_js_variables');
                 
                    ?> 
        
                </script><?php

                $opt= Func::getOption('general');
                if(!empty($opt['layout']) && $opt['layout']=='wide'){
                    $kns= [  'def','1400', '1280','980','750'  ];
                    $css='';
                    foreach($kns as $kn){
                        $key  = 'layout_wide_'.$kn;
                        $unit  = 'layout_wide_'.$kn.'_unit';
                        if(!empty($opt[$key]) && !empty($opt[$unit])){
                            $unitVal = $opt[$unit]=='percent' ? '%' : $opt[$unit];
                            $val = $opt[$key];
                            if($kn=='def'){
                                $css.='.ric_form_layout_wide .ric_drop_box_outer{width:'.$val.$unitVal.';} ';
                            }else{
                                $css.=' @media (max-width:'.$kn.'px){
                                    .ric_form_layout_wide .ric_drop_box_outer{width:'.$val.$unitVal.';}
                                } ';
                            }
                        }
                    }
                    if(!empty($css)){
                        echo '<style>'.$css.'</style>';
                    }
                }

                \do_action( 'ric_footer_html');


                },200);
      
      

    }

}

  
