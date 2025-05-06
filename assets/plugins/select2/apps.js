$(document).ready(function(){
	$("#users-id").select2({
		placeholder: {
		    id: "0",
		    text: "Chọn người nhận"
		},
		language: "vi",
	  	ajax: { 
	   		url: base_url + "users/search-ajax",
	   		type: "POST",
	   		dataType: "json",
	   		delay: 250,
	   		data: function (params) {
	    		return {
	      			searchTerm: params.term
	    		};
	   		},
	   		processResults: function (response) {
	     		return {
	        		results: response
	     		};
	   		},
	   		cache: true
	  	}
	});
});