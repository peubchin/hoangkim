$(function () {
    $('#prd').raty({
        path: base_url + 'assets/plugins/raty/img',
        number: 5,
        starOff: 'star-off-big.png',
        starOn: 'star-on-big.png',
        width: 180,
        scoreName: "score",
        hints: ['bad1', 'poor', 'regular', 'good', 'gorgeous'],
    });
});
$(document).on('click', '#submit', function () {
    var score = $("#score").val();
    var product_id = $("form#product_addtocart_form").find('input[name=product_id]').val();
    if (score.length > 0) {
        $.post(base_url + "shops/rating/add", {
            product_id: product_id,
            user_id: "12",
            score: score
        }, function (data) {
            $("#all_rating").html('');
            $("#all_rating").html(data);
            $('#prd').raty({
                path: base_url + 'assets/plugins/raty/img',
                readOnly: true,
                number: 5,
                starOff: 'star-off-big.png',
                starOn: 'star-on-big.png',
                width: 180,
                hints: ['bad1', 'poor', 'regular', 'good', 'gorgeous'],
                score: score
            });
        });
    } else {
        alert("Select the ratings.");
    }
});