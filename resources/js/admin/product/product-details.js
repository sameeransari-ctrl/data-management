let products = {
    init: function () {
        products.readMore();
        products.reviewDetails();
    },

    readMore: function() {
        $(document).on('click', '.readMoreBtn', function () {
            var ratingId = $(this).data("id");
            var dots = document.getElementById("dots-" + ratingId);
            var targetElement = $('[data-id="' + ratingId + '"]');
            if (dots.style.display === "none") {
                dots.style.display = "inline";
                targetElement.html('...Read more');
                $('#more-read-more-' + ratingId).addClass('d-none');
            } else {
                dots.style.display = "none";
                targetElement.html('...Read less');
                $('#more-read-more-' + ratingId).removeClass('d-none');
            }
        });
    },

    reviewDetails: function() {
        $(document).on('click', '#review-detail', function () {
            productId = $(this).attr('data-product_id');
            let btn = $('#review-detail');
            let btnName = btn.html();
            $.ajax({
                method: "GET",
                url: route('admin.product.rating-details', productId),
                beforeSend: function () {
                    showButtonLoader(btn, btnName, true);
                },
                success: function (response) {
                    $('#reviewRating').html(response).modal('show');
                    showButtonLoader(btn, btnName, false);
                },
                error: function (response) {
                    handleError(response);
                },
            });
        });
    }
};

$(function () {
    products.init();
});
