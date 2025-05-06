//<![CDATA[
$(function() {
    $("#click_print").on('click', function() {
        $("#element_print").print({
            globalStyles: true,
            mediaPrint: false,
            stylesheet: "http://fonts.googleapis.com/css?family=Inconsolata",
            noPrintSelector: "#click_print"
        });
    });
});
//]]>