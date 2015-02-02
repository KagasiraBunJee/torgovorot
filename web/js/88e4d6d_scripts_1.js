/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function(){
    
    var search_link = $(".select_rub option:selected").val();
    
    $(".torg_search").attr('action',search_link);
    
    $(".select_rub").change(function(){
        
        search_link = $(".select_rub option:selected").val();
    
        $(".torg_search").attr('action',search_link);
        
    });
    
    $(".menu-select li").click(function(){
        var selected_type_str = $(this).text();
        
        $(".select_rub option").each(function(){
            
            if($(this).text() == selected_type_str)
            {
                $(".torg_search").attr('action',$(this).val());
            }
            
        })
    });
    
    $(".sort").find(".menu-select").find("li").click(function(){
        window.location.href = $(this).attr("data-val");
    });
    
    $(".print").click(function(){
       window.print();
       return false;
    });
    
    $(".vlink").click(function(){
        $(".ph, .link-age").fadeIn();
        $(".back_link").val("http://"+window.location.hostname+$(this).attr("data-val"));
        return false;
    });
    
    
    $(".add_image").click(function(){
            
            var input = '<li><label>Фото:</label><div><input id="form_photo" class="upload_photo" type="file" multiple="multiple" accept="image/*" name="form[photo][]"></div></li>';
        
            $(".add_img").before(input);
    });

    
    
});

function getPhone(url,oid){
        
    $.get(url,               
                {id: oid }, 
            function(response){
                    if(response.code == 100 && response.success){//dummy check
                      
                      $(".get_prof_number").html(response.number);
                    }
                    

    }, "json");    
}

function getEmail(url,oid){
        
    $.get(url,               
                {id: oid }, 
            function(response){
                    
                    if(response.code == 100 && response.success){//dummy check
                      
                      $(".get_prof_email").html(response.email);
                    }
                    

    }, "json");    
}





