$(function() {
    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
    $("#dialog").dialog("destroy");

    var name = $("#name"),
    tweet = $("#tweet"),
    allFields = $([]).add(name).add(tweet),
    tips = $(".validateTips");

    function updateTips(t) {
        tips
        .text(t)
        .addClass('ui-state-highlight');
        setTimeout(function() {
            tips.removeClass('ui-state-highlight', 1500);
        }, 500);
    }

    function checkLength(o,n,min,max) {

        if ( o.val().length > max || o.val().length < min ) {
            o.addClass('ui-state-error');
            updateTips("Length of " + n + " must be between "+min+" and "+max+".");
            return false;
        } else {
            return true;
        }

    }

    function checkRegexp(o,regexp,n) {

        if ( !( regexp.test( o.val() ) ) ) {
            o.addClass('ui-state-error');
            updateTips(n);
            return false;
        } else {
            return true;
        }

    }

    $("#dialog-form").dialog({
        autoOpen: false,
        height: 300,
        width: 250,
        modal: true,
        buttons: {
            'OK': function() {
                var bValid = true;
                allFields.removeClass('ui-state-error');

                bValid = bValid && checkLength(name,"Name",1,5);
                bValid = bValid && checkLength(tweet,"Tweet",1,10);

                bValid = bValid && checkRegexp(name,/^[a-z]([0-9a-z_])+$/i,"Name may consist of a-z, 0-9, underscores, begin with a letter.");

                if (bValid) {
                    $.ajax({
                        type: "GET",
                        url: "wp-content/plugins/JQueryAccessibleDialog/addTweetAjax.php",
                        data: "name=" + name.val() + "&tweet=" + tweet.val(),
                        dataType: "json",
                        success: function(msg){
                            
                        }
                    });
                    $('#users tbody').append('<tr>' +
                        '<td>' + name.val() + '</td>' +
                        '<td>' + tweet.val() + '</td>' +
                        '</tr>');
                    $(this).dialog('close');
                }
            },
            Cancel: function() {
                $(this).dialog('close');
            }
        },
        close: function() {
            allFields.val('').removeClass('ui-state-error');
        }
    });

    $('#create-user')
    .button()
    .click(function() {
        $('#dialog-form').dialog('open');
    });

});