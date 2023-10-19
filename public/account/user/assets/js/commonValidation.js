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
            // return /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/.test(value)
            return /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,9}(:[0-9]{1,9})?(\/.*)?$/.test(value)
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
$.validator.addMethod('validPlayStore',

    function (value) {

        if (value != "") {
            return /^https:\/\/\play\.google\.com\/.*$/.test(value)
        } else {
            return true;
        }
    },
    'Please enter valid play store url'
);

/*$.validator.addMethod('validMapLink',

    function (value) {
        if (value != "") {
            return /^https?\:\/\/(www\.|maps\.)?google(\.[a-z]+){1,2}\/maps\/?\?([^&]+&)*(ll=-?[0-9]{1,2}\.[0-9]+,-?[0-9]{1,2}\.[0-9]+|q=[^&]+)+($|&)/.test(value)
        } else {
            return true;
        }
    },
    'Please enter valid map link'
);*/

$.validator.addMethod('validUpiId',

    function (value) {
        return /^[\.a-zA-Z0-9_-]{4,}@[a-zA-Z]{3,}/.test(value)
    },
    'Please enter valid upi id'
);

/*(?=.*[0-9].*[0-9])*/

/*$.validator.addMethod('validPassword',

    function (value) {
        return /^[A-Z](?=.*[!@#$&*])(?=.*[a-z]).{4,}$/.test(value)
    },
    'Your password contain First Letter Should be Capital and \n one special character .'
);*/

$.validator.addMethod('validContact',

    function (value) {
        return /^\d{10}$/.test(value)
    },
    'Please enter only 10 digit number'
);
$.validator.addMethod('validCustomUrl',
    function (value) {
        return /^[a-z0-9-]+$/i.test(value)
    },
    'Please enter valid custom url'
);


$().ready(function () {
    //add/edit-company-validation
    $("#sms_verification").validate({
        rules: {
            sms_contact: {
                required: true,
                validContact: true,
                minlength: 10,
                maxlength: 10

            }
        }
    });
    $("#custom_url").validate({
        rules: {
            custom_url_preview: {
                validCustomUrl: true,
                minlength: 4,
                maxlength: 100
            }
        }
    });
    $("#dealer_login").validate({
        rules: {
            sms_contact: {
                required: true,
                validContact: true,
                minlength: 10,
                maxlength: 10
            }
        }
    });
    $("#registration_form").validate({
        rules: {
            txt_password: {
                required: true,
                minlength: 4,
                maxlength: 15
            }
        }
    });


    $("#basic_user_info").validate({
        rules: {
            basic_email: {
                validEmail: true
            },
            /*txt_contact: {
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
            },*/
            basic_website: {
                required: false,
                validUrl: true
            }
            /*txt_linked: {
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
            },
            txt_playstore: {
                validPlayStore: true
            }*/
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
                minlength: 10,
                maxlength: 10
            }
        }
    });
});