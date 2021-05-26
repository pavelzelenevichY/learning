/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

define([
    'jquery',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/modal/confirm',
], function ($, Component, customerData) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();
            customerData.reload('credit_hold_section');
            this.customsection = customerData.get('credit_hold_section');
            this.creditHold = this.customsection().credit_hold;

            var url = this.url;
            var customerId = this.customerId;

            if (this.checkOptionAndFlag) {

                if (this.creditHold) {
                    showPopup();
                }

                this.customsection.subscribe(function (updatedCustomer) {
                    this.creditHold = updatedCustomer.credit_hold;
                    if (this.creditHold) {
                        showPopup();
                    }
                });
            }

            function showPopup(){
                $('.confirmation-modal-content').show();
                $('.confirmation-modal-content').confirm({
                    actions: {
                        always: function () {
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: {
                                    'note': $.mage.__('Customer is notified about credit hold.'),
                                    'customer_id': customerId
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
        }
    });
});
