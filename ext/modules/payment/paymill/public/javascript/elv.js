var isElvSubmitted = false;
$(document).ready(function() {
    if (typeof $.fn.prop !== 'function') {
        $.fn.prop = function(name, value) {
            if (typeof value === 'undefined') {
                return this.attr(name);
            } else {
                return this.attr(name, value);
            }
        };
    }

    $('#account-name-field').html('<input type="text" value="' + paymill_elv_holder + '" id="paymill-bank-owner" class="form-row-paymill" />');
    if(sepaActive == 'True'){
        $('#iban-field').html('<input type="text" value="' + paymill_elv_iban + '" id="paymill-iban" class="form-row-paymill" />');
        $('#bic-field').html('<input type="text" value="' + paymill_elv_bic + '" id="paymill-bic" class="form-row-paymill" />');
    } else {
        $('#account-number-field').html('<input type="text" value="' + paymill_elv_account + '" id="paymill-account-number" class="form-row-paymill" />');
        $('#bank-code-field').html('<input type="text" value="' + paymill_elv_code + '" id="paymill-bank-code" class="form-row-paymill" />');
    }

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

                if(sepaActive == 'True'){ //Sepa Form active
                    console.log("Starting Validation for new form...");
                    if(false === ($('#paymill-iban').val() != '')){
                        $('#elv-iban-error').text(elv_iban_invalid);
                        $('#elv-iban-error').css('display', 'block');
                        elvErrorFlag = false;
                    }

                    if(false === ($('#paymill-bic').val() != '')){
                        $('#elv-bic-error').text(elv_iban_invalid);
                        $('#elv-bic-error').css('display', 'block');
                        elvErrorFlag = false;
                    }

                } else { //Old Form active
                    console.log("Starting Validation for old form...");
                    if (false === paymill.validateAccountNumber($('#paymill-account-number').val())) {
                        $("#elv-account-error").text(elv_account_number_invalid);
                        $("#elv-account-error").css('display', 'block');
                        elvErrorFlag = false;
                    }
                    console.log("Account number valid...");
                    if (false === paymill.validateBankCode($('#paymill-bank-code').val())) {
                        $("#elv-bankcode-error").text(elv_bank_code_invalid);
                        $("#elv-bankcode-error").css('display', 'block');
                        elvErrorFlag = false;
                    }
                    console.log("Bank code valid...");
                }

                if (!elvErrorFlag) {
                    return elvErrorFlag;
                }


                if(sepaActive == 'True'){ //Sepa Form active
                    paymill.createToken({
                        iban: $('#paymill-iban').val(),
                        bic: $('#paymill-bic').val(),
                        accountholder: $('#paymill-bank-owner').val()
                    }, PaymillElvResponseHandler);
                } else {
                    paymill.createToken({
                        number: $('#paymill-account-number').val(),
                        bank: $('#paymill-bank-code').val(),
                        accountholder: $('#paymill-bank-owner').val()
                    }, PaymillElvResponseHandler);
                }

                return false;
            } else {
                $('#paymill_form').html('<input type="hidden" name="paymill_token" value="dummyToken" />').submit();
            }
        }
    });

    $('#paymill-bank-owner').focus(function() {
        paymill_elv_fastcheckout = false;
        $('#paymill-bank-owner').val('');
    });

    if(sepaActive == 'True'){
        $('#paymill-iban').focus(function() {
            $('#paymill-iban').val('');
            paymill_elv_fastcheckout = false;
        });

        $('#paymill-bic').focus(function() {
            $('#paymill-bic').val('DE');
            paymill_elv_fastcheckout = false;
        });
    } else {
        $('#paymill-account-number').focus(function() {
            $('#paymill-account-number').val('');
            paymill_elv_fastcheckout = false;
        });

        $('#paymill-bank-code').focus(function() {
            $('#paymill-bank-code').val('');
            paymill_elv_fastcheckout = false;
        });
    }
    function hideErrorBoxes()
    {
        $("#card-holder-error").css('display', 'none');

        if(sepaActive == 'True'){ //Sepa Form active
            $("#elv-iban-error").css('display', 'none');
            $("#elv-bic-error").css('display', 'none');
        } else {
            $("#elv-bankcode-error").css('display', 'none');
            $("#elv-account-error").css('display', 'none');
        }
    }

    function PaymillElvResponseHandler(error, result)
    {
        isElvSubmitted = true;
        if (error) {
            isElvSubmitted = false;
            console.log(error);
            window.location = $("<div/>").html(checkout_payment_link + error.apierror).text();
        } else {
            $('#paymill_form').html('<input type="hidden" name="paymill_token" value="' + result.token + '" />').submit();
            return false;
        }
    }
});