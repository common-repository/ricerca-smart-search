<?php
namespace Ric;

defined( 'ABSPATH' ) || exit;

 
class Db{

    public static function version() {
        global $wpdb;
        if ( empty( $wpdb->is_mysql ) || ! $wpdb->use_mysqli ) {
            return '';
        }
        // phpcs:ignore WordPress.DB.RestrictedFunctions
        $server_info = mysqli_get_server_info( $wpdb->dbh );
        return $server_info;
    }
 
    //security prepare sql query
    public static function prepare($q,$p=[]){
        global $wpdb;
        $r =  $wpdb->prepare($q,$p);
        return $r;
    }

    //build db table name
    public static  function buildTableName($key){
        global $table_prefix;
        $tblname = RIC_TABLE_PREFIX.$key;
        $tablename = $table_prefix . "$tblname";
        return $tablename;
    }
     
    //delete post by custom post type name
    public static function deleteByPostType($table,$unmateched){
        $tablename = Db::buildTableName($table);
        global $wpdb;
        if(empty($unmateched)){
            return ;
        }

        $unmatechedStr = implode(', ', array_fill(0, count($unmateched), '%s'));
       
        $wpdb->get_results(Db::prepare("DELETE FROM ".$tablename." WHERE wp_type IN (".$unmatechedStr.")",$unmateched));   
    }

    //select query
    public static  function select($table,$args){

        $tablename = Db::buildTableName($table);


        $defaults = array(
            'fields'      => '*',
            's'      => false,
            'scolumn'      => 'keywords',
            'exclude'      => [],
            'limit'      => false,
            'lang'          => false,
            'notempty'          => false,
            'biggerthan'          => false,
            'orderby'          => false,
            'order'            => 'ASC',
            'datebetween'   => false,
            'multiplekeys'=>false
        );
    
        $parsed_args = wp_parse_args( $args, $defaults );
        //$args = wp_

        extract($parsed_args);


        //double security check allowed orderby columns
        $allowedOrderBy = ['date','count','title','item_order'];
        if($orderby && !in_array($orderby,$allowedOrderBy)){
            return [];
        }

        //double security check allowed order column
        $allowedOrder = ['ASC','DESC'];
        if($order && !in_array($order,$allowedOrder)){
            return [];
        }
        //double security check allowed search column
        $allowedScolumn = ['title','keywords'];
    
        if($s && $scolumn && !in_array($scolumn,$allowedScolumn)){
            return [];
        }



        $qParams= [];
 

        global $wpdb;

        $q ="SELECT $fields  FROM $tablename ";

        $where=[];
        if($s && !empty($s) ){
            $isWhere=true;
            $qParams[]='%'.$s.'%';
            $where[]="{$scolumn} LIKE '%s'";
        }
        

        //support multilanguage
 
        if($lang!==false){
            $isWhere=true;
            if(empty($lang)){ 
                $where[]="lang IS NULL";
            }else{
                $qParams[]=$lang;
                $where[]="lang = '%s'";
            }
        }



        //exclude items
        if(!empty($exclude)){
            $isWhere=true;
            $excludeValuesStr=[];
            $excludeValues = implode(',',$exclude);
            foreach($exclude as $ex){
                $qParams[]=$ex;
                $excludeValuesStr[]='%d';
            }
            $where[]="wp_id NOT IN (".implode(',',$excludeValuesStr).")";
         }
      
 

        if($datebetween && !empty($datebetween) && isset($datebetween[1])){
            $from = $datebetween[0];
            $to = $datebetween[1];
            $isWhere=true;
            $qParams[]=$from;
            $qParams[]=$to;
            $where[]="(date BETWEEN %d AND %d)";
         }
        if($biggerthan){
            $fieldKey = $biggerthan[0];
            $fieldValue = $biggerthan[1]; 
            $where[]="$fieldKey>=%d";

           
            $qParams[]=$fieldValue;
        }

        if($notempty){ 
            $where[]="$notempty<>''";
        }


        if(!empty($multiplekeys)){
     
            foreach($multiplekeys as $multiplekey){
                if(!empty($multiplekey['key']) &&
                !empty($multiplekey['value']) &&
                !empty($multiplekey['operator'])){

                    $fieldKey = $multiplekey['key'];
                    $fieldValue = $multiplekey['value']; 

                    $op= $multiplekey['operator'];

                    $where[]="$fieldKey$op'%s'";

 
                    $qParams[]=$fieldValue;
                }


            }




        }
 

        if(!empty($where)){


            $q.=" WHERE ".implode(' AND ',$where);

            
            
        }


        if($orderby){
        //    $qParams[]=$orderby;
          //  $qParams[]=$order;

            //$q.=" ORDER BY $orderby $order ";
            $q.=" ORDER BY {$orderby} {$order} ";
        }
 
 
        if($limit){
            $q.=" LIMIT $limit";
        }
 

        if(empty($qParams)){
            return $wpdb->get_results($q);   
        }
 
 
        return $wpdb->get_results( Db::prepare($q,$qParams));   
   
        
    }



    public static  function join($table1,$table2,$args,$key1='wpid',$key2='wp_id'){

        $table1 = Db::buildTableName($table1);
        $table2 = Db::buildTableName($table2);


        $defaults = array(
            'fields' => '*',
            'limit'      => false,
            'bykey'      => false,
            'bykey_value'      => false,
            'multiplekeys'=>false,
            'orderby'          => false,
            'order'            => 'ASC',
            'datebetween'   => false,
        );
    
        $parsed_args = wp_parse_args( $args, $defaults );
        //$args = wp_

        extract($parsed_args);

        $qParams= [];
 

        global $wpdb;

    

        $q = "SELECT $fields FROM $table1 i LEFT JOIN $table2 u ON i.$key1 = u.$key2 " ;

        $where=[];
        if($datebetween && !empty($datebetween) && isset($datebetween[1])){
            $from = $datebetween[0];
            $to = $datebetween[1];
            $isWhere=true;
            $qParams[]=$from;
            $qParams[]=$to;
            $where[]="(date BETWEEN %d AND %d)";
         }
    
    
         if(!empty($multiplekeys)){
     
            foreach($multiplekeys as $multiplekey){
                if(!empty($multiplekey['key']) &&
                !empty($multiplekey['value']) &&
                !empty($multiplekey['operator'])){

                    $fieldKey = $multiplekey['key'];
                    $fieldValue = $multiplekey['value']; 

                    $op= $multiplekey['operator'];

                    $where[]="$fieldKey$op'%s'";

 
                    $qParams[]=$fieldValue;
                }


            }




        }

        if(!empty($where)){


            $q.=" WHERE ".implode(' AND ',$where);

            
            
        }


        if($orderby){
            $qParams[]=$orderby;
            $qParams[]=$order;

            $q.=" ORDER BY %s %s ";
        }
 

        if($limit){
            $q.=" LIMIT $limit";
        }
  
       

        return $wpdb->get_results( Db::prepare($q,$qParams));   
   
        
    }


    //delete by item id
    public static function deleteById($table,$id){
        $tablename = Db::buildTableName($table);
        global $wpdb;
        $q ="DELETE FROM ".$tablename." WHERE id=%d";
        return $wpdb->get_results( Db::prepare($q,$id));   
    }

    public static function deleteByKeyValue($table,$key,$value){
        $tablename = Db::buildTableName($table);
        global $wpdb;
        $q ="DELETE FROM ".$tablename." WHERE $key=%s";
        return $wpdb->get_results( Db::prepare($q,$value));   
    }
    
    
    public static function deleteByDateBefore($table,$time){
        $tablename = Db::buildTableName($table);
        global $wpdb;
        $q ="DELETE FROM ".$tablename." WHERE date<=%d";
        return $wpdb->get_results( Db::prepare($q,$time));   
    }



    public static function getRowByColumn($table,$k,$v){

        $tablename = Db::buildTableName($table);
        global $wpdb;

        $q ="SELECT * FROM ".$tablename." WHERE $k=%s LIMIT 1"; 
        $res = $wpdb->get_results( Db::prepare($q,$v));   
        if(!empty($res)){
            return $res[0];
        }
        return false;
    }
    public static function getAllById($table,$id){

        $tablename = Db::buildTableName($table);

 

        global $wpdb;

        $q ="SELECT * FROM ".$tablename." WHERE id=%d";
        $res = $wpdb->get_results( Db::prepare($q,$id));   
        if(!empty($res)){
            return $res[0];
        }
        return false;
    }

    public static function updateById($table,$id,$args){

        $tablename = Db::buildTableName($table);
        global $wpdb;

         $wpdb->update( $tablename,  $args,  
            array( 
            'id'=>$id
            )
        );  
        
    }

  

    public static function insert($table,$args){

        $tablename = Db::buildTableName($table);
        global $wpdb;

        $a = array_fill(0, count($args), '%s');

        $wpdb->insert( $tablename, $args,
        $a
        );


        return $wpdb->insert_id;
    }



    public static function createTables(){
        
        global $table_prefix, $wpdb;
        
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


             
        
        $wp_track_table = Db::buildTableName('termscorrector');
        if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
        {
            
            $sql = "CREATE TABLE $wp_track_table (
                    id bigint(20) NOT NULL AUTO_INCREMENT,
                    term longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    lang varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    correct_term longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    count bigint(20) NOT NULL,
                    PRIMARY KEY  (id)
            );";
    
            \dbDelta( $sql );
        }



        /*
        * create items table
        */
    
        $wp_track_table = Db::buildTableName('items');
        if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
        {
            $sql = "CREATE TABLE $wp_track_table (
                    id bigint(20) NOT NULL AUTO_INCREMENT,
                    keywords longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    title varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    lang varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    image varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    url longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    addition_data longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    item_order bigint(20) NOT NULL,
                    ishidestock int(1) NOT NULL,
                    ispinned int(1) NOT NULL,
                    wp_id bigint(20) NOT NULL,
                    wp_obj_type varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    wp_type varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    PRIMARY KEY  (id)
            );";
            \dbDelta( $sql );
        }
        
        



        /*
        * create items taxonomies
        */
 
        $wp_track_table = Db::buildTableName('taxonomies');
        if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
        {
            $sql = "CREATE TABLE $wp_track_table (
                    id bigint(20) NOT NULL AUTO_INCREMENT,
                    keywords longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    title varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    lang varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    image varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    url longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    addition_data longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    item_order bigint(20) NOT NULL,
                    ispinned int(1) NOT NULL,
                    wp_id bigint(20) NOT NULL,
                    wp_obj_type varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    wp_type varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    PRIMARY KEY  (id)
            );";
            \dbDelta( $sql );
        }
        






        update_option( "ric_db_version", RIC_DB_VER ,false );



    }


    public static function checkDbUpdates(){

    

        $currentVersion = get_site_option( 'ric_db_version' );
 
        if ( $currentVersion != RIC_DB_VER ) {

    
            global $table_prefix, $wpdb;
        
        
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
         
     

            $isUpdated=false;

 
       

            // add ishidestock columns
            if(version_compare($currentVersion, '1.0.17' ,'<')){
                $tableName = DB::buildTableName('items');
                $existing_columns = $wpdb->get_col("DESC {$tableName}", 0);
                if(!empty($existing_columns) 
                    && !in_array('ishidestock',$existing_columns)){
                    $wpdb->query("ALTER TABLE $tableName ADD ishidestock INT(1) NOT NULL DEFAULT 0");
                    $isUpdated=true;
                }
                
            }
           
            // add ishidestock columns
            if(version_compare($currentVersion, '1.0.17' ,'<')){

                $tableName = DB::buildTableName('items');
                $wpdb->query("ALTER TABLE $tableName MODIFY url LONGTEXT");


                $tableName = DB::buildTableName('taxonomies');
                $wpdb->query("ALTER TABLE $tableName MODIFY url LONGTEXT");
                $isUpdated=true;
               
                
            }


            // remove term corrector to free version
            if(version_compare($currentVersion, '1.0.19' ,'<')){

                $wp_track_table = Db::buildTableName('termscorrector');
                if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
                {
                    
                    $sql = "CREATE TABLE $wp_track_table (
                            id bigint(20) NOT NULL AUTO_INCREMENT,
                            term longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                            correct_term longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                            count bigint(20) NOT NULL,
                            PRIMARY KEY  (id)
                    );";
            
                    \dbDelta( $sql );
                }
               
                
            }
           
            if(version_compare($currentVersion, '1.0.20' ,'<')){


                //add items lang column
                $tableName = DB::buildTableName('items');
                $column = $wpdb->get_results( $wpdb->prepare(
                    "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",
                    DB_NAME, $tableName, 'lang'
                ) );
                if (empty( $column ) ) {
                    $wpdb->query("ALTER TABLE $tableName ADD lang varchar(255) NULL");
                }

                //add taxonomies lang column
                $tableName = DB::buildTableName('taxonomies');
                $column = $wpdb->get_results( $wpdb->prepare(
                    "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",
                    DB_NAME, $tableName, 'lang'
                ) );
                if (empty( $column ) ) {
                    $wpdb->query("ALTER TABLE $tableName ADD lang varchar(255) NULL");
                } 
                //add taxonomies lang column
                $tableName = DB::buildTableName('termscorrector');
                $column = $wpdb->get_results( $wpdb->prepare(
                    "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",
                    DB_NAME, $tableName, 'lang'
                ) );
                if (empty( $column ) ) {
                    $wpdb->query("ALTER TABLE $tableName ADD lang varchar(255) NULL");
                } 
                $isUpdated=true;
               
                
            }
       


            if($isUpdated){
                    update_option( "ric_db_version", RIC_DB_VER ,false );
            }
           
        }
    }
 
    public static function dropTables(){
        global $table_prefix, $wpdb;


        $tables = [
            'taxonomies',
            'termscorrector',
            'items'
        ];

        /* clear tables */
        foreach($tables as $table){

            $wp_track_table = Db::buildTableName($table);
 
            $sql = "DROP TABLE IF EXISTS $wp_track_table";
            $wpdb->query($sql);
        }

     
    }
}