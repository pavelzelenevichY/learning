/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

require([
    'jquery',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/modal/confirm',
], function ($, customerData) {
    'use strict';

    customerData.reload('credit_hold_section');

    var customsection = customerData.get('credit_hold_section');
    customsection.subscribe(function (updatedCustomer)
    {
        console.log(updatedCustomer);
        var creditHold = updatedCustomer.credit_hold;

        if (creditHold) {
            $('.confirmation-modal-content').confirm({
                actions: {
                    always: function () {
                        $.ajax({
                            type: "POST",
                            url: this.url,
                            data: {
                                'note': $.mage.__('Customer is notified about credit hold.'),
                                'customer_id': this.customerId
                            },
                            success: function (data) {
                            }
                        });
                    }
                },
                buttons: [{
                    text: $.mage.__('OK'),
                    class: 'action primary action-accept',
                    click: function (event) {
                        this.closeModal(event, true);
                    }
                }]
            });
        }
    }, this);
});
