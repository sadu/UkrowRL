$(function() {
	$('#url-shortner').submit(function(e) {
		$form = $(this).children("form");
		$form.children("input#no-js").val(false);		// Set no-js var to false
		sendRequest($form.serialize(), this);			// Post data
        e.preventDefault();								
	
    });
});
function sendRequest(postData, sender) {	
	$.post('api.php', postData, function(data){
		data = JSON.parse(data);	
		if(data.error == '')
			$(sender).children("#status").hide("normal").html('<div class="success">Your short url is: <a href="'+DOMAIN_URL+'index.php?key='+data.url+'" class="link" >'+DOMAIN_URL+'index.php?key='+data.url+'</a></div>').show("normal");
		else
			$(sender).children("#status").hide("normal").html('<div class="fail">An error occured: '+data.error+'</div>').show("normal");
	});
}
