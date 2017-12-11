$(document).ready(function() {
  // Product List
  $('#list-view-new').click(function() {
    $('#content .product-layout > .clearfix').remove();

     $('.noparent').each(function(index, element) {
          html = '';

          var image = $(element).find('.image').html();
          var name = $(element).find('.name').html();
          var description = $(element).find('.description').html();
          var price = $(element).find('.price').html();
          var cart = $(element).find('.button-group').html();
          var rating = $(element).find('.rating').html();

          if (image != null) {
              html += '<div class="product-layout product-list col-xs-12"><div class="product-thumb"><div class="image">' + image + '</div>';
          } 
          if (name != null) {
              html += '<div><div class="caption">' + name ;
          }
          if (description != null) {
              html += '<p>' + description + '</p>';
          }
          if (rating != null) {
              html += '<div class="rating">' + rating + '</div>';
          }   
          if (price != null) {
              html += '<p class="price">' + price + '</p></div>';
          }
          if (cart != null) {
              html += '<div class="button-group">' + cart + '</div></div></div></div>';
          }   

          $(element).html(html);        
    });
 
    if ($("tr").is(".noparent")) {
     $('.product-list').unwrap().unwrap().unwrap().unwrap();
     $('thead').remove();
    }

    $('#content .product-layout').attr('class', 'product-layout product-list col-xs-12');

    localStorage.setItem('display', 'listnew');
    $(this).addClass('btn-danger');
    $('#grid-view-new').removeClass('btn-danger');
    $('#price-view').removeClass('btn-danger');
  });

  // Product Grid
  $('#grid-view-new').click(function() {
    $('#content .product-layout > .clearfix').remove();

      $('.noparent').each(function(index, element) {
          html = '';

          var image = $(element).find('.image').html();
          var name = $(element).find('.name').html();
          var description = $(element).find('.description').html();
          var price = $(element).find('.price').html();
          var cart = $(element).find('.button-group').html();
          var rating = $(element).find('.rating').html();

          if (image != null) {
              html += '<div class="product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12"><div class="product-thumb"><div class="image">' + image + '</div>';
          }
          if (name != null) {
              html += '<div><div class="caption">' + name ;
          }
          if (description != null) {
              html += '<p>' + description + '</p>';
          }
          if (rating != null) {
              html += '<div class="rating">' + rating + '</div>';
          }   
          if (price != null) {
              html += '<p class="price">' + price + '</p></div>';
          }
          if (cart != null) {
              html += '<div class="button-group">' + cart + '</div></div></div></div>';
          }   

          $(element).html(html);        
    });

    if ($("tr").is(".noparent")) {      
     $('.product-grid').unwrap().unwrap().unwrap().unwrap();
/*     $('.product-grid').warp('<div class="row"></div>');*/
     $('thead').remove();
    }

    // What a shame bootstrap does not take into account dynamically loaded columns
    cols = $('#column-right, #column-left').length;   

    if (cols == 2) {
      $('#content .product-layout').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
    } else if (cols == 1) {
      $('#content .product-layout').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');
    } else {
      $('#content .product-layout').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-4 col-xs-12');
    }

     localStorage.setItem('display', 'gridnew');
     $(this).addClass('btn-danger');
     $('#list-view-new').removeClass('btn-danger');
     $('#price-view').removeClass('btn-danger');
  });


// Product Price
    $('#price-view').click(function() {

        $('#content .product-layout').attr('class', 'product-layout product-price col-xs-12');

      $('.product-price > div').each(function(index, element) {
          html = '';

          var image = $(element).find('.image').html();
          var name = $(element).find('.caption h4').html();
          var description = $(element).find('.caption p').html();
          var price = $(element).find('.price').html();
          var cart = $(element).find('.button-group').html();
          var rating = $(element).find('.rating').html();
          var labels = $(element).find('.product-label').html();

          if (labels != null) {
              labels = '<div class="product-label">' + labels + '</div>';
          }
          if (labels == undefined) {
          	  labels = '';
          }
          if (rating != null) {
              rating = '<p class="rating">' + rating + '</p>';
          }
          if (rating == undefined) {
          	  rating = '';
          } 
          if (image != null) {
              html += '<tr class="noparent"><td class="image">' + image + labels + '</td>';
          }
          if (name != null) {
              html += '<td class="name"><h4>' + name + '</h4></td>';
          }
          if (description != null) {
              html += '<td><p class="description">' + description + '</p>' + rating +'</td>';
          } 
          if (price != null) {
              html += '<td class="price">' + price + '</td>';
          }
          if (cart != null) {
              html += '<td class="button-group">' + cart + '</td></tr>';
          }                       

          $(element).html(html);        
    });
        if (localStorage.getItem('display') == 'listnew' || localStorage.getItem('display') == 'gridnew') {
      $('.noparent').unwrap().unwrap();
          $(".noparent").wrapAll('<div class="col-sm-12 price-view"><table class="table table-hover product-price"><tbody></tbody></table></div>');
          $('thead').remove();
          $('.noparent').parent().before('<thead><tr> <th></th><th>Название</th><th>Описание</th><th>Цена</th> <th></th> </tr></therd>');
	    } else {
	      $('.noparent').unwrap().unwrap();
	          $(".noparent").wrapAll('<div class="row"><div class="col-sm-12 price-view"><table class="table table-hover product-price"><tbody></tbody></table></div></div>');
	          $('thead').remove();
	          $('.noparent').parent().before('<thead><tr> <th></th><th>Название</th><th>Описание</th><th>Цена</th> <th></th> </tr></therd>');
	    }        

        localStorage.setItem('display', 'price');
        $(this).addClass('btn-danger');
        $('#grid-view-new').removeClass('btn-danger');
        $('#list-view-new').removeClass('btn-danger');
     
    });

	if ($(window).width() <= 768){
	localStorage.setItem('display', 'gridnew');
	}

	if (localStorage.getItem('display') == 'listnew') {
	$('#list-view-new').trigger('click');
	} else if (localStorage.getItem('display') == 'gridnew') {
	$('#grid-view-new').trigger('click');
	} else {
	$('#price-view').trigger('click');
	}

});