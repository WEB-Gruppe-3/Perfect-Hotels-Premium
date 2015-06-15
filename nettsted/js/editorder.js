/**
 * JS Stuff for index.php
 */

startDateInput = $("#startDateInput");
startDateInput1 = $("#startDateInput1");
startDateInput2 = $("#startDateInput2");
startDateInput3 = $("#startDateInput3");
startDateInput4 = $("#startDateInput4");
startDateInput5 = $("#startDateInput5");
startDateInput6 = $("#startDateInput6");
startDateInput7 = $("#startDateInput7");
startDateInput8 = $("#startDateInput8");
startDateInput9 = $("#startDateInput9");
startDateInput10 = $("#startDateInput10");

endDateInput = $("#endDateInput");
endDateInput1 = $("#endDateInput1");
endDateInput2 = $("#endDateInput2");
endDateInput3 = $("#endDateInput3");
endDateInput4 = $("#endDateInput4");
endDateInput5 = $("#endDateInput5");
endDateInput6 = $("#endDateInput6");
endDateInput7 = $("#endDateInput7");
endDateInput8 = $("#endDateInput8");
endDateInput9 = $("#endDateInput9");
endDateInput10 = $("#endDateInput10");

editstartDateInput = $("#editstartDateInput");
editendDateInput = $("#editendDateInput");

$(function() {

    // Setting and configuring date picker
    startDateInput.datepicker({ minDate: 0 });
    startDateInput1.datepicker({ minDate: 0 });
    startDateInput2.datepicker({ minDate: 0 });
    startDateInput3.datepicker({ minDate: 0 });
    startDateInput4.datepicker({ minDate: 0 });
    startDateInput5.datepicker({ minDate: 0 });
    startDateInput6.datepicker({ minDate: 0 });
    startDateInput7.datepicker({ minDate: 0 });
    startDateInput8.datepicker({ minDate: 0 });
    startDateInput9.datepicker({ minDate: 0 });
    startDateInput10.datepicker({ minDate: 0 });

    endDateInput.datepicker({ minDate: 0 });
    endDateInput1.datepicker({ minDate: 0 });
    endDateInput2.datepicker({ minDate: 0 });
    endDateInput3.datepicker({ minDate: 0 });
    endDateInput4.datepicker({ minDate: 0 });
    endDateInput5.datepicker({ minDate: 0 });
    endDateInput6.datepicker({ minDate: 0 });
    endDateInput7.datepicker({ minDate: 0 });
    endDateInput8.datepicker({ minDate: 0 });
    endDateInput9.datepicker({ minDate: 0 });
    endDateInput10.datepicker({ minDate: 0 });

    editstartDateInput.datepicker({ minDate: 0 });
    editendDateInput.datepicker({ minDate: 0 });

    $.datepicker.setDefaults(
        $.extend(
            {'dateFormat':'yy-mm-dd'}
        )
    );

});
