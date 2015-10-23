(function($) {
$(document).ready(function(){
    $(':checkbox.selectall').on('click', function(){
        $(':checkbox[name="' + $(this).data('checkbox-name') + '"]').prop("checked", $(this).prop("checked"));
    });
    $(':checkbox.checkme').on('click', function(){
        var _selectall = $(this).prop("checked");
        if ( _selectall ) {
            $( ':checkbox[name="' + $(this).attr('name') + '"]' ).each(function(i){
                _selectall = $(this).prop("checked");
                return _selectall;
            });
        }
        $(':checkbox[name="' + $(this).data('select-all') + '"]').prop("checked", _selectall);
    });
});

// hiding the feature box div if it is not selected in the type drop down box
$(document).ready(function (){

$("#insert_before_div").hide();

if ($("#ucta_type").val() == "custom")	
	$("#insert_before_div").show();

$('#ucta_type').change(function() {
    ($(this).val() == "custom") ? $('#insert_before_div').show() : $('#insert_before_div').hide();
});
 });

// selecting all rows for the actions of Turn On, Off, and Delete
$(document).ready(function (){
$('#select_all_rows').click(function() {
var status = $(this).attr('checked');
if (status == undefined) {
status = false;
}
$('#name_column input').attr('checked',status);
});
 });
 
// prompting the user for a DELETE
$(document).ready(function () {
  $("#change_action_form").submit(function () {
    if ($("#action_dd").val() == "delete") {
      if (!confirm("Are you sure? Once you delete a Call to Action it is gone for good.")) {
        return false;
      }
    }
    return true;
  });
});

// prompting the user that they did attach categories to a CTA
$(document).ready(function(){    
	$('#btnsave').click(function(){   
	   var chkboxrowcount = $(".category_list input[id*='show_categories[]']:checkbox:checked").size();   
	   if (chkboxrowcount==0)
	   {
			alert("Bertha, Hold On! You must select at least one Category for this Call to Action.");
			return false;         
	   }
	   return true;
   });
});

})( jQuery );


function SetAllCheckBoxes(FormName, FieldName, CheckValue)
{
	if(!document.forms[FormName])
		return;
	var objCheckBoxes = document.forms[FormName].elements[FieldName];
	if(!objCheckBoxes)
		return;
	var countCheckBoxes = objCheckBoxes.length;
	if(!countCheckBoxes)
		objCheckBoxes.checked = CheckValue;
	else
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
			objCheckBoxes[i].checked = CheckValue;
}