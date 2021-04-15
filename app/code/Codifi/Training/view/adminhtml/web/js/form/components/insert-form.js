/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

define([
    'Magento_Ui/js/form/components/insert-form'
], function (Insert) {
    'use strict';

    return Insert.extend({
        defaults: {
            listens: {
                responseData: 'onResponse'
            },
            modules: {
                noteListing: '${ $.noteListingProvider }'
            }
        },

        /**
         * Reload customer note listing
         *
         * @param {Object} responseData
         */
        onResponse: function (responseData) {
            if (!responseData.error) {
                this.noteListing().reload({
                    refresh: true
                });
            }
        }
    });
});
