var isElvSubmitted = false;
paymillInit = function() {
    if (typeof $.fn.prop !== 'function') {
        $.fn.prop = function(name, value) {
            if (typeof value === 'undefined') {
                return this.attr(name);
            } else {
                return this.attr(name, value);
            }
        };
    }

    PaymillCreateElvForm();
    PaymillAddElvFormFokusActions();

    $('form[action="paymill_confirmation_form"]').submit(function (event) {
        event.preventDefault();
        if (!isElvSubmitted) {
            if (!paymill_elv_fastcheckout) {
                hideErrorBoxes();
                var elvErrorFlag = true;

                if ($('#paymill-bank-owner').val() === "") {
                    $("#elv-holder-error").text(elv_bank_owner_invalid);
                    $("#elv-holder-error").css('display', 'block');
                    elvErrorFlag = false;
                }

                if(isSepa()){
                    elvErrorFlag = PaymillValidateSepaForm(elvErrorFlag);
                } else {
                    elvErrorFlag = PaymillValidateOldElvForm(elvErrorFlag);
                }

                if (!elvErrorFlag) {
                    return elvErrorFlag;
                }

                PaymillCreateElvToken();

                return false;
            } else {
                $('#paymill_form').html('<input type="hidden" name="paymill_token" value="dummyToken" />');
				$('#paymill_form')[0].submit();
            }
        }
    });
};

function PaymillValidateSepaForm(elvErrorFlag)
{
    console.log("Starting Validation for SEPA form...");
    var iban = new Iban();

    if(!iban.validate($('#paymill-iban').val())){
        $('#elv-iban-error').text(elv_iban_invalid);
        $('#elv-iban-error').css('display', 'block');
        elvErrorFlag = false;
    }

    if($('#paymill-bic').val() === ''){
        $('#elv-bic-error').text(elv_bic_invalid);
        $('#elv-bic-error').css('display', 'block');
        elvErrorFlag = false;
    }

    return elvErrorFlag;
}

function PaymillValidateOldElvForm(elvErrorFlag)
{
    console.log("Starting Validation for old form...");
    if (!paymill.validateBankCode($('#paymill-bic').val())) {
        $("#elv-bic-error").text(elv_bank_code_invalid);
        $("#elv-bic-error").css('display', 'block');
        elvErrorFlag = false;
    }
    if (!paymill.validateAccountNumber($('#paymill-iban').val())) {
        $("#elv-iban-error").text(elv_account_number_invalid);
        $("#elv-iban-error").css('display', 'block');
        elvErrorFlag = false;
    }
	
    return elvErrorFlag;
}

function PaymillAddElvFormFokusActions()
{
    $('#paymill-bank-owner').focus(function() {
        paymill_elv_fastcheckout = false;
    });

	$('#paymill-iban').focus(function() {
		paymill_elv_fastcheckout = false;
	});

	$('#paymill-bic').focus(function() {
		paymill_elv_fastcheckout = false;
	});
}

function PaymillCreateElvForm()
{
	if (paymill_elv_iban === "") {
		paymill_elv_iban = paymill_elv_account;
		paymill_elv_bic = paymill_elv_code;
	}
	
    $('#account-name-field').html('<input type="text" value="' + paymill_elv_holder + '" id="paymill-bank-owner" class="form-row-paymill" />');
	$('#iban-field').html('<input type="text" value="' + paymill_elv_iban + '" id="paymill-iban" class="form-row-paymill" autocomplete="off"/>');
	$('#bic-field').html('<input type="text" value="' + paymill_elv_bic + '" id="paymill-bic" class="form-row-paymill" autocomplete="off"/>');
	if ($('#paymill_form').length == 0) {
		$('#bic-field').after('<form id="paymill_form" action="' + success_link + '" method="POST"/>');
	}
}

function PaymillCreateElvToken()
{
    if(isSepa()){ //Sepa Form active
        paymill.createToken({
            iban: $('#paymill-iban').val(),
            bic: $('#paymill-bic').val(),
            accountholder: $('#paymill-bank-owner').val()
        }, PaymillElvResponseHandler);
    } else {
        paymill.createToken({
            number: $('#paymill-iban').val(),
            bank: $('#paymill-bic').val(),
            accountholder: $('#paymill-bank-owner').val()
        }, PaymillElvResponseHandler);
    }
}

function isSepa() 
{
	var reg = new RegExp(/^\D{2}/);
	return reg.test($('#paymill-iban').val());
}

function hideErrorBoxes()
{
    $("#elv-holder-error").css('display', 'none');
	$("#elv-iban-error").css('display', 'none');
	$("#elv-bic-error").css('display', 'none');
}

function PaymillElvResponseHandler(error, result)
{
    isElvSubmitted = true;
    if (error) {
        isElvSubmitted = false;
        console.log(error);
        window.location = $("<div/>").html(checkout_payment_link + error.apierror).text();
    } else {
        $('#paymill_form').html('<input type="hidden" name="paymill_token" value="' + result.token + '" />')
		$('#paymill_form')[0].submit();
        return false;
    }
}

if (window.addEventListener) {
    window.addEventListener("load", paymillInit);
} else if (window.attachEvent) {
    window.attachEvent("onload", paymillInit);
} else { 
	window.onload = paymillInit;
}
