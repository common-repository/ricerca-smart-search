var $ = jQuery.noConflict();
if(typeof $==='undefined'){
  let $ = jQuery.noConflict();
}  
function loadLightTable(selector){
 

    new LightTable({
        selector : selector,
        search : true,
        pagination : true, 
        theme : 'blue',
    }); 
}
$(function(){
  

  let iindex = $('.items_in_index');
  if(iindex.length){
    let nonce=iindex.data('nonce');
    $.ajax({
      type : 'POST' ,
      url : ajaxurl   ,
                  
      dataType : 'json' ,
      data: {
        'action' : 'ric_get_items',
        'nonce' : nonce,
      }, 
      success : function(r, status, jqFObj) { 
         
        iindex.html(r.html);


      
        loadLightTable('#index_table');


        setTimeout(ricLazyLoad,1000);
 
      } ,
      error : function(param1, param2) {
        iindex.html('');
        } ,
      timeout : 30000
    });
  }
});
 
function ricLazyLoad(){

 

    let rc=0;
    $('.ric_lazy').each(function(){
      if(rc<5){
        const src= $(this).data('src');
        const alt= $(this).data('alt');
        const html = '<img class="ric_lazy_loaded" src="'+src+'"  alt="'+alt+'" />';
        $(this).replaceWith(html); 
  
      }
      rc++;
    });




    if($('.ric_lazy').length>0){
      setTimeout(ricLazyLoad,3000);
    }


    //ric_lazy

}

//ric_lazy


//condition admin field toggle show/hide
$(document).on('change','.ric_condition_field',function(){
  let v =$(this).val();
  let r =$(this).parent();
  if(r.find('.subfields_condition').length>0){
    r.find('.subfields_condition.active').removeClass('active');
    if(r.find('.subfields_condition[data-k="'+v+'"]').length>0){
      r.find('.subfields_condition[data-k="'+v+'"]').addClass('active');
    }
  }
});

/* admin seach items */
let itemsSelectSending=false;
function doItemsSelect(f){
  let itemsSelectRoot = f.parent().parent();
  let itemsFieldParent = f.parent();
  let itemsSelectList = itemsSelectRoot.find('.items_select_list');
  let val = f.val();
  let nonce=itemsSelectRoot.data('nonce');
  let params=itemsSelectRoot.data('params');

  if(val.length>2){
    if(itemsSelectSending){
        return;
    }
    itemsSelectSending=true;
 
 
    
    $('.export_loader_b').remove();

    
    itemsFieldParent.append('<img class="export_loader_b" style="width:30px;height:auto;" src="'+(ricAdminConfig.assets_url)+'images/loader.gif" alt="" />');
  
    $.ajax({
      type : 'POST' ,
      url : ajaxurl   ,
                  
      dataType : 'json' ,
      data: {
        'action' : 'ric_search_items',
        'key' : params.key,
        'source' : params.source,
        'val' : val,
        'nonce' : nonce,
      }, 
      success : function(r, status, jqFObj) { 
        $('.export_loader_b').remove();    
        
        itemsSelectList.html(r.data);

        itemsSelectRoot.find('.items_select_list').addClass('active');
      
        setTimeout(function(){
          itemsSelectSending=false;
          isItemSearchCurrentTyping=false;

        },1000);
                    
      } ,
      error : function(param1, param2) {
        itemsSelectSending=false;
        $('.export_loader_b').remove();
        } ,
      timeout : 30000
    });


  }
 
}

let isItemSearchCurrentTyping=false;
let isItemSearchCurrentTypingLast=new Date().getTime();
$(document).on('input','.items_select_field > input',function(e) {
 
  const f = $(this);


  isItemSearchCurrentTypingLast=new Date().getTime();
  
 setTimeout(function(){
    doItemsSelectInterval(f);
  },500,f);

  
});

function doItemsSelectInterval(f){

 
  if(isItemSearchCurrentTypingLast+1000<=new Date().getTime()){
    doItemsSelect(f);
  }else{
      setTimeout(function(){
        doItemsSelectInterval(f);
      },500,f);
  
   
  }
}

 

$(document).on('focus','.items_select_field > input',function(e) {
 
  const f = $(this);
  let itemsSelectRoot = f.parent().parent();
  if(itemsSelectRoot.find('.items_select_list > a').length){
    itemsSelectRoot.find('.items_select_list').addClass('active');
  }
 
 

});
 
$(document).on('click','.items_select_list > a',function(e) {
 e.preventDefault();

  const f = $(this);
  const id = f.data('wp_id');
  const key = f.data('key');
  const title = f.text();
  const root = $('.ric_items_select[data-key="'+key+'"]');
  const max = parseInt(root.data('max'));

  $('.items_select_list').removeClass('active');


  if(root.find('.items_select_target_item').length>=max){
    f.remove();
    return;
  }

  if(root.find('.items_select_target_item[data-id="'+id+'"]').length){
    f.remove();
    return ;
  }


    let html = '<div data-id="'+id+'" class="items_select_target_item">';
      html+= '<input type="hidden" name="'+key+'[id][]" value="'+id+'" >';
      html+= '<input type="hidden" name="'+key+'[title][]" value="'+title+'" >';
      html+= '<span>'+title+'</span>';
      html+= '<a href="#" role="button" class="items_select_target_remove">x</a>';
    html+= '</div>';


  root.find('.items_select_target').append(html);

 

 
 

});
$(document).on('click','.items_select_target_remove',function(e) {
 e.preventDefault();

  const f = $(this);
  f.parent().remove();
});

/* admin seach items */
/* promotions free */
$(document).on('click','.promotions_free_addpromotion_btn',function(e){
    e.preventDefault();

    let txt =$(this).data('txt');

    if(typeof ricUpgradeModal!='undefined'){

        $('body').append(ricUpgradeModal);

        $('.ric_upgrade_modal_in').append('<p>'+txt+'</p>');
   
       
    }
    
  
});
/* promotions free */


/* upgrademodal */
jQuery(document).keyup(function(e) {
    if (e.keyCode == 27) {
      $('.ric_upgrade_modal').remove();
   }
  });
  
 
$(document).on('click','.ric_upgrademodal',function(e){
    e.preventDefault();

    let txt =$(this).data('txt');

    if(typeof ricUpgradeModal!='undefined'){

        $('body').append(ricUpgradeModal);

        $('.ric_upgrade_modal_in').append('<p>'+txt+'</p>');
   
       
    }
    
  
});
$(document).on('click','.ric_upgrade_modal_close',function(e){
    e.preventDefault();
    $('.ric_upgrade_modal').remove();
  
});
/* upgrademodal */


 
/* modal */
jQuery(document).keyup(function(e) {
    if (e.keyCode == 27) {
      ricCloseAdminModal();
   }
  });
  
  
  $(document).on('click','.ricmodal_close',function(e){
      
    e.preventDefault();
    ricCloseAdminModal();
  });
  
  function ricCloseAdminModal(){
  
    let id =$(this).parents('.ricmodal').attr('id');
    
    var event = new CustomEvent("ricAdminModalClosed", {
      detail: {
        id: '#'+id,
        element: this
      }
    });
    document.dispatchEvent(event);
    
    
    
     
         
        $('.ricmodal').removeClass('active');
  
  
  }
  
  $(document).on('click','.ricmodal_open',function(e){
      e.preventDefault();
      let id =$(this).attr('href');
  
   
  
  var event = new CustomEvent("ricAdminModalOpened", {
    detail: {
      id: id,
      element: this
    }
  });
  document.dispatchEvent(event);
      
      $(id).addClass('active');
  
  
  
  });
  
  /* modal */

/* manual sync modal */
$(document).on('click','.ric_domanual_sync',function(e){
    e.preventDefault();
    if(typeof ricSyncModal!='undefined'){

        $('body').append(ricSyncModal);

        //$('.ric_sync_modal').addClass('active');
  
        $('#ric_sync_data').trigger('submit');
       
    }
    
  
});
/* manual sync modal */

 
$(document).on('click','.ric_index_item_act',function(e){
      e.preventDefault();
      let f = $(this);
      let id = f.attr('data-id');
      let act = f.attr('data-act');
      let nonce=f.attr('data-nonce');
      if(act=='delete' && !confirm(ricAdminConfig.strings.msg4)){
        return;
      }


      f.parent().append('<img class="export_loader_b" style="width:30px;height:auto;" src="'+(ricAdminConfig.assets_url)+'images/loader.gif" alt="" />');
    
      $.ajax({
        type : 'POST' ,
        url : ajaxurl   ,
                    
        dataType : 'json' ,
        data: {
          'action' : 'ric_index_act',
          'id' : id,
          'act' : act,
          'nonce' : nonce,
        },
                
        success : function(r, status, jqFObj) { 
                       
          if(!r.error){
  
    
              if(act=='delete'){
                f.parents('tr').remove();
              }
  
              
          }
  
        
          $('.export_loader_b').remove();
                      
        } ,
        error : function(param1, param2) {
          $('.export_loader_b').remove();
          } ,
        timeout : 20000
      });
  
  
  });

 
$(document).on('click','.ric_export_settings',function(e){
      e.preventDefault();
      let f = $(this);
      let nonce=f.data('nonce');
    
      f.parent().append('<img class="export_loader_b" style="width:30px;height:auto;" src="'+(ricAdminConfig.assets_url)+'images/loader.gif" alt="" />');
    
      $.ajax({
        type : 'POST' ,
        url : ajaxurl   ,
                    
        dataType : 'json' ,
        data: {
          'action' : 'ric_export_settings',
          'nonce' : nonce,
        },
                
        success : function(r, status, jqFObj) { 
                       
          if(!r.error){
  
  
              const e = document.getElementById('json');
  
              const a = document.createElement("a");
              const file = new Blob([JSON.stringify(r.data)], { type:  "text/plain" });
              a.href = URL.createObjectURL(file);
              a.download = 'ric-export.json';
              a.click();
  
  
              $('.export_loader_b').remove();
  
  
   
          }
  
        
         
                      
        } ,
        error : function(param1, param2) {
          } ,
        timeout : 20000
      });
  
  
  });

/* tabs */
$(document).on('click','.ric_tab_toggle',function(e){
    e.preventDefault();

  let t=$(this);
  let id = t.attr('href');

    $('.ric_tab_toggle.current').removeClass('current');
    $('.ric_tab_content_item.current').removeClass('current');
    t.addClass('current');
    $(id).addClass('current');


});
$(document).on('input','.ric_tab_content_item input',ricInputToDisable);
$(document).on('change','.ric_tab_content_item input',ricInputToDisable);
function ricInputToDisable(e){
 
  let t=$(this);
  let v=t.val();

  if(typeof t.data('todisable')!='undefined'){
    let todisable = t.data('todisable');

 

    if(v.length>0){
      if($('select[name="'+todisable+'"]').length){
        let elm =  $('select[name="'+todisable+'"]');
        elm.prop('disabled',true);

        if(elm.hasClass('ric_condition_field')){
          elm.parent().find('select').prop('disabled',true);
          elm.parent().find('input').prop('disabled',true);
        }


      }else if($('input[name="'+todisable+'"]').length){
          $('input[name="'+todisable+'"]').prop('disabled',true);
      }

    }else{
      if($('select[name="'+todisable+'"]').length){

        let elm =  $('select[name="'+todisable+'"]');

          elm.prop('disabled',false);

          
        if(elm.hasClass('ric_condition_field')){
          elm.parent().find('select').prop('disabled',false);
          elm.parent().find('input').prop('disabled',false);
        }



      }else if($('input[name="'+todisable+'"]').length){
          $('input[name="'+todisable+'"]').prop('disabled',false);
      }

    }
    

   
    console.log('todisable',todisable);
  }
 

    

}
/* tabs */


 
 
 

/*
 * 
 * save admin settings
 */
let loadSyncModal=false;
$(document).on('click','.ric_save_and_sync',function(e){
    e.preventDefault();
 
loadSyncModal=true;
    $('.ric_admin_settings').trigger('submit');

});


$(document).on('submit','.ric_admin_settings',function(e){
   e.preventDefault();
   var f=$(this);
   f.find('.ric_err').html('<img src="'+(ricAdminConfig.assets_url)+'images/loader.gif" alt="" />');
	$.ajax({
		type : 'POST' ,
		url : ajaxurl   ,
                
		dataType : 'json' ,
		data: f.serialize(),
            
		success : function(r, status, jqFObj) { 
            f.find('.ric_err').html(r.message);
            
            if(loadSyncModal){
                $('body').append(r.synchtml);
                setTimeout(function(){
                    $('#ric_sync_data').trigger('submit');
                },500);
                loadSyncModal=false;
            }
            
            setTimeout(function(){
                f.find('.ric_err').html('');
            },6000);
                  
		} ,
		error : function(param1, param2) {
  		} ,
		timeout : 20000
	});
    
});
/*
 * do sync data
 */

let cronsListOffset=0;
$(document).on('submit','.ric_sync_data',function(e){
    e.preventDefault();
    
    $(this).hide();
    ricGetUpdateStatus();
    
});


function ricGetUpdateStatus(){
    
    
    $.ajax({
            type : 'POST' ,
            url : ajaxurl   ,
            dataType : 'json' ,
            data: $('.ric_sync_data').serialize(),

            success : function(r, status, jqFObj) {
                if(r.error){
                     $('.ric_sync_status').replaceWith(r.message);
                }else 
                if(!r.isdone){
                    $('.ric_sync_data').find('input[name="offset"]').val(r.nextoffset),
                    setTimeout(ricGetUpdateStatus,400);
                     $('.ric_sync_status_bar').css({'width' : r.percent+'%'});
                } else{

                    cronsListOffset=0;
                    
                    
                     $('.ric_sync_status').replaceWith('<h2 class="ric_msg_finalizing">'+ricAdminConfig.strings.msg2+' 0/'+ricAdminConfig.crons.length+'</h2>');

                     ricGetUpdateStatusCrons();


                     if($('.ric_sync_modal').length>0){
                   //     $('.ric_sync_modal').remove();
                     }

                }
            } ,
            error : function(param1, param2) {
            } ,
            timeout : 30000
    });
}
function ricGetUpdateStatusCrons(){
    
    let crons  = ricAdminConfig.crons;
    
    $.ajax({
            type : 'POST' ,
            url : ajaxurl   ,
            dataType : 'json' ,
            data:{
                'action' : 'ric_do_crons',
                'offset' : cronsListOffset
            },

            success : function(r, status, jqFObj) {


               

                if(!r.isdone){

                    cronsListOffset = r.nextoffset;


                    $('.ric_msg_finalizing').replaceWith('<h2 class="ric_msg_finalizing">'+ricAdminConfig.strings.msg2+' '+cronsListOffset+'/'+ricAdminConfig.crons.length+'</h2>');

                    setTimeout(ricGetUpdateStatusCrons,500);


                }else{

                    $('.ric_msg_finalizing').replaceWith('<h2 class="ric_msg_finalizing">'+ricAdminConfig.strings.msg1+'</h2>');


                    if($('.ric_sync_modal').length>0){
                       $('.ric_sync_modal').remove();
                    }



                }

              
            } ,
            error : function(param1, param2) {
            } ,
            timeout : 30000
    });
}