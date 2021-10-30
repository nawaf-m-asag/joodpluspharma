jQuery(function(){

if($("#type").val()=='products'){
    $('#category_id').parent().hide();
    $('#product_id').parent().show();
} 
else if($("#type").val()=='categories'){
    $('#category_id').parent().show();
    $('#product_id').parent().hide();
}
else{
    $('#category_id').parent().hide();
    $('#product_id').parent().hide();
}

$("#type").on("input", function() {
    if($(this).val()=='products'){
        $('#category_id').parent().hide();
        $('#product_id').parent().show();
    } 
    else if($(this).val()=='categories'){
        $('#category_id').parent().show();
        $('#product_id').parent().hide();
    }
    else{
        $('#category_id').parent().hide();
        $('#product_id').parent().hide();
    }
 });

});
