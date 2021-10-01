$(function(){
    // Step show event
    $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
       //alert("You are on step "+stepNumber+" now");
       if(stepPosition === 'final'){
           $("#finish-btn").removeAttr('disabled');
       } else {
            $("#finish-btn").attr('disabled', true);
       }
    });

    $("#smartwizard").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
        var elmForm = $("#form-step-" + stepNumber);
        // stepDirection === 'forward' :- this condition allows to do the form validation
        // only on forward navigation, that makes easy navigation on backwards still do the validation when going next
        if(stepDirection === 'forward' && elmForm){
            elmForm.validator('validate');
            var elmErr = elmForm.find('.has-error');
            if(elmErr && elmErr.length > 0){
                // Form validation failed
                return false;
            }
        }
        return true;
    });

    // Toolbar extra buttons
    var btnFinish = $('<button id="finish-btn" disabled></button>').text('Finish')
        .addClass('btn btn-info')
        .on('click', function(){ onComplete() });

    // Smart Wizard
    $('#smartwizard').smartWizard({
        selected: 0,
        theme: 'default',
        transitionEffect:'fade',
        showStepURLhash: true,
        toolbarSettings: {toolbarPosition: 'bottom',
            toolbarButtonPosition: 'end',
            toolbarExtraButtons: [btnFinish]
        }
    });

    // Set selected theme on page refresh
    $('#smartwizard').smartWizard("theme", 'arrows');
    window.scrollTo(0, 0);
});

function onComplete() {
    $('#form-buy').submit();
}