/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

$(document).ready(function () {
    $('#select-drug-list').select2({
        ajax: {
            url: function (params) {
                return `/pharmacy/drug/search?q=${params.term}`
            },
            dataType: 'json',
            delay: 1000,
            data: function (params) {
                return {};
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: data,
                };
            },
            cache: true
        },
        placeholder: 'Search for a drug',
        minimumInputLength: 1,
        templateResult: formatDrug,
        templateSelection: formatDrugSelection
    });
});

$('.prescription-image').on('click', (event) => {
    const { src } = event.target;
    const imageModal = $('#prescriptionImageModal');

    imageModal.find('#prescriptionImage').attr('src', src);
});

formatDrug = (drug) => {
    if (drug.loading) {
        return drug.text;
    }

    return drug.name || drug.text;
}

formatDrugSelection = (drug) => {
    return drug.name || drug.text;
}

$('#select-drug-list').on('select2:select', function (event) {
    const data = event.params.data;

    $('#selected-drug').data('selected_drug', data);
    $('#drug-quantity').val('');
});

$('#add-drug-item').on('click', (event) => {
    const selectedDrug = $('#selected-drug').data('selected_drug');
    const drugQuantity = $('#drug-quantity').val();
    const drugListField = $('#drug-list');
    const drugListString = drugListField.attr('value');

    if (
        !$.isEmptyObject(selectedDrug) &&
        isValidNumber(drugQuantity) &&
        drugQuantity > 0
    ) {
        const {
            id,
            name,
            price,
        } = selectedDrug;

        let drugList = drugListString ? JSON.parse(drugListString) : {};
        const hasDrug = Object.keys(drugList).length && drugList.hasOwnProperty(id);
        const quantity = parseInt(drugQuantity);

        if (!hasDrug) {
            drugList[id] = {
                id,
                name,
                price: price,
                quantity: quantity,
                amount: quantity * price,
            };
        } else {
            drugList[id].quantity += quantity;
            drugList[id].amount = drugList[id].quantity * price;
        }

        drugListField.attr('value', JSON.stringify({ ...drugList }));
        setDrugList(drugList);
    }
});

isValidNumber = (value) => {
    return /^[0-9]+$/.test(value);
}

setDrugList = (drugList) => {
    let drugListString = '';
    let totalAmount = 0;

    for (const drug of Object.values(drugList)) {
        const drugPrice = (Math.round(drug.price * 100) / 100).toFixed(2);
        const drugAmount = (Math.round(drug.amount * 100) / 100).toFixed(2);

        drugListString += `<div class="row drug-item mt-1">
            <div class="col-6">${drug.name}</div>
            <div class="col-3">${drugPrice} x ${drug.quantity}</div>
            <div class="col-2 text-end">${drugAmount}</div>
            <div class="col-1">
                <button type="button" class="btn btn-close remove-drug-item" data-drug_id="${drug.id}"
                    aria-label="Close"></button>
            </div>
        </div>`;

        totalAmount += drug.amount;
    }

    totalAmount = (Math.round(totalAmount * 100) / 100).toFixed(2);

    $('.drug-list').html(drugListString);
    $('#drug-total-amount').html(`<strong>${totalAmount}</strong>`);
    setDrugStyle();
}

setDrugStyle = () => {
    $('.drug-header').removeClass('has-item');
    $('.drug-list').removeClass('has-item');
    $('.drug-total').removeClass('has-item');
    $('#send-quotation-button').attr('disabled', true);

    const drugListString = $('#drug-list').attr('value');
    const drugList = drugListString ? JSON.parse(drugListString) : {};

    if (Object.keys(drugList).length) {
        $('.drug-header').addClass('has-item');
        $('.drug-list').addClass('has-item');
        $('.drug-total').addClass('has-item');
        $('#send-quotation-button').attr('disabled', false);
    }
}

$(document).on('click', '.remove-drug-item', (event) => {
    const drugId = event.target.getAttribute('data-drug_id');
    const drugListField = $('#drug-list');
    let drugList = JSON.parse(drugListField.attr('value'));

    delete drugList[drugId];
    drugListField.attr('value', JSON.stringify({ ...drugList }));
    setDrugList(drugList);
});

$('#drug-quantity').on('keydown', (event) => {
    if (event.which === 13) {
        event.preventDefault();
    }
});

$('#send-quotation-button').on('click', (event) => {
    const drugListField = $('#drug-list');
    const drugListString = drugListField.attr('value');
    const drugList = drugListString ? JSON.parse(drugListString) : {};

    if (!Object.keys(drugList).length) {
        event.preventDefault();
    }
});

$('#confirm-quotation-delivered-button').on('click', (event) => {
    $('#confirm-quotation-delivered-modal').modal('show');
});

$('.hide-confirm-quotation-delivered-modal-button').on('click', (event) => {
    $('#confirm-quotation-delivered-modal').modal('hide');
});

$('#confirm-quotation-delivered-form').on('submit', (event) => {
    $('#confirm-quotation-delivered-modal').modal('hide');
});

$('.dropdown-toggle').on('click', (event) => {
    const toggleElementId = event.target.id;
    const toggleElement = $('#' + toggleElementId);
    const lastOpenedDropdown = $('#lastOpenedDropdown');
    const lastOpenedDropdownId = lastOpenedDropdown.data('last_opened_dropdown');
    const dropdownClass = '.dropdown-menu';

    if (lastOpenedDropdownId === toggleElementId) {
        toggleElement.next('.dropdown-menu').hide();
        lastOpenedDropdown.data('last_opened_dropdown', '');
    } else {
        $(dropdownClass).css('display', 'none');
        toggleElement.next('.dropdown-menu').show();
        lastOpenedDropdown.data('last_opened_dropdown', toggleElementId);
    }
});

$('body').on('click', (event) => {
    const elementId = event.target.id;
    const dropdownClass = '.dropdown-menu';
    let shouldHideDropdowns = true;

    if (elementId) {
        if ($('#' + elementId).hasClass('dropdown-toggle')) {
            shouldHideDropdowns = false;
        }
    }

    if (shouldHideDropdowns) {
        $(dropdownClass).css('display', 'none');
        $('#lastOpenedDropdown').data('last_opened_dropdown', '');
    }
});

$('.confirm-quotation-status-button').on('click', (event) => {
    const action = event.target.getAttribute('data-action');
    const route = event.target.getAttribute('data-route');
    const form = $('#confirm-quotation-status-form');
    const actionElement = $('#confirm-quotation-status-action');
    const submitButton = $('#confirm-quotation-status-button-submit');

    form.attr('action', route);
    actionElement.html(action === 'approve' ? 'approved' : 'rejected');
    submitButton.html(action === 'approve' ? 'Approve' : 'Reject');

    if (action === 'approve') {
        submitButton.removeClass('btn-danger').addClass('btn-success');
    } else {
        submitButton.removeClass('btn-success').addClass('btn-danger');
    }

    $('#confirm-quotation-status-modal').modal('show');
});

$('.hide-confirm-quotation-status-modal-button').on('click', (event) => {
    $('#confirm-quotation-status-modal').modal('hide');
});

$('#confirm-quotation-status-form').on('submit', (event) => {
    $('#confirm-quotation-status-modal').modal('hide');
});
