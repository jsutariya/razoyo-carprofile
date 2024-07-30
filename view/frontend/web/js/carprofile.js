define([
    'jquery',
    'mage/url',
    'Magento_Ui/js/modal/modal'
], function ($, url, modal) {
    'use strict';

    $(document).ready(function () {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: 'Select Car'
        };

        var myModal = modal(options, $('#car-details-popup'));

        $('#change-your-car').on("click", function (event) {
            $('body').loader('show');
            event.preventDefault();
            $.ajax({
                url: url.build('carprofile/mycar/get'),
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $('body').loader('hide');
                        $('#car-details-popup').html(response.html);
                        myModal.openModal();
                    } else {
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error saving saved car: ' + errorThrown);
                }
            });
        });

        $(document).on("click", ".select-car", function (event) {
            var self = $(this);
            $('body').loader('show');
            event.preventDefault();

            var formData = {
                car_id: $(this).data("carid")
            };

            $.ajax({
                url: url.build('carprofile/mycar/save'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    $('.car-container').removeClass("selected");
                    $('body').loader('hide');
                    if (response.success) {
                        self.parent().parent(".car-container").addClass("selected");
                    } else {
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error saving saved car: ' + errorThrown);
                }
            });
        });
    });
});
