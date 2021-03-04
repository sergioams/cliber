$(document).ready(function(){
    var $alertModal         = $('#alertModal');
    var $alertModalBody     = $alertModal.find('.modal-body');
    var $alertModalHeader   = $alertModal.find('.modal-header');
    
    var alertModal          = document.getElementById('alertModal');
    var bsAlertModal        = new bootstrap.Modal(alertModal, {
        keyboard: false
    });

    var $frmCurrencyConversor   = $('#frmCurrencyConversor');
    var $selectToCurrency       = $frmCurrencyConversor.find('#selectToCurrency');
    var $btnConvert             = $frmCurrencyConversor.find('#btnConvert');
    var $gridCurrency           = $('#gridCurrency');

    function enableFrmCurrencyConversor(){
        $selectToCurrency.removeAttr('disabled', 'disabled');
        $btnConvert.removeAttr('disabled', 'disabled').find('i').removeClass('fa-spin');
    }
    function disableFrmCurrencyConversor(){
        $selectToCurrency.attr('disabled', 'disabled');
        $btnConvert.attr('disabled', 'disabled').find('i').addClass('fa-spin');
    }

    function showAlertModal(message, alertType = ''){
        let $p = $('<p />', { 'text': message, 'class': 'm-0 p-0 text-center'});
        switch(alertType){
            case 'danger':
                alertType = 'bg-' + alertType + ' text-white';
            break;
            default:
                alertType = 'bg-light text-body';
        }
        $alertModalHeader.addClass(alertType);
        $alertModalBody.append($p);
        bsAlertModal.show();
    }
    
    function doCurrencyConversion(jsonData){
        disableFrmCurrencyConversor();
        $.ajax({
            'method': 'POST',
            'url': url.currency_converter,
            'data': jsonData,
            'dataType': 'json'
        }).done(function(data, textStatus, jqXHR){
            if(jqXHR.status !== 200){
                showAlertModal('No se ha logrado realizar la conversión, actualice la página e intente de nuevo.', 'danger');
            }else{
                if(data.status==='error'){
                    showAlertModal('No se ha logrado establecer conexión con el servicio de conversión, actualice la página e intente de nuevo.', 'danger');
                }else{
                    getGridConversion(data.data);
                }
            }
        }).always(function(data, textStatus, jqXHR){
            enableFrmCurrencyConversor();
        });
    }
    
    function getGridConversion(jsonData){
        disableFrmCurrencyConversor();
        
        $gridCurrency.html('');
        
        jsonData = {
            'currencyConversions': jsonData
        };
        $.ajax({
            'method': 'POST',
            'url': url.grid_conversion,
            'data': jsonData,
            'dataType': 'json'
        }).done(function(data, textStatus, jqXHR){
            if(jqXHR.status !== 200){
                showAlertModal('No se ha logrado realizar la conversión, actualice la página e intente de nuevo.', 'danger');
            }else{
                if(data.status==='error'){
                    showAlertModal('No se ha logrado establecer conexión con el servicio de conversión, actualice la página e intente de nuevo.', 'danger');
                }else{
                    $gridCurrency.html(data.data);
                }
            }
        }).always(function(data, textStatus, jqXHR){
            enableFrmCurrencyConversor();
        });
    }

    alertModal.addEventListener('hide.bs.modal', function(event){
        $alertModalHeader.removeClass().addClass('modal-header py-1');
        $alertModalBody.html('');
    });

    $selectToCurrency.on('change', function(evt){
        $gridCurrency.html('');
    });

    $btnConvert.off('click').on('click', function(evt){
        evt.preventDefault();

        let selectToCurrency = $selectToCurrency.val();
        selectToCurrency = selectToCurrency.trim();

        if(selectToCurrency.length === 0){
            showAlertModal('Debe seleccionar una moneda base.', 'danger');
            enableFrmCurrencyConversor();
            return false;
        }
        
        let jsonData = {
            toCurrency: selectToCurrency
        };
        doCurrencyConversion(jsonData);
        
        return false;
    });
});
