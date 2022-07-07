//== Class definition

var BootstrapDatepicker = function () {
    
    //== Private functions
    var demos = function () {
		//User side Work report
		  $('#user_report_datepicker, #user_report_datepicker_validate').datepicker({
			format: "dd-mm-yyyy",
            todayHighlight: true,
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });

		
		//Close user side work report
        // minimum setup
        $('#m_datepicker_1, #m_datepicker_1_validate').datepicker({
			format: "d-m-yyyy",
            todayHighlight: true,
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });

        $('#m_datepicker_joindate').datepicker({
			format: "d-m-yyyy",
            todayHighlight: true,
            orientation: "top right",
            minDate:0,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });

        $('#m_datepicker_joindate2').datepicker({
			format: "d-m-yyyy",
            todayHighlight: true,
            orientation: "top right",
            minDate:0,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });

        /** Datepicker in update interview scheduler */
        $('#m_datepicker_joindate_updated').datepicker({
			format: "d-m-yyyy",
            todayHighlight: true,
            orientation: "top right",
            minDate:0,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });
        /** Close admin interview joining date */
		//Admin report of users work
			
        $('#admin_datepick_ticket').datepicker({
//            todayHighlight: true,
            orientation: "bottom left",
			format: "mm-yyyy",
    		startView: "months", 
			minViewMode: "months",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });
		//Close work report in admin side
        // minimum setup for modal demo
        $('#m_datepicker_1_modal').datepicker({
            todayHighlight: true,
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });

        // input group layout 
        $('#m_datepicker_2, #m_datepicker_2_validate').datepicker({
//            todayHighlight: true,
            orientation: "bottom left",
			format: "mm-yyyy",
    		startView: "months", 
			minViewMode: "months",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });
		//Close 
		
		//Open date picker in workreport of user secion
			  $('#user_workreport_m_datepicker_2, #user_workreport_m_datepicker_2').datepicker({
//            todayHighlight: true,
				orientation: "bottom left",
//				format: "mm-yyyy",
//				startView: "months", 
//				minViewMode: "months",
				templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });
		
		//Close date picker in user section

        // input group layout for modal demo
//		Admin section daily activities status
		   $('#m_datepicker_admin').datepicker({
//            todayHighlight: true,
            orientation: "bottom left",
			format: "mm-yyyy",
    		startView: "months", 
			minViewMode: "months",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });
		//Month pick in atttendance view
		  $('#month_pick_attendance').datepicker({
//            todayHighlight: true,
            orientation: "bottom left",
			format: "mmyyyy",
    		startView: "months", 
			minViewMode: "months",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });
//Close month pick in attendance view
        // input group layout for modal demo
//		Close admin section
        $('#m_datepicker_2_modal').datepicker({
//            todayHighlight: true,
			format: "mm-yyyy",
    		startView: "months", 
			minViewMode: "months",
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });

        // enable clear button 
        $('#m_datepicker_3, #m_datepicker_3_validate').datepicker({
            todayBtn: "linked",
            clearBtn: true,
            todayHighlight: true,
			
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });

        // enable clear button for modal demo
        $('#m_datepicker_3_modal').datepicker({
            todayBtn: "linked",
            clearBtn: true,
            todayHighlight: true,
		
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });

        // orientation 
        $('#m_datepicker_4_1').datepicker({
            orientation: "top left",
            todayHighlight: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });

        $('#m_datepicker_4_2').datepicker({
            orientation: "top right",
            todayHighlight: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });

        $('#m_datepicker_4_3').datepicker({
            orientation: "bottom left",
            todayHighlight: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });

        $('#m_datepicker_4_4').datepicker({
            orientation: "bottom right",
            todayHighlight: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });

        // range picker
        $('#m_datepicker_5').datepicker({
            todayHighlight: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });
//For daily work reports in admin section
		  $('#datepick_4daily').datepicker({
            todayHighlight: true,
		    format: "dd-mm-yyyy",
            templates: {
				
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }); 
//Close daily date picker
$('#m_datepicker_joindate_updated,#m_datepicker_joindate2').datepicker({
    format: "dd/mm/yyyy",
    todayHighlight: true,
    orientation: "bottom left",
})
         // inline picker
        $('#m_datepicker_6').datepicker({
            todayHighlight: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });
    }

    return {
        // public functions
        init: function() {
            demos(); 
        }
    };
}();

jQuery(document).ready(function() {    
    BootstrapDatepicker.init();
});

