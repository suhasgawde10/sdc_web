$.validator.addMethod('validName',

    function (value) {
        if (value != "") {
            return /^(\S[a-zA-Z0-9 "!?.-]+)$/.test(value)
        }
        else {
            return true;
        }
    },
    'Please enter a valid name'
);
$.validator.addMethod('validEmail',

    function (value) {
        if (value != "") {
            return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)
        } else {
            return true;
        }
    },
    'Please enter a valid email address'
);


$.validator.addMethod('validUrl',

    function (value) {
        if (value != "") {
            return /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/.test(value)
        } else {
            return true;
        }
    },
    'Please enter valid url'
);

$.validator.addMethod('validLinkedInUrl',

    function (value) {
        if (value != "") {
            return /^https:\/\/[a-z]{2,3}\.linkedin\.com\/.*$/.test(value)
        }
        else {
            return true;
        }
    },
    'Please enter valid linked in url'
);
$.validator.addMethod('validInstagramUrl',

    function (value) {
        if (value != "") {
            return /^https:\/\/\www\.instagram\.com\/.*$/.test(value)
        } else {
            return true;
        }
    },
    'Please enter valid instagram url'
);
$.validator.addMethod('validTwitter',

    function (value) {
        if (value != "") {
            return /^https:\/\/twitter\.com\/.*$/.test(value)
        } else {
            return true;
        }
    },
    'Please enter valid twitter url'
);
$.validator.addMethod('validFacebook',

    function (value) {
        if (value != "") {
            return /^https:\/\/\www\.facebook\.com\/.*$/.test(value)
        } else {
            return true;
        }
    },
    'Please enter valid facebook url'
);
$.validator.addMethod('validYoutube',

    function (value) {
        if (value != "") {
            return /^https:\/\/\www\.youtube\.com\/.*$/.test(value)
        } else {
            return true;
        }
    },
    'Please enter valid youtube url'
);
$.validator.addMethod('validMapLink',

    function (value) {
        if (value != "") {

            return /^https:\/\/\www\.google\.com\/\maps\/.*$/.test(value)
        } else {
            return true;
        }
    },
    'Please enter valid map link'
);

$.validator.addMethod('validUpiId',

    function (value) {
        return /^\w+@\w+$/.test(value)
    },
    'Please enter valid upi id'
);


$().ready(function () {
    //add/edit-company-validation
    $("#basic_user_info").validate({
        rules: {
            basic_email: {
                validEmail: true
            },
            txt_name: {
                validName: true
            },
            txt_contact: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            txt_alt_contact: {
                required: false,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            txt_linked: {
                required: false,
                validLinkedInUrl: true
            },
            txt_instagram: {
                required: false,
                validInstagramUrl: true
            },
            basic_twitter: {
                required: false,
                validTwitter: true
            },
            basic_facebook: {
                required: false,
                validFacebook: true
            },
            basic_youtube: {
                required: false,
                validYoutube: true
            },
            txt_map: {
                validMapLink: true
            }


        }
    });
    //add/edit-project
    $("#upi_form_validation").validate({
        rules: {
            txt_upi_id: {
                validUpiId: true
            },
            txt_upi_number: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            }
        }
    });
    $("#contact_reset").validate({
        rules: {
            new_contact: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            }
        }
    });
    $("#register_number").validate({
        rules: {
            sms_contact: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            }
        }
    });
});