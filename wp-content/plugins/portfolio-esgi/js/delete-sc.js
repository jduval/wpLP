jQuery(document).ready(function() {
  var $j = jQuery;
  jQuery('.delete-sc').click(function() {

    event.preventDefault();

    var $_this = $j(this),
        url = $_this.attr('data-url');

    $j.post('../wp-content/plugins/portfolio-esgi/delete-sc.php', {url : url}, function(data) {
      $_this.parent().hide('slow');
    });

  });
});
