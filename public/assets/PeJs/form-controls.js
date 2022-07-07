//== Class definition

var FormControls = function () {
    //== Private functions
    
    var demo1 = function () {
        $( "#m_form_1" ).validate({
            // define validation rules
            rules: {
                email: {
                    required: true,
                    email: true 
                },
//                name: {
//                    required: true,
//					
//                },
//                empl_id: {
//                    required: true,
//                    digits: true
//                },
//				 department: {
//                    required: true,
//                },
//				age: {
//                    required: true,
//                     
//                },
//                dob: {
//                    required: true,
//                    
//                },
//                phone: {
//                    required: true,
//                    phoneUS: true 
//                }
            },
            //display error alert on form submit  
            invalidHandler: function(event, validator) {     
                var alert = $('#m_form_1_msg');
                alert.removeClass('m--hide').show();
        		mApp.scrollTo(alert, -200);
            },

            submitHandler: function (form) {
//				alert('sddwedwe');
				//formsubmit(form);
                //form[0].submit(); // submit the form
//				formform.serialize();
				var serializedData = $(form).serialize();
				$.ajax({
					url:"./edit", 
					type: "POST",             
					data: serializedData,
					cache: false,             
					processData: false,  
					dataType: 'json',
					success: function(data){
//						console.log(data.user_id);
//						$('#up_name').show().html(data['fullname']);
						$.notify({
								title: '<strong>Heads up!</strong>',
								message: 'Successfully updated your personal details.'
								},{
								type: 'success'
						});
										
						$('#up_name').html(data['fullname']);
						$('#up_dob').html(data.dob);
						$('#up_phone').html(data['phone']);
						

            }
        });
//        return false;
//				var url="User/edit"
//			$.ajax(
//				{
//					type:"POST",
//					url:"<?php echo base_url()?>"+url,
//					data:dataString,
//					dataType: 'json',
//					success:function (data)
//					{
//						
//					//	console.log(data['stat-msg']);
////						if(data['stat'] == 0)
////						{
////							$('#success_contact').hide();
////							$('#error_contact').show().html(data['stat-msg']);
////						}
////						else
////						{
////							$('#error_contact').hide();
////							$('#success_contact').show().html(data['stat-msg']);
////							$("#contactusform")[0].reset();
////						}
//
//					}
//				});
//				return false;
            }
        });       
    }

 

    return {
        // public functions
        init: function() {
            demo1(); 
            
        }
    };
}();

$(document).ready(function() {    
    FormControls.init();
	

});
