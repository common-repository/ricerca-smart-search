 
 
let ric_LastTerm=false;
let autoInput = 'input[name="s"]:not(.adminbar-input)';


/* on click at shortcode button */
jQuery(document).on('click','.ric_toggle_search',function(){
    let autoInput = 'input[name="s"]:not(.adminbar-input)';
  
    jQuery(autoInput).trigger('focus');
  
  });
  /* on click at shortcode button */
  
  


/*
* close menu on click out side
*
*/
jQuery(document).click(function() {
    let container = jQuery(".ric_form_wrapper");
    if (!container.hasClass('ric_modal_body') && container.length>0 && !container.is(event.target) && !container.has(event.target).length) {
        ric_close_modal();
    }
});


/*
* get strings
*/
function ric_GetString(key){
    return ric_Config.strings[key];
}

/*
 * run on ready
 * modal mode prepare
 * 
 * @type type
 */
jQuery(function() {
    /*
    * prepare modal to open later
    */
    if(typeof ric_Config!=='undefined' && ric_Config.layout=='modal' ){
        jQuery('body').append(ric_Config.modalHtml);
        
    }

 
    if(typeof ric_Config!=='undefined'
        && ric_Config.fetch_items_on=='onengagement'
        && ric_Data.length==0){
            document.body.addEventListener('click', ricLoadItems, true); 
            document.addEventListener('scroll', ricLoadItems, true);
            document.addEventListener('mousemove', ricLoadItems, true);
            document.addEventListener('touchstart', ricLoadItems, true);
            document.addEventListener('wheel', ricLoadItems, true);
            document.addEventListener('keydown', ricLoadItems, true);
    }else if (typeof ric_Config!=='undefined'
    && ric_Config.fetch_items_on=='afterload'
    && ric_Data.length==0){
        ricLoadItems();
}
    
 
    
 
    
});

let isRicItemsLoaded=false;
let isTryingRicItemsLoaded=false;
function ricLoadItems(){
 
    if(isRicItemsLoaded || isTryingRicItemsLoaded){
        return ;
    }

    isTryingRicItemsLoaded=true;
 

    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: ric_Config.ajaxUrl,
        data:{
            'action' : 'ric_load_items', 
            'nonce' : ric_Config['nonce'],
            'r' : Math.floor(Math.random() * 101)
        },
        success: function(r) {
            if(!r.error){
                ric_Config['nonce'] = r.nonce;

                 
                
                ric_Data = r.items;


                isRicItemsLoaded=true;
                isTryingRicItemsLoaded=false;

                
                document.removeEventListener('scroll', ricLoadItems, true);
                document.removeEventListener('mousemove', ricLoadItems, true);
                document.removeEventListener('touchstart', ricLoadItems, true);
                document.removeEventListener('wheel', ricLoadItems, true);
                document.removeEventListener('keydown', ricLoadItems, true);

            }
        },error : function(param1, param2) {
            
            isTryingRicItemsLoaded=false;
        } ,
        timeout : 120000
        
    });

}
 
 
 
jQuery(document).on('click','.js_ric_add_to_cart',function(e){
    e.preventDefault();

    let t = jQuery(this);

    let id=jQuery(this).data('id');
    let title=jQuery(this).data('title');
    let term=jQuery(this).data('term');
    jQuery(this).data('id');
     
 
    jQuery('<div data-id="'+id+'" class="ricloader" />').insertAfter(this);

    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: ric_Config.ajaxUrl,
        data:{
            'action' : 'ric_wc_add_to_cart',
            'nonce' : ric_Config.nonce,
            'id' : id,
            'r' : Math.floor(Math.random() * 101)
        },
        success: function(r) {

            jQuery('.ricloader[data-id="'+id+'"]').remove();

            if(!r.error){
              
 
                //woo trigger refresh carts and minicart
                jQuery(document.body).trigger("added_to_cart");
                jQuery(document.body).trigger("removed_from_cart");
                jQuery(document.body).trigger( 'wc_fragment_refresh' );



       
                var event = new CustomEvent("ricAddProductToCart", {
                    detail: {
                        id: id,
                        title: title,
                        term: ric_LastTerm
                    }
                });
                document.dispatchEvent(event);

             


                


       /*

                if(typeof refreshminicartcountNumber!=='undefined'){
                    refreshminicartcountNumber();
                }

 
           */
               t.replaceWith('<div class="ric_added_to_cart">'+ric_GetString('Added to cart')+'</div>');
            }


          
        }
    });

});


/*
 * detect change on input field
 */
jQuery(document).on('click','.ric_modal_toggle',function(e){
    e.preventDefault();
 ric_close_modal();
});
jQuery(document).keyup(function(e) {
    if (e.keyCode == 27) {
      ric_close_modal();
   }
});
function ric_close_modal(){

    autoInput = 'input[name="s"]:not(.adminbar-input)';
  
    if((ric_Config.layout=='simple' || ric_Config.layout=='wide') && jQuery('.ric_drop_box_outer').length>0 ){ 
        jQuery('.ric_drop_box_outer').remove();
    }else{
        jQuery('.ric_modal').removeClass('active');
        jQuery('body').removeClass('ric_modal_active');
    }
    

    var event = new CustomEvent("ricModalClosed", {
        detail: {
            layout: ric_Config.layout
        }
    });
    document.dispatchEvent(event);


}

/*
on focus native wp search field
*/

jQuery(document).on('input',autoInput,ric_start);

 
jQuery(function(){

    if(ric_Config.selector_run && jQuery(ric_Config.selector_run).length>0){
        jQuery(document).on('click',ric_Config.selector_run,function(e){
            e.preventDefault();

 
            if(typeof ric_Config==='undefined' ){
                return ;   
            }
            ricRunModal('');
         });
    }

    if(jQuery(autoInput).length>0){
        jQuery(autoInput).each(function(){
            jQuery(this).parents('form').on('submit',function(e){
                e.preventDefault();
                jQuery(this).find(autoInput).trigger('change');
            });
        });
    }
});

/*
on click any custom button
*/

jQuery(document).on('focus',autoInput,function(){
    let term = jQuery(this).val();

    if(typeof ric_Config==='undefined' ){
        return ;   
    }

    ricRunModal(term);
   //ric_LastTerm =term;

});


function ricRunModal(term){
    
    if(ric_Config.layout=='modal' && !jQuery('.ric_modal').hasClass('active')){
 

        jQuery('.ric_modal').addClass('active');
        jQuery('body').addClass('ric_modal_active');

        
        var event = new CustomEvent("ricModalLoaded", {
            detail: {
                term: term
            }
        });
        document.dispatchEvent(event);




        let currentVal= jQuery(autoInput).val();
        autoInput = '.ric_modal input[name="s"]';
        input = jQuery('.ric_modal input[name="s"]');
        if(currentVal.length>0){
            input.val(currentVal);
        }



        setTimeout(function(){
            jQuery('.ric_modal input[name="s"]').focus();
        },300);
        


    }
}
 
let ricInputBck=false;
function ric_start(){


   
    /*
     * is somthing wrong?
     */

    if(typeof ric_Config==='undefined' ){
        return ;   
    }


 
    if(typeof ric_Data==='undefined' ){
        return ;   
    }
  
    
    
    /*
     * verift status is active
     */
    if(ric_Config.active===false ){
        return ;   
    }




    let showCategories = ric_Config.show_categories;
   
    //improve loading in case data still not available
    let input=false;
    if(ric_Data.length===0 ){
        if(!ricInputBck){  
            ricInputBck=jQuery(this);
        }
        setTimeout(ric_start, 1000);
        input=ricInputBck;
    }else if(ricInputBck){
         input=ricInputBck;
    }else{
        input=jQuery(this);
    }
    /*
     * modal mode switch to 
     */


    
    let form = input.parents('form');
    let formParent = form.parent();

    // check value length
     
    let term = input.val();

 

    //blocked terms injections

 
    term = term.replace(/(<([^>]+)>)/ig, "");
    term = term.trim();
    if(term.includes('alert(')){
        return ;
    }
    if(term.includes('script(')){
        return ;
    }
    if(term.includes('<style')){
        return ;
    }

 


    if(ric_Config.layout=='modal' ){

    

        if(showCategories){
            jQuery('.ric_modal').addClass('ric_modal_category');
       } 

    }

 

 
 
 
 
    if(term.length==0){

        if(ric_Config.layout!=='modal' ){
            formParent.find('.ric_drop_box_outer').remove();
        }

        return ;
    }
 
    if(term===ric_LastTerm && form.find('.ric_drop_box').length>0){
        return ;
    }
   
   ric_LastTerm =term;
   
 
   formParent.addClass('ric_form_wrapper');
   formParent.addClass('ric_form_layout_'+ric_Config.layout);
  
   
 

   if(ric_Config.layout=='wide' ){
            
        var event = new CustomEvent("ricWideLoaded", {
            detail: {
                term: term
            }
        });
        document.dispatchEvent(event);

    } 
 
    
    
    
    // append pinned results
    let found=0;
    let foundTerms=0; 
    let results=[];
    let resultsTerms=[];

 


    
 
    // append results as  posts
  


    
    ric_Data.map(function (ricDataItem){
  
        
        let arraycontainsturtles = (ricDataItem.keywords.indexOf(term) > -1);
        if(!arraycontainsturtles){
            arraycontainsturtles = (ricDataItem.keywords.indexOf(term.toUpperCase()) > -1);
        }
        if(!arraycontainsturtles){
            arraycontainsturtles = (ricDataItem.keywords.indexOf(term.toLowerCase()) > -1);
        }

        //if(ricDataItem.obj_type=='post' && arraycontainsturtles && found<ric_Config.results_to_show){
        if(  
            ricDataItem.ispinned==1 &&
            arraycontainsturtles && 
            results.length<ric_Config.results_to_show){
            results.push(ricDataItem);
         }
    },this);

    
    ric_Data.map(function (ricDataItem){
  
        
        let arraycontainsturtles = (ricDataItem.keywords.indexOf(term) > -1);
        if(!arraycontainsturtles){
            arraycontainsturtles = (ricDataItem.keywords.indexOf(term.toUpperCase()) > -1);
        }
        if(!arraycontainsturtles){
            arraycontainsturtles = (ricDataItem.keywords.indexOf(term.toLowerCase()) > -1);
        }

        //if(ricDataItem.obj_type=='post' && arraycontainsturtles && found<ric_Config.results_to_show){
        if(  
            ricDataItem.ispinned!=1 &&
            arraycontainsturtles && 
            results.length<ric_Config.results_to_show){
            results.push(ricDataItem);
         }
    },this);

    found = results.length;

 
    // append results as terms 
    if(showCategories){

        ric_DataTerms.map(function (ricDataItem){
            let arraycontainsturtles = (ricDataItem.keywords.indexOf(term) > -1);
            if(!arraycontainsturtles){
                arraycontainsturtles = (ricDataItem.keywords.indexOf(term.toUpperCase()) > -1);
            }
            if(!arraycontainsturtles){
                arraycontainsturtles = (ricDataItem.keywords.indexOf(term.toLowerCase()) > -1);
            }

            if(ricDataItem.obj_type=='term'
            && arraycontainsturtles && 
            resultsTerms.length<ric_Config.show_categories_limit){
                resultsTerms.push(ricDataItem); 
            }

        },this);

        
        foundTerms = resultsTerms.length;
 
    }



          
       

    let isNotFound = false;
    let isNotFoundCat = false;
       
        
        // draw results html
       html='';
       html+='<div class="ric_drop_box_in '+(ric_Config.show_categories ? 'ric_categories_allowed' : 'ric_categories_disabled')+'">';
            html+='<div class="ric_drop_box_col1">';
            html+='<div class="ric_drop_box_header"></div>';
            
            html+='<div class="ric_drop_box_col1_title ric_drop_box_col1_title_results">'+ric_GetString('Results')+'</div>';
                html+='<div class="ric-scroll-pane">';
                if(results.length>0){
                    html+='<ul class="ric_items_list">';
                    for(let i=0;i<results.length;i++){
                        let resultItem=results[i];


                        html+=resultItem.html;
 
                    }
                    html+='</ul>';

                }else{


                     
                    
                   //  html+='<h2>'+ric_GetString('No results found')+'</h2>';
                   //  html+='<div class="ric_notfound_placeholder"></div>';
                     isNotFound = true;
                    
 
                     
                }
                html+='</div>';
            html+='</div>';

            if(showCategories){
            
                    html+='<div class="ric_drop_box_col2">';
                        html+='<div class="ric_drop_box_col1_title">'+ric_GetString('Categories results')+':</div>';
                        if(resultsTerms.length>0){
                        html+='<ul>';
                        for(let i=0;i<resultsTerms.length;i++){
                            let resultItem=resultsTerms[i];
                            html+='<li data-id="'+resultItem.id+'">';
                                html+='<a  '+(ric_Config.newtab ? 'target="_blank"' : '')+'  class="rictrack"   data-type="terms" data-title="'+resultItem.title+'" data-term="'+term+'"   href="'+resultItem.url+'">';
                                    html+='<span>'+resultItem.title+'</span>';
                                html+='</a>';
                            html+='</li>';
                        }
                        html+='</ul>';
                    }else{
                        html+='<h2>'+ric_GetString('No results found')+'</h2>';
                        
                        

                        isNotFoundCat = true;

               

                   
                        
                    }
                    html+='</div>';

                }
            
        html+='</div>';
       

        if((ric_Config.layout=='simple' || ric_Config.layout=='wide') && formParent.find('.ric_drop_box_outer').length==0 ){ 
            formParent.append(ric_Config.simpleHtml);


            const h= formParent.find('form').height();
            formParent.find('.ric_drop_box_outer').css({'top' : h+'px'});

        }
       
       
        formParent.find('.ric_drop_box').html(html);


        if(isNotFound && ric_Data.length===0){

            jQuery('.ric_drop_box_col1_title_results').show();
            jQuery('.ric_drop_box_col1_title_results').html('<span class="ric_notfound_span">'+ric_GetString('Loading data please wait')+'</span>');



        }else if(isNotFound){
            jQuery('.ric_drop_box_col1_title_results').show();
            jQuery('.ric_drop_box_col1_title_results').append('<span class="ric_notfound_span">'+ric_GetString('No results found')+'</span>');
        }
          
         
        // show search url as a footer
        if( /*(ric_Config.layout=='simple' || ric_Config.layout=='wide')  && */ric_Config.redirect_to_search){ 
                let footer='<div class="ric_drop_box_footer_col">';
                footer+='<a class="rictrack" '+(ric_Config.newtab ? 'target="_blank"' : '')+'   data-type="moreresults" data-title="more results url" data-term="'+term+'" href="'+(ric_Config.searchUrl+term)+'">'+ric_GetString('msg1')+': '+term+'</a>';
                footer+='</div>';

                formParent.find('.ric_drop_box_footer').html(footer);
        }
        
         

        
        /*
         * results scrolling
         */   
        let w=jQuery(window).width();
       /* if(w>768 &&  ric_Config.layout!='simple' ){*/
        if(w>768  ){
            jQuery('.ric-scroll-pane').jScrollPane({
                autoReinitialise  : true
            });
        }

 
        if(isNotFound  || isNotFoundCat){
            
            var event = new CustomEvent("ricTermNotFound", {
                detail: {
                    term: ric_LastTerm
                }
            });
            document.dispatchEvent(event);
 
        }
        
        if(results.length>0 && ric_LastTerm.length>0){

            var event = new CustomEvent("ricTermFound", {
                detail: {
                    term: ric_LastTerm
                }
            });
            document.dispatchEvent(event);
        }



        var event = new CustomEvent("ricFinishLoaded", {
            detail: {
                term: ric_LastTerm
            }
        });
        document.dispatchEvent(event);

     
}



/*
 * trigger term click to value
 * @type type
 */
jQuery(document).on('click','.ricToggleTerm',function(e){
    e.preventDefault();
    let elm=jQuery(this);
    let root=elm.parents('.ric_form_wrapper');
    let text=elm.text();

    let sTarget = jQuery('.ric_modal input[name="s"]');
    if(root.length>0){
        sTarget = root.find('input[name="s"]');
    }



 
    sTarget.val(text);

    setTimeout(function(){
        sTarget.trigger('input');
    },100);

    setTimeout(function(){
        var v = sTarget.val();
        if(v!=text){
            sTarget.val(text);
            sTarget.trigger('input');
        }
         
        
    if(jQuery('.ric_notfound_placeholder button').length>0){
        jQuery('.ric_notfound_placeholder').html('');
    }

    
    },200,text);
     

    if(jQuery('.ric_user_history.active').length>0){
        jQuery('.ric_user_history').removeClass('active');
    }
    
 
    
});


/*
* termscorrector start
*/
let lastNotFoundPushedTerm = new Date().getTime();
document.addEventListener("ricTermNotFound",function(e){

    let detail = e.detail;
  
    let term=detail.term;
 
      if(!term || term.length==0 ){
          return false;
      }

      if(lastNotFoundPushedTerm+5000 > new Date().getTime()){
          return false;
      }

      lastNotFoundPushedTerm = new Date().getTime();

      term = term.trim();


       


      if(jQuery('.ric_modal.active').length>0){
        setTimeout(function(){
          let term = jQuery('.ric_modal.active').find('input[name="s"]').val();
          pushTermNotfoundF(term);
  
        },3000);
      }else{
        pushTermNotfoundF(term);
      }

    
  
});

function pushTermNotfoundF(term){
  let data = {};
  data['action'] = 'ric_register_term_corrector';
  data['nonce'] = ric_Config.nonce;
  data['term'] = term;
  jQuery.ajax({
      type: 'POST',
      dataType: 'html',
      url: ric_Config.ajaxUrl,
      data: data,
      success: function(r) { 
      },
      error : function(param1, param2) {
          
} ,
timeout : 20000
  });   
      
}

/*
* termscorrector end
*/

/* user history start */
let ricUhKey = 'fiujxs';
document.addEventListener("ricModalLoaded",ricLoadHistory);

function ricLoadHistory(){
    if(!ric_Config.show_user_history){
        return ;
    }

    
    jQuery('.ric_modal_form').find('.ric_history_toggle').remove();
    jQuery('.ric_modal_form').find('.ric_user_history').remove();
 


    let uLog =localStorage.getItem(ricUhKey);
    if(typeof uLog!='undefined' && uLog){
    
        uLog = JSON.parse(uLog);
        if(uLog.length>0){
      

     
  
     
        let hIcon = '<a class="ric_history_toggle" href="#" role="button" ><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M75 75L41 41C25.9 25.9 0 36.6 0 57.9V168c0 13.3 10.7 24 24 24H134.1c21.4 0 32.1-25.9 17-41l-30.8-30.8C155 85.5 203 64 256 64c106 0 192 86 192 192s-86 192-192 192c-40.8 0-78.6-12.7-109.7-34.4c-14.5-10.1-34.4-6.6-44.6 7.9s-6.6 34.4 7.9 44.6C151.2 495 201.7 512 256 512c141.4 0 256-114.6 256-256S397.4 0 256 0C185.3 0 121.3 28.7 75 75zm181 53c-13.3 0-24 10.7-24 24V256c0 6.4 2.5 12.5 7 17l72 72c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-65-65V152c0-13.3-10.7-24-24-24z"/></svg></a>';


 
        let html = '<div class="ric_user_history">';
            html+= '<div  class="ric_user_history_in">';
            uLog.forEach((element,index) => {
 
                html+= '<div class="ric_user_history_item">';
                    html+= '<a  href="#" role="button" class="ricToggleTerm">'+element+'</a>';
                    html+= '<a href="#" role="button" data-e="'+index+'" class="ric_clear_history"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="svg-inline--fa fa-times fa-w-11" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg></a>';
                html+= '</div>';
                    
                });
            html+= '</div>';
        html+= '</div>';



        jQuery('.ric_modal_form').append(html);
        jQuery('.ric_modal_form').append(hIcon);
            }

    }
}
jQuery(document).on('click','.ric_history_toggle',function(e){
    e.preventDefault();
    if(!jQuery('.ric_user_history').hasClass('active')){
        jQuery('.ric_user_history').addClass('active');
    }else{
        jQuery('.ric_user_history').removeClass('active');
    }
});
jQuery(document).on('click','.ric_clear_history',function(e){
    e.preventDefault();
    let ind=jQuery(this).data('e');
    let uLog =localStorage.getItem(ricUhKey);
    if(typeof uLog!='undefined' && typeof uLog[ind]!=='undefined'){
        uLog = JSON.parse(uLog);

         uLog.splice(ind, 1);
     

        
       uLog= JSON.stringify(uLog);
       localStorage.setItem(ricUhKey, uLog);


       jQuery('.ric_user_history').removeClass('active');

       ricLoadHistory();
    }
});
jQuery(document).on('click','.rictrack',function(e){
    if(!ric_Config.show_user_history){
        return ;
    }


    let uLog =localStorage.getItem(ricUhKey);
    if(!uLog){
        uLog=[];
    }else{
        uLog = JSON.parse(uLog);
    }


     


    let term=false;
    if(jQuery('.ric_modal input[name="s"]').length){
       term=jQuery('.ric_modal input[name="s"]').val();
    }
    if(jQuery('.ric_form_layout_simple input[name="s"]').length){
       term=jQuery('.ric_form_layout_simple input[name="s"]').val();
    }
    if(jQuery('.ric_form_layout_wide input[name="s"]').length){
       term=jQuery('.ric_form_layout_wide input[name="s"]').val();
    }
 
    if(term && !uLog.includes(term)){
        uLog.push(term);

        if(uLog.length>5){
            uLog = uLog.slice(-5);
        }
        uLog= JSON.stringify(uLog);
        localStorage.setItem(ricUhKey, uLog);
    }
  
});
/* user history end */


 
 /* plugins */
 /*
  * mouse wheel 
  */
 !function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?module.exports=e:e(jQuery)}(function(r){var f,d,e=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],t="onwheel"in document||9<=document.documentMode?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],c=Array.prototype.slice;if(r.event.fixHooks)for(var n=e.length;n;)r.event.fixHooks[e[--n]]=r.event.mouseHooks;var m=r.event.special.mousewheel={version:"3.1.12",setup:function(){if(this.addEventListener)for(var e=t.length;e;)this.addEventListener(t[--e],i,!1);else this.onmousewheel=i;r.data(this,"mousewheel-line-height",m.getLineHeight(this)),r.data(this,"mousewheel-page-height",m.getPageHeight(this))},teardown:function(){if(this.removeEventListener)for(var e=t.length;e;)this.removeEventListener(t[--e],i,!1);else this.onmousewheel=null;r.removeData(this,"mousewheel-line-height"),r.removeData(this,"mousewheel-page-height")},getLineHeight:function(e){var t=r(e),e=t["offsetParent"in r.fn?"offsetParent":"parent"]();return e.length||(e=r("body")),parseInt(e.css("fontSize"),10)||parseInt(t.css("fontSize"),10)||16},getPageHeight:function(e){return r(e).height()},settings:{adjustOldDeltas:!0,normalizeOffset:!0}};function i(e){var t,n=e||window.event,i=c.call(arguments,1),o=0,l=0,s=0,h=0,a=0,u=0;if(e=r.event.fix(n),e.type="mousewheel","detail"in n&&(s=-1*n.detail),"wheelDelta"in n&&(s=n.wheelDelta),"wheelDeltaY"in n&&(s=n.wheelDeltaY),"wheelDeltaX"in n&&(l=-1*n.wheelDeltaX),"axis"in n&&n.axis===n.HORIZONTAL_AXIS&&(l=-1*s,s=0),o=0===s?l:s,"deltaY"in n&&(o=s=-1*n.deltaY),"deltaX"in n&&(l=n.deltaX,0===s&&(o=-1*l)),0!==s||0!==l)return 1===n.deltaMode?(o*=t=r.data(this,"mousewheel-line-height"),s*=t,l*=t):2===n.deltaMode&&(o*=t=r.data(this,"mousewheel-page-height"),s*=t,l*=t),h=Math.max(Math.abs(s),Math.abs(l)),(!d||h<d)&&w(n,d=h)&&(d/=40),w(n,h)&&(o/=40,l/=40,s/=40),o=Math[1<=o?"floor":"ceil"](o/d),l=Math[1<=l?"floor":"ceil"](l/d),s=Math[1<=s?"floor":"ceil"](s/d),m.settings.normalizeOffset&&this.getBoundingClientRect&&(h=this.getBoundingClientRect(),a=e.clientX-h.left,u=e.clientY-h.top),e.deltaX=l,e.deltaY=s,e.deltaFactor=d,e.offsetX=a,e.offsetY=u,e.deltaMode=0,i.unshift(e,o,l,s),f&&clearTimeout(f),f=setTimeout(g,200),(r.event.dispatch||r.event.handle).apply(this,i)}function g(){d=null}function w(e,t){return m.settings.adjustOldDeltas&&"mousewheel"===e.type&&t%120==0}r.fn.extend({mousewheel:function(e){return e?this.bind("mousewheel",e):this.trigger("mousewheel")},unmousewheel:function(e){return this.unbind("mousewheel",e)}})});
 
 /*
  * jscrollpane
  */
 !function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?module.exports=e(jQuery||require("jquery")):e(jQuery)}(function(be){be.fn.jScrollPane=function(o){function s(w,e){var y,b,k,T,C,S,x,D,B,H,P,z,A,W,Y,M,X,L,R,t,E,I,F,V,q,O,G,N,K,Q,U,jQuery,J,Z,_=this,r=!0,a=!0,l=!1,c=!1,o=w.clone(!1,!1).empty(),ee=!1,te=be.fn.mwheelIntent?"mwheelIntent.jsp":"mousewheel.jsp",oe=function(){0<y.resizeSensorDelay?setTimeout(function(){se(y)},y.resizeSensorDelay):se(y)};function se(e){var t,o,s,i,n,r,a,l,c,p,u,d,f,h,g,j,v=!1,m=!1;if(y=e,void 0===b)n=w.scrollTop(),r=w.scrollLeft(),w.css({overflow:"hidden",padding:0}),k=w.innerWidth()+J,T=w.innerHeight(),w.width(k),b=be('<div class="jspPane" />').css("padding",jQuery).append(w.children()),C=be('<div class="jspContainer" />').css({width:k+"px",height:T+"px"}).append(b).appendTo(w);else{if(w.css("width",""),C.css({width:"auto",height:"auto"}),b.css("position","static"),a=w.innerWidth()+J,l=w.innerHeight(),b.css("position","absolute"),v=y.stickToBottom&&(20<(p=x-T)&&p-we()<10),m=y.stickToRight&&(20<(c=S-k)&&c-me()<10),i=a!==k||l!==T,k=a,T=l,C.css({width:k,height:T}),!i&&Z==S&&b.outerHeight()==x)return void w.width(k);Z=S,b.css("width",""),w.width(k),C.find(">.jspVerticalBar,>.jspHorizontalBar").remove().end()}b.css("overflow","auto"),S=e.contentWidth?e.contentWidth:b[0].scrollWidth,x=b[0].scrollHeight,b.css("overflow",""),D=S/k,H=1<(B=x/T)||y.alwaysShowVScroll,(P=1<D||y.alwaysShowHScroll)||H?(w.addClass("jspScrollable"),(t=y.maintainPosition&&(W||X))&&(o=me(),s=we()),H&&(C.append(be('<div class="jspVerticalBar" />').append(be('<div class="jspCap jspCapTop" />'),be('<div class="jspTrack" />').append(be('<div class="jspDrag" />').append(be('<div class="jspDragTop" />'),be('<div class="jspDragBottom" />'))),be('<div class="jspCap jspCapBottom" />'))),L=C.find(">.jspVerticalBar"),R=L.find(">.jspTrack"),z=R.find(">.jspDrag"),y.showArrows&&(F=be('<a class="jspArrow jspArrowUp" />').on("mousedown.jsp",le(0,-1)).on("click.jsp",ye),V=be('<a class="jspArrow jspArrowDown" />').on("mousedown.jsp",le(0,1)).on("click.jsp",ye),y.arrowScrollOnHover&&(F.on("mouseover.jsp",le(0,-1,F)),V.on("mouseover.jsp",le(0,1,V))),ae(R,y.verticalArrowPositions,F,V)),E=T,C.find(">.jspVerticalBar>.jspCap:visible,>.jspVerticalBar>.jspArrow").each(function(){E-=be(this).outerHeight()}),z.on("mouseenter",function(){z.addClass("jspHover")}).on("mouseleave",function(){z.removeClass("jspHover")}).on("mousedown.jsp",function(e){be("html").on("dragstart.jsp selectstart.jsp",ye),z.addClass("jspActive");var t=e.pageY-z.position().top;return be("html").on("mousemove.jsp",function(e){ue(e.pageY-t,!1)}).on("mouseup.jsp mouseleave.jsp",pe),!1}),ne()),P&&(C.append(be('<div class="jspHorizontalBar" />').append(be('<div class="jspCap jspCapLeft" />'),be('<div class="jspTrack" />').append(be('<div class="jspDrag" />').append(be('<div class="jspDragLeft" />'),be('<div class="jspDragRight" />'))),be('<div class="jspCap jspCapRight" />'))),q=C.find(">.jspHorizontalBar"),O=q.find(">.jspTrack"),Y=O.find(">.jspDrag"),y.showArrows&&(K=be('<a class="jspArrow jspArrowLeft" />').on("mousedown.jsp",le(-1,0)).on("click.jsp",ye),Q=be('<a class="jspArrow jspArrowRight" />').on("mousedown.jsp",le(1,0)).on("click.jsp",ye),y.arrowScrollOnHover&&(K.on("mouseover.jsp",le(-1,0,K)),Q.on("mouseover.jsp",le(1,0,Q))),ae(O,y.horizontalArrowPositions,K,Q)),Y.on("mouseenter",function(){Y.addClass("jspHover")}).on("mouseleave",function(){Y.removeClass("jspHover")}).on("mousedown.jsp",function(e){be("html").on("dragstart.jsp selectstart.jsp",ye),Y.addClass("jspActive");var t=e.pageX-Y.position().left;return be("html").on("mousemove.jsp",function(e){fe(e.pageX-t,!1)}).on("mouseup.jsp mouseleave.jsp",pe),!1}),G=C.innerWidth(),re()),function(){if(P&&H){var e=O.outerHeight(),t=R.outerWidth();E-=e,be(q).find(">.jspCap:visible,>.jspArrow").each(function(){G+=be(this).outerWidth()}),G-=t,T-=t,k-=e,O.parent().append(be('<div class="jspCorner" />').css("width",e+"px")),ne(),re()}P&&b.width(C.outerWidth()-J+"px");x=b.outerHeight(),B=x/T,P&&((N=Math.ceil(1/D*G))>y.horizontalDragMaxWidth?N=y.horizontalDragMaxWidth:N<y.horizontalDragMinWidth&&(N=y.horizontalDragMinWidth),Y.css("width",N+"px"),M=G-N,he(X));H&&((I=Math.ceil(1/B*E))>y.verticalDragMaxHeight?I=y.verticalDragMaxHeight:I<y.verticalDragMinHeight&&(I=y.verticalDragMinHeight),z.css("height",I+"px"),A=E-I,de(W))}(),t&&(je(m?S-k:o,!1),ge(v?x-T:s,!1)),b.find(":input,a").off("focus.jsp").on("focus.jsp",function(e){ve(e.target,!1)}),C.off(te).on(te,function(e,t,o,s){X||(X=0),W||(W=0);var i=X,n=W,r=e.deltaFactor||y.mouseWheelSpeed;return _.scrollBy(o*r,-s*r,!1),i==X&&n==W}),j=!1,C.off("touchstart.jsp touchmove.jsp touchend.jsp click.jsp-touchclick").on("touchstart.jsp",function(e){var t=e.originalEvent.touches[0];u=me(),d=we(),f=t.pageX,h=t.pageY,j=!(g=!1)}).on("touchmove.jsp",function(e){if(j){var t=e.originalEvent.touches[0],o=X,s=W;return _.scrollTo(u+f-t.pageX,d+h-t.pageY),g=g||5<Math.abs(f-t.pageX)||5<Math.abs(h-t.pageY),o==X&&s==W}}).on("touchend.jsp",function(e){j=!1}).on("click.jsp-touchclick",function(e){if(g)return g=!1}),y.enableKeyboardNavigation&&function(){var s,i,n=[];P&&n.push(q[0]);H&&n.push(L[0]);b.on("focus.jsp",function(){w.focus()}),w.attr("tabindex",0).off("keydown.jsp keypress.jsp").on("keydown.jsp",function(e){if(e.target===this||n.length&&be(e.target).closest(n).length){var t=X,o=W;switch(e.keyCode){case 40:case 38:case 34:case 32:case 33:case 39:case 37:s=e.keyCode,r();break;case 35:ge(x-T),s=null;break;case 36:ge(0),s=null}return!(i=e.keyCode==s&&t!=X||o!=W)}}).on("keypress.jsp",function(e){if(e.keyCode==s&&r(),e.target===this||n.length&&be(e.target).closest(n).length)return!i}),y.hideFocus?(w.css("outline","none"),"hideFocus"in C[0]&&w.attr("hideFocus",!0)):(w.css("outline",""),"hideFocus"in C[0]&&w.attr("hideFocus",!1));function r(){var e=X,t=W;switch(s){case 40:_.scrollByY(y.keyboardSpeed,!1);break;case 38:_.scrollByY(-y.keyboardSpeed,!1);break;case 34:case 32:_.scrollByY(T*y.scrollPagePercent,!1);break;case 33:_.scrollByY(-T*y.scrollPagePercent,!1);break;case 39:_.scrollByX(y.keyboardSpeed,!1);break;case 37:_.scrollByX(-y.keyboardSpeed,!1)}return i=e!=X||t!=W}}(),y.clickOnTrack&&function(){ce(),H&&R.on("mousedown.jsp",function(i){if(void 0===i.originalTarget||i.originalTarget==i.currentTarget){var n,r=be(this),e=r.offset(),a=i.pageY-e.top-W,l=!0,c=function(){var e=r.offset(),t=i.pageY-e.top-I/2,o=T*y.scrollPagePercent,s=A*o/(x-T);if(a<0)t<W-s?_.scrollByY(-o):ue(t);else{if(!(0<a))return void p();W+s<t?_.scrollByY(o):ue(t)}n=setTimeout(c,l?y.initialDelay:y.trackClickRepeatFreq),l=!1},p=function(){n&&clearTimeout(n),n=null,be(document).off("mouseup.jsp",p)};return c(),be(document).on("mouseup.jsp",p),!1}});P&&O.on("mousedown.jsp",function(i){if(void 0===i.originalTarget||i.originalTarget==i.currentTarget){var n,r=be(this),e=r.offset(),a=i.pageX-e.left-X,l=!0,c=function(){var e=r.offset(),t=i.pageX-e.left-N/2,o=k*y.scrollPagePercent,s=M*o/(S-k);if(a<0)t<X-s?_.scrollByX(-o):fe(t);else{if(!(0<a))return void p();X+s<t?_.scrollByX(o):fe(t)}n=setTimeout(c,l?y.initialDelay:y.trackClickRepeatFreq),l=!1},p=function(){n&&clearTimeout(n),n=null,be(document).off("mouseup.jsp",p)};return c(),be(document).on("mouseup.jsp",p),!1}})}(),function(){if(location.hash&&1<location.hash.length){var e,t,o=escape(location.hash.substr(1));try{e=be("#"+o+', a[name="'+o+'"]')}catch(e){return}e.length&&b.find(o)&&(0===C.scrollTop()?t=setInterval(function(){0<C.scrollTop()&&(ve(e,!0),be(document).scrollTop(C.position().top),clearInterval(t))},50):(ve(e,!0),be(document).scrollTop(C.position().top)))}}(),y.hijackInternalLinks&&function(){if(be(document.body).data("jspHijack"))return;be(document.body).data("jspHijack",!0),be(document.body).delegate('a[href*="#"]',"click",function(e){var t,o,s,i,n,r=this.href.substr(0,this.href.indexOf("#")),a=location.href;if(-1!==location.href.indexOf("#")&&(a=location.href.substr(0,location.href.indexOf("#"))),r===a){t=escape(this.href.substr(this.href.indexOf("#")+1));try{o=be("#"+t+', a[name="'+t+'"]')}catch(e){return}o.length&&(s=o.closest(".jspScrollable"),s.data("jsp").scrollToElement(o,!0),s[0].scrollIntoView&&(i=be(window).scrollTop(),((n=o.offset().top)<i||n>i+be(window).height())&&s[0].scrollIntoView()),e.preventDefault())}})}()):(w.removeClass("jspScrollable"),b.css({top:0,left:0,width:C.width()-J}),C.off(te),b.find(":input,a").off("focus.jsp"),w.attr("tabindex","-1").removeAttr("tabindex").off("keydown.jsp keypress.jsp"),b.off(".jsp"),ce()),y.resizeSensor||!y.autoReinitialise||U?y.resizeSensor||y.autoReinitialise||!U||clearInterval(U):U=setInterval(function(){se(y)},y.autoReinitialiseDelay),y.resizeSensor&&!ee&&(ie(b,oe),ie(w,oe),ie(w.parent(),oe),window.addEventListener("resize",oe),ee=!0),n&&w.scrollTop(0)&&ge(n,!1),r&&w.scrollLeft(0)&&je(r,!1),w.trigger("jsp-initialised",[P||H])}function ie(e,t){var o,s,i=document.createElement("div"),n=document.createElement("div"),r=document.createElement("div"),a=document.createElement("div"),l=document.createElement("div");i.style.cssText="position: absolute; left: 0; top: 0; right: 0; bottom: 0; overflow: scroll; z-index: -1; visibility: hidden;",n.style.cssText="position: absolute; left: 0; top: 0; right: 0; bottom: 0; overflow: scroll; z-index: -1; visibility: hidden;",a.style.cssText="position: absolute; left: 0; top: 0; right: 0; bottom: 0; overflow: scroll; z-index: -1; visibility: hidden;",r.style.cssText="position: absolute; left: 0; top: 0;",l.style.cssText="position: absolute; left: 0; top: 0; width: 200%; height: 200%;";var c=function(){r.style.width=n.offsetWidth+10+"px",r.style.height=n.offsetHeight+10+"px",n.scrollLeft=n.scrollWidth,n.scrollTop=n.scrollHeight,a.scrollLeft=a.scrollWidth,a.scrollTop=a.scrollHeight,o=e.width(),s=e.height()};n.addEventListener("scroll",function(){(e.width()>o||e.height()>s)&&t.apply(this,[]),c()}.bind(this)),a.addEventListener("scroll",function(){(e.width()<o||e.height()<s)&&t.apply(this,[]),c()}.bind(this)),n.appendChild(r),a.appendChild(l),i.appendChild(n),i.appendChild(a),e.append(i),"static"===window.getComputedStyle(e[0],null).getPropertyValue("position")&&(e[0].style.position="relative"),c()}function ne(){R.height(E+"px"),W=0,t=y.verticalGutter+R.outerWidth(),b.width(k-t-J);try{0===L.position().left&&b.css("margin-left",t+"px")}catch(e){}}function re(){C.find(">.jspHorizontalBar>.jspCap:visible,>.jspHorizontalBar>.jspArrow").each(function(){G-=be(this).outerWidth()}),O.width(G+"px"),X=0}function ae(e,t,o,s){var i,n="before",r="after";"os"==t&&(t=/Mac/.test(navigator.platform)?"after":"split"),t==n?r=t:t==r&&(n=t,i=o,o=s,s=i),e[n](o)[r](s)}function le(e,t,o){return function(){return function(e,t,o,s){o=be(o).addClass("jspActive");var i,n,r=!0,a=function(){0!==e&&_.scrollByX(e*y.arrowButtonSpeed),0!==t&&_.scrollByY(t*y.arrowButtonSpeed),n=setTimeout(a,r?y.initialDelay:y.arrowRepeatFreq),r=!1};a(),i=s?"mouseout.jsp":"mouseup.jsp",(s=s||be("html")).on(i,function(){o.removeClass("jspActive"),n&&clearTimeout(n),n=null,s.off(i)})}(e,t,this,o),this.blur(),!1}}function ce(){O&&O.off("mousedown.jsp"),R&&R.off("mousedown.jsp")}function pe(){be("html").off("dragstart.jsp selectstart.jsp mousemove.jsp mouseup.jsp mouseleave.jsp"),z&&z.removeClass("jspActive"),Y&&Y.removeClass("jspActive")}function ue(e,t){if(H){e<0?e=0:A<e&&(e=A);var o=new be.Event("jsp-will-scroll-y");if(w.trigger(o,[e]),!o.isDefaultPrevented()){var s=e||0,i=0===s,n=s==A,r=-(e/A)*(x-T);void 0===t&&(t=y.animateScroll),t?_.animate(z,"top",e,de,function(){w.trigger("jsp-user-scroll-y",[-r,i,n])}):(z.css("top",e),de(e),w.trigger("jsp-user-scroll-y",[-r,i,n]))}}}function de(e){void 0===e&&(e=z.position().top),C.scrollTop(0);var t,o,s=0===(W=e||0),i=W==A,n=-(e/A)*(x-T);r==s&&l==i||(r=s,l=i,w.trigger("jsp-arrow-change",[r,l,a,c])),t=s,o=i,y.showArrows&&(F[t?"addClass":"removeClass"]("jspDisabled"),V[o?"addClass":"removeClass"]("jspDisabled")),b.css("top",n),w.trigger("jsp-scroll-y",[-n,s,i]).trigger("scroll")}function fe(e,t){if(P){e<0?e=0:M<e&&(e=M);var o=new be.Event("jsp-will-scroll-x");if(w.trigger(o,[e]),!o.isDefaultPrevented()){var s=e||0,i=0===s,n=s==M,r=-(e/M)*(S-k);void 0===t&&(t=y.animateScroll),t?_.animate(Y,"left",e,he,function(){w.trigger("jsp-user-scroll-x",[-r,i,n])}):(Y.css("left",e),he(e),w.trigger("jsp-user-scroll-x",[-r,i,n]))}}}function he(e){void 0===e&&(e=Y.position().left),C.scrollTop(0);var t,o,s=0===(X=e||0),i=X==M,n=-(e/M)*(S-k);a==s&&c==i||(a=s,c=i,w.trigger("jsp-arrow-change",[r,l,a,c])),t=s,o=i,y.showArrows&&(K[t?"addClass":"removeClass"]("jspDisabled"),Q[o?"addClass":"removeClass"]("jspDisabled")),b.css("left",n),w.trigger("jsp-scroll-x",[-n,s,i]).trigger("scroll")}function ge(e,t){ue(e/(x-T)*A,t)}function je(e,t){fe(e/(S-k)*M,t)}function ve(e,t,o){var s,i,n,r,a,l,c,p,u,d=0,f=0;try{s=be(e)}catch(e){return}for(i=s.outerHeight(),n=s.outerWidth(),C.scrollTop(0),C.scrollLeft(0);!s.is(".jspPane");)if(d+=s.position().top,f+=s.position().left,s=s.offsetParent(),/^body|htmljQuery/i.test(s[0].nodeName))return;l=(r=we())+T,d<r||t?p=d-y.horizontalGutter:l<d+i&&(p=d-T+i+y.horizontalGutter),isNaN(p)||ge(p,o),c=(a=me())+k,f<a||t?u=f-y.horizontalGutter:c<f+n&&(u=f-k+n+y.horizontalGutter),isNaN(u)||je(u,o)}function me(){return-b.position().left}function we(){return-b.position().top}function ye(){return!1}"border-box"===w.css("box-sizing")?J=jQuery=0:(jQuery=w.css("paddingTop")+" "+w.css("paddingRight")+" "+w.css("paddingBottom")+" "+w.css("paddingLeft"),J=(parseInt(w.css("paddingLeft"),10)||0)+(parseInt(w.css("paddingRight"),10)||0)),be.extend(_,{reinitialise:function(e){se(e=be.extend({},y,e))},scrollToElement:function(e,t,o){ve(e,t,o)},scrollTo:function(e,t,o){je(e,o),ge(t,o)},scrollToX:function(e,t){je(e,t)},scrollToY:function(e,t){ge(e,t)},scrollToPercentX:function(e,t){je(e*(S-k),t)},scrollToPercentY:function(e,t){ge(e*(x-T),t)},scrollBy:function(e,t,o){_.scrollByX(e,o),_.scrollByY(t,o)},scrollByX:function(e,t){fe((me()+Math[e<0?"floor":"ceil"](e))/(S-k)*M,t)},scrollByY:function(e,t){ue((we()+Math[e<0?"floor":"ceil"](e))/(x-T)*A,t)},positionDragX:function(e,t){fe(e,t)},positionDragY:function(e,t){ue(e,t)},animate:function(e,t,o,s,i){var n={};n[t]=o,e.animate(n,{duration:y.animateDuration,easing:y.animateEase,queue:!1,step:s,complete:i})},getContentPositionX:function(){return me()},getContentPositionY:function(){return we()},getContentWidth:function(){return S},getContentHeight:function(){return x},getPercentScrolledX:function(){return me()/(S-k)},getPercentScrolledY:function(){return we()/(x-T)},getIsScrollableH:function(){return P},getIsScrollableV:function(){return H},getContentPane:function(){return b},scrollToBottom:function(e){ue(A,e)},hijackInternalLinks:be.noop,destroy:function(){var e,t;e=we(),t=me(),w.removeClass("jspScrollable").off(".jsp"),b.off(".jsp"),w.replaceWith(o.append(b.children())),o.scrollTop(e),o.scrollLeft(t),U&&clearInterval(U)}}),se(e)}return o=be.extend({},be.fn.jScrollPane.defaults,o),be.each(["arrowButtonSpeed","trackClickSpeed","keyboardSpeed"],function(){o[this]=o[this]||o.speed}),this.each(function(){var e=be(this),t=e.data("jsp");t?t.reinitialise(o):(be("script",e).filter('[type="text/javascript"],:not([type])').remove(),t=new s(e,o),e.data("jsp",t))})},be.fn.jScrollPane.defaults={showArrows:!1,maintainPosition:!0,stickToBottom:!1,stickToRight:!1,clickOnTrack:!0,autoReinitialise:!1,autoReinitialiseDelay:500,verticalDragMinHeight:0,verticalDragMaxHeight:99999,horizontalDragMinWidth:0,horizontalDragMaxWidth:99999,contentWidth:void 0,animateScroll:!1,animateDuration:300,animateEase:"linear",hijackInternalLinks:!1,verticalGutter:4,horizontalGutter:4,mouseWheelSpeed:3,arrowButtonSpeed:0,arrowRepeatFreq:50,arrowScrollOnHover:!1,trackClickSpeed:0,trackClickRepeatFreq:70,verticalArrowPositions:"split",horizontalArrowPositions:"split",enableKeyboardNavigation:!0,hideFocus:!1,keyboardSpeed:0,initialDelay:300,speed:30,scrollPagePercent:.8,alwaysShowVScroll:!1,alwaysShowHScroll:!1,resizeSensor:!1,resizeSensorDelay:0}});