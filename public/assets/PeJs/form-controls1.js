//== Class definition

var FormControls = function () {
    //== Private functions
    
    var demo1 = function () {
		alert('welcome to validate');
        $( "#m_form_1" ).validate({
            // define validation rules
			
            rules: {
                name: {
                    required: true,
                    name: true 
                },
                mail_id: {
                    required: true,
					mail: true 
                },
                empl_id: {
                    required: true,
                    empl_id: true
                },
                dep: {
                    required: true,
                    dep: true 
                },
                age: {
                    required: true,
                    age: true 
                },
                dob: {
                    required: true,
					dob: true
                },
                phone: {
                    required: true,
//                    minlength: 2,
//                    maxlength: 4
                }
            },
            
            //display error alert on form submit  
            invalidHandler: function(event, validator) {     
                var alert = $('#m_form_1_msg');
                alert.removeClass('m--hide').show();
                mApp.scrollTo(alert, -200);
				event.preventDefault();
            },

            submitHandler: function (form) {
//                form[0].submit(); // submit the form
            }
        });       
    }

    var demo2 = function () {
        $( "#m_form_2" ).validate({
            // define validation rules
            rules: {
                email: {
                    required: true,
                    email: true 
                },
                url: {
                    required: true 
                },
                digits: {
                    required: true,
                    digits: true
                },
                creditcard: {
                    required: true,
                    creditcard: true 
                },
                phone: {
                    required: true,
                    phoneUS: true 
                },
                option: {
                    required: true
                },
                options: {
                    required: true,
                    minlength: 2,
                    maxlength: 4
                },
                memo: {
                    required: true,
                    minlength: 10,
                    maxlength: 100
                },

                checkbox: {
                    required: true
                },
                checkboxes: {
                    required: true,
                    minlength: 1,
                    maxlength: 2
                },
                radio: {
                    required: true
                }
            },
            
            //display error alert on form submit  
            invalidHandler: function(event, validator) {     
                var alert = $('#m_form_2_msg');
                alert.removeClass('m--hide').show();
                mApp.scrollTo(alert, -200);
            },

            submitHandler: function (form) {
//                form[0].submit(); // submit the form
            }
        });       
    }

    return {
        // public functions
        init: function() {
			alert('hiii');
            demo1(); 
            
        }
    };
}();

jQuery(document).ready(function() { 
		alert('hiii222');
    FormControls.init();
});