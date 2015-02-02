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
    
    $(".vlink").click(function(e){
        var position = $(this).position();
        $(".link-age").fadeIn();
        var y = e.pageY - 80 - 6;
        $(".link-age").css({ "top" : y });
        $(".link-age").css(
            { "left" : (e.pageX+150) }
            
        );
        
        $(".back_link").val("http://"+window.location.hostname+$(this).attr("data-val"));
        setTimeout(function(){$(".link-age").fadeOut();} , 10000);
        return false;
    });
    
    
    $(".add_image").click(function(){
            
            var input = '<li><label>Фото:</label><div><input id="form_photo" class="upload_photo" type="file" multiple="multiple" accept="image/*" name="form[photo][]"></div></li>';
        
            $(".add_img").before(input);
    });

    $(".seo-link").click(function(){
        
        $("input[name=search_str]").val($(this).text());
        $(".torg_search").submit();
        return false;
    });
    
    $("img.gallery_box").click(function(){
       var key = $(this).attr("key");
       
       $(".gallery_light a").each(function(){
          var current_key = $(this).attr("key");
          if(current_key == key)
          {
              $(this).click();
          }
       });
    });
    
    $(".all-photo").click(function(){
        $(".gallery_light a").each(function(key){
            if(key == 0)
            {
                $(this).click();
            }
        });
    });
    
    var sim_count = $(".relative-vacancy div").children().length;
    if(sim_count < 2)
    {
        $(".relative-vacancy").empty();
    }
    
});

function setNewFav(url, title)
{
    if (window.sidebar) { // Mozilla Firefox Bookmark
        window.sidebar.addPanel(url,title,"");
      } else if(window.external) { // IE Favorite
        window.external.AddFavorite(url,title); }
      else if(window.opera && window.print) { // Opera Hotlist
        this.title=title;
        return true;
      }
      
      return false;
}

function getPhone(url,oid, obj){
        
    $.get(url,               
                {id: oid }, 
            function(response){
                    if(response.code == 100 && response.success){//dummy check
                      
                      $(".get_prof_number").html(response.number);
                    }
                    

    }, "json");
    if(obj != null)
    {
        $(obj).remove();
    }
}

function getEmail(url,oid, obj){
        
    $.get(url,               
                {id: oid }, 
            function(response){
                    
                    if(response.code == 100 && response.success){//dummy check
                      
                      $(".get_prof_email").html(response.email);
                    }
                    

    }, "json");
    if(obj != null)
    {
        $(obj).remove();
    }
}

function setComplaints(url){
    
    $.get(url, function(response){
            var parse = JSON.parse(response);
            if(parse.code == 100 && parse.success){//dummy check
                alert("Жалоба принята");
            }
        }
                
    );
    
    return false;
}

function getAllCatsByKatalog(type, button)
{
    $.get("http://"+window.location.hostname+"/json/getCats/"+type, 
            function(response){
                    
                    if(response.code == 100 && response.success){//dummy check
                      
                      $(".vacancies ul").html(response.cats);
                      
                      if($(".vacancies ul li").size() > 0)
                      {
                          var object = $(".vacancies ul li");
                          var cat_count = object.size();
                          var cat_limit = 12;
                          var counter = 0;
                          
                          var ul_str = "<ul>";
                          
                          object.each(function(){
                              counter++;
                              
                              if(counter > cat_limit)
                              {
                                  ul_str += "<li>"+$(this).html()+"</li>";
                                  $(this).remove();
                              }
                              
                          });
                          ul_str += "</ul>";
                          
                          if(cat_count > cat_limit)
                          {
                              $(".vacancies ul").after(ul_str);
                          }
                          
                      }
                    }
                    

    }, "json");      
    $(button).remove();
    return false;
}