function getAjaxUrlFilter(){
  var url = '';
  for (var prop in filterObject) {
    url += 'filter[' + prop + ']' + '=' + filterObject[prop] + '&';
  }
  return url;
}

function getOrders(page){
   $("#loader").fadeIn(333);
   urlAjax = `/ajax/orders/${section}?page=${page}&` + getAjaxUrlFilter();
   //alert(urlAjax);
    $.ajax({url: urlAjax, success: function(result){
        var response = getResponseFromResponseStream(result);
        var orders = getOrdersFromResponse(response);
        allOrdersTemplate = getAllOrdersTemplate(orders);

        $("#orders_count").html('(' + response["count"] + ' Orders)');
        $("#orders").html(allOrdersTemplate);
        $("#paginator").html(response["paginator"]);
        $("#loader").fadeOut(333);
    }});
}

function initializeFilterButton(){
  filter_save.addEventListener("click", function() {
    filterObject["restaurant"] = $("#filter_restaurant").val();
    filterObject["state"] = $("#filter_state").val();
    if( $("#filter_myorders").is(':checked') == true){
      filterObject["myorders"] = 1;
    }else{
      filterObject["myorders"] = 0;
    }
     getOrders(1);
   });
}
