function jsonEscape(str){
    return str.replace(/\n/g, '').replace(/\t/g, '');
}

function getOrderTemplate(order){
  var orderTemplate = `
  <div class="panel panel-success">
    <div class="panel-heading">
      <h3 class="panel-title"><b>${order["restaurant"]}</b> (created by: ${order["creator"]})</h3>
    </div>
    <div class="panel-body">
      ${order["content"]}
    </div>
  </div>
  `;
  return orderTemplate;
}

function getAllOrdersTemplate(orders){
  var allOrdersTemplate = '';
  for (var key in orders) {
       var order = orders[key];
       allOrdersTemplate += getOrderTemplate(order);
   }
   return allOrdersTemplate;
}

function getOrdersFromResponse(response){
  return JSON.parse(jsonEscape(response["orders"]));
}

function getResponseFromResponseStream(responseStream){
  return JSON.parse(jsonEscape(responseStream));
}
