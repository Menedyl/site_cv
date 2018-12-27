require('../../css/project/form.css');

$(document).ready(function () {

    var $ = require('jquery');

    var prototype = $('#picture_prototype').attr('data-prototype');
    var index = $("#pictures").children().length;


    function createField() {

        var field = $(prototype.replace(/__name__/g, index)).attr('class', 'col-md-3');


        $(field).find("input:file").on("change", function () {

            var img = $("<img class='img-thumbnail' src='' alt='#'>");
            $(this).after($(img));

            var reader = new FileReader();
            reader.onload = (function (e){
                $(img).attr('src' , e.target.result);
            });

            reader.readAsDataURL($(this)[0].files[0]);

            $(this).attr('hidden', 'true');
        });


        if (index !== 0) {
            var btnDelete = $("<button class='btn btn-danger delete' type='button'>Supprimer</button>");

            $(field).append(btnDelete);

            $(btnDelete).on("click", function () {
                deleteField($(this));
            });
        }

        $("#pictures").append(field);
    }

    function addField() {
        createField();
        index++;
    }

    function deleteField(field) {
        $(field).parent().remove();
    }


    if (index === 0) {
        addField();
    }


    $("#add_picture").on("click", function () {
        addField();
    });


    $(".delete").on("click", function () {
        deleteField($(this));
    });

});
