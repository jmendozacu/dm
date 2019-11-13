define([
    'Magento_Ui/js/form/element/ui-select',
    'underscore'
], function (UiSelect, _) {
    return UiSelect.extend({
        toggleOptionSelected: function (data) {
            if (_.isUndefined(data.disabled) || data.disabled == false) {
                this._super();
            }
        }
    });
});
