
$.validator.addMethod('space',

    function (value) {
            return /^\S*$/.test(value)
    },
    'Please enter some text'
);


$().ready(function () {
    //add/edit-company-validation
    $("#globalSearch").validate({
        rules:{
            city: {
                required: true
            },
            search:{
                required: false,
                space : true
            }
        }
    });
});