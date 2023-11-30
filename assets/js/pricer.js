jQuery(function ($) {
    $('.url-lm-wrapper').dependsOn({'#price-settings-load_method':{values:['url']}},{'toggleClass':'dependenced'});
    $('.email-lm-wrapper').dependsOn({'#price-settings-load_method':{values:['email']}},{'toggleClass':'dependenced'});
    $('.xml-rm-wrapper').dependsOn({'#price-settings-read_method':{values:['xml']}},{'toggleClass':'dependenced'});
    $('.csv-rm-wrapper').dependsOn({'#price-settings-read_method':{values:['csv']}},{'toggleClass':'dependenced'});
});
