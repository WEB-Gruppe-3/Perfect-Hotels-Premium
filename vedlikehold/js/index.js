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
    startDateInput.datepicker();
    startDateInput1.datepicker();
    startDateInput2.datepicker();
    startDateInput3.datepicker();
    startDateInput4.datepicker();
    startDateInput5.datepicker();
    startDateInput6.datepicker();
    startDateInput7.datepicker();
    startDateInput8.datepicker();
    startDateInput9.datepicker();
    startDateInput10.datepicker();

    endDateInput.datepicker();
    endDateInput1.datepicker();
    endDateInput2.datepicker();
    endDateInput3.datepicker();
    endDateInput4.datepicker();
    endDateInput5.datepicker();
    endDateInput6.datepicker();
    endDateInput7.datepicker();
    endDateInput8.datepicker();
    endDateInput9.datepicker();
    endDateInput10.datepicker();

    editstartDateInput.datepicker();
    editendDateInput.datepicker();

    $.datepicker.setDefaults(
        $.extend(
            {'dateFormat':'yy-mm-dd'}
        )
    );

});