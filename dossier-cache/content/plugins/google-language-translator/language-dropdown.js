jQuery(document).ready(function(){

jQuery('input[type="radio"][value="all"]').change(function() {
    jQuery('.languages').css('display','none');
    jQuery('label').css('display','none');
  });
jQuery('input[type="radio"][value="specific"]').change(function() {
    jQuery('.languages').css('display','inline');
    jQuery('label').css('display','inline');
  });

});