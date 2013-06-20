var ch1, ch2, tno;

// check configuration - number or picture
if ( jQuery('#mem-table').attr('data-value') === '1' ) {
  var tile = new Array([1, '1'],[2, '2'],[3, '3'],[4, '4'],[5, '1'],[6, '2'],[7, '3'],[8, '4']);
} else if ( jQuery('#mem-table').attr('data-value') === '2' ) {
  // if picture then ajax request to get picture url from db
  var url1, url2, url3, url4, tile;
  var config = 'picture';

  jQuery.post('wp-content/plugins/memory/url.php', {}, function(data) {
    obj = JSON.parse(data);
    obj.forEach(function(value, key) {
      if ( key == 1 )
        url1 = value;
      else if ( key == 2 )
        url2 = value;
      else if ( key == 3 )
        url3 = value;
      else
        url4 = value;
    });
    tile = new Array([1, url1],[2, url2],[3, url3],[4, url4],[5, url1],[6, url2],[7, url3],[8, url4]);
  });

} else {
  alert('Memory Configuration not set');
}

function displayBack(i) {
  jQuery('#t'+i).html('<div class="back card" id="'+i+'"><\/div>');
}

function changeSide() {

  tno = 0;

  if (ch1 !== ch2) {

    jQuery('.front').each(function() {
      if ( ! jQuery(this).hasClass('right') ) {
        var cardToChange = null;

        if ( jQuery(this).attr('id') == idch1 ) {
          cardToChange = jQuery(this).parent().attr('id');
        }

        if ( jQuery(this).attr('id') == idch2 ) {
          cardToChange = jQuery(this).parent().attr('id');
        }

        cardToChange = cardToChange.substring(1,2);
        jQuery('#t'+cardToChange).html('<div class="back card" id="'+cardToChange+'">  <\/div>');
      }
    });

  } else {

    var count = 0;

    jQuery('.front').each(function() {
      if ( ! jQuery(this).hasClass('right') )
        jQuery(this).addClass('right');
      else
        count++
    });

    if ( count === 6 ) {
      alert('You WIN!!! Congratz!');
      setTimeout(function() {
        window.location.reload();
      }, 500);
    }

  }
} // end changeSide function

function begin() {
  for (var i = 1; i <= 8 ;i++)
    displayBack(i);

  tno = 0;

  tile.sort( randOrd );
}

// random order of front side card array
function randOrd(a, b) {
  return (Math.round(Math.random())-0.5);
}

jQuery(document).ready(function($) {

  setTimeout('begin()', 1000);

  $('body').on('click', '.back', function() {
    if ( tno > 1 ) // two card clicked then check if right or change side
      changeSide();

    var sel = $(this).attr('id');
    if ( config === 'picture' )
      $('#t' + sel).html('<div class="card front" id="'+tile[sel-1][0]+'"><img src="'+tile[sel-1][1]+'" width=50 height=50 /></div>');
    else
      $('#t' + sel).html('<div class="card front" id="'+tile[sel-1][0]+'">'+tile[sel-1][1]+'</div>');

    if (tno==0) {
      ch1 = tile[sel-1][1];
      idch1 = tile[sel-1][0];
    } else {
      ch2 = tile[sel-1][1];
      idch2 = tile[sel-1][0];
      setTimeout('changeSide()', 300);
    }

    tno++;
  });

});
