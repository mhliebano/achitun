jQuery.validator.addMethod(
	"dateES",
	function(value, element) {
		var check = false;
		var re = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
		if( re.test(value)){
			var adata = value.split('/');
			var dd = parseInt(adata[0],10);
			var mm = parseInt(adata[1],10);
			var yyyy = parseInt(adata[2],10);
			var xdata = new Date(yyyy,mm-1,dd);
			if (xdata.getFullYear() == yyyy  &&  xdata.getMonth () == mm - 1  &&  xdata.getDate()== dd)
				check = true;
			else
				check = false;
		} else
			check = false;
		return this.optional(element) || check;
	},
	"Por Favor use dd/mm/yyyy"
);
