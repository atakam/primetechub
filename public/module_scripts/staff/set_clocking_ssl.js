$(document).ready(function(){
	/* Clock In/Out */
	$("#hr_clocking").submit(function(e){

		e.preventDefault();
		var clock_state = '';
		var obj = $(this), action = obj.attr('name');

    var lat = '3.861770';
    var lng = '11.518750';
    $.ajax({
      type: "POST",
      url: e.target.action,
      data: obj.serialize()+"&is_ajax=1&type=set_clocking&latitude="+lat+"&longitude="+lng+"&form="+action,
      cache: false,
      success: function (JSON) {
        if (JSON.error != '') {
          toastr.error(JSON.error);
          Ladda.stopAll();
        } else {
          toastr.success(JSON.result);
          window.location = '';
          Ladda.stopAll();
        }
      }
    });
	});
});
