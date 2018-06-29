var sliders = {};
var info_notify = null;
$(document).ready(function () {

    verifyNewComments();

    if ($(window).width() < 768) {
        $("body").toggleClass('nav-md nav-sm');
    }

    $().fancybox({
        selector: '[data-fancybox="images"]',
        loop: true
    });

    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '< Ant',
        nextText: 'Sig >',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd-mm-yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    $(function () {
        from = $(".date-from")
                .datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 1
                })
                .on("change", function () {
                    to.datepicker("option", "minDate", getDate(this));
                }),
                to = $(".date-to").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1
        })
                .on("change", function () {
                    from.datepicker("option", "maxDate", getDate(this));
                });

        function getDate(element) {
            var date;
            try {
                date = $.datepicker.parseDate(dateFormat, element.value);
            } catch (error) {
                date = null;
            }

            return date;
        }
    });

    $(document).on('click', ".ntf-side", function (event) {
        $('.loader-div').removeClass('hidden');
        $(this).find('i').remove();

        var url = $(this).attr("href");
        $.ajax({
            url: url,
            dataType: "html",
            success: function (data) {
                $('.loader-div').addClass('hidden');
                $('#inbox-container').html(data);
            }
        });

        return false;
    });

    // Acción de votación
    $(document).on('click', ".face-rate", function (event) {
        event.preventDefault();
        info_notify.close();
        var url = $(this).attr("href");
        $.ajax({
            url: url,
            success: function (data) {

                if (data.status == "ok") {
                    // Notificación de voto correcto
                    $.notify(
                            {
                                message: "Comentado votado como: " + data.rate
                            }, {
                        type: "success"
                    }
                    );
                    verifyNewComments();
                }

            }
        });

        return false;
    });



    if ($(".js-datetimepicker").length) {
        $(".js-datetimepicker").datetimepicker({
            startDate: Date.now()
        });
    }

    if ($(".js-datepicker").length) {
        $(".js-datepicker").datetimepicker({
            minView: 2,
            format: 'yyyy-mm-dd',
            startDate: Date.now()
        });
    }
    if ($(".js-timepicker").length) {
        $(".js-timepicker").datetimepicker({
            startView: 1,
            maxView: 1,
            minuteStep: 15,
            format: "hh:ii",
            forceParse: true,
            autoclose: true,
            todayBtn: false
        });
    }

    if ($(".slider-content").length) {
        $(".slider-content").each(function () {
            var sliderName = $(this).data('slider-name');
            sliders[sliderName] = $(this).bxSlider({
                slideMargin: $(this).data('slide-margin') || 0,
                moveSlides: $(this).data('move-slides') || 1,
                mode: $(this).data('mode') || 'horizontal',
                slideWidth: $(this).data('slide-width') || 0,
                maxSlides: $(this).data('max-slides') || 1,
                minSlides: $(this).data('max-slides') || 1,
                auto: hasData($(this).data('auto')) ? $(this).data('auto') : true,
                pager: hasData($(this).data('pager')) ? $(this).data('pager') : false || true,
                controls: hasData($(this).data('controls')) ? $(this).data('controls') : false || true,
                speed: $(this).data('speed') || 800,
                responsive: hasData($(this).data('responsive')) ? $(this).data('responsive') : false || true,
                adaptiveHeight: hasData($(this).data('adaptive-height')) ? $(this).data('adaptive-height') : false || false,
                touchEnabled: false
            });
        });
    }

    $(document).on('change', ':file', function () {
        var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    $(document).on('fileselect', ':file', function (event, numFiles, label) {
        $(this).closest('label').addClass('with-file');
        $(this).closest('label').find('.btn-text').html('Foto seleccionada: ' + label);
    });

    if ($("#we_reportbundle_activacion_proyecto").length) {
        $(document).on('change', '#we_reportbundle_activacion_proyecto', function () {
            var projectId = $(this).val();
            $(".project-info-wrap").addClass('hidden');
            $(".project-id-" + projectId).removeClass('hidden');

            var fechaInicio = $(".project-id-" + projectId).data('fecha-inicio');
            var fechaFinal = $(".project-id-" + projectId).data('fecha-final');

            var dateToday = new Date();
            var dateInicio = new Date(fechaInicio);
            var dateFinal = new Date(fechaFinal);

            if (dateInicio < dateToday && dateToday < dateFinal) {
                fechaInicio = dateToday;
            }
            if (dateToday > dateFinal) {
                $(".js-datepicker").val("");
                $(".js-datepicker").prop('disabled', 'disabled');
            } else {
                $(".js-datepicker").prop('disabled', false);
            }

            $(".js-datepicker").datetimepicker('setStartDate', fechaInicio);
            $(".js-datepicker").datetimepicker('setEndDate', fechaFinal);

        });
    }

});

function hasData(data) {
    return  typeof (data) !== undefined && data !== null ? true : false;
}

function verifyNewComments() {
    // Verifica si hay nuevos comentarios
    var urlVerify = $('#verify_rate').attr("href");
    $.ajax({
        url: urlVerify,
        dataType: "html",
        success: function (data) {
            console.log("datos de html");
            console.log(data);
            if (data != null && data != " " && data.length > 0) {
                info_notify = $.notify({
                    title: "<strong>Hay nuevos comentarios pendientes a votar: </strong>",
                    message: data
                }, {
                    delay: 0
                }
                );
            }
        }
    });
}