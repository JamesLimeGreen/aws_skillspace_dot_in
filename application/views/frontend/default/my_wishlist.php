<?php include "profile_menus.php"; ?>

<section class="my-courses-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="my-course-search-bar">
                    <form action="">
                        <div class="input-group">
                            <input type="text" class="form-control py-2" placeholder="<?php echo site_phrase('search_my_courses'); ?>" onkeyup="getMyWishListsBySearchString(this.value)">
                            <div class="input-group-append">
                                <button class="btn py-2" type="button"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row no-gutters" id="my_wishlists_area">

            <?php include "reload_my_wishlists.php"; ?>

        </div>
    </div>
</section>

<script type="text/javascript">
    function getMyWishListsBySearchString(search_string) {
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('home/get_my_wishlists_by_search_string'); ?>',
            data: {
                search_string: search_string
            },
            success: function(response) {
                $('#my_wishlists_area').html(response);
            }
        });
    }

    async function handleWishListDel(elem) {
        try {
            var result = await async_modal();
            if (result) {
                $.ajax({
                    url: '<?php echo site_url('home/handleWishList'); ?>',
                    type: 'POST',
                    data: {
                        course_id: elem.id
                    },
                    success: function(response) {
                        if ($(elem).hasClass('active')) {
                            $(elem).removeClass('active');
                        } else {
                            $(elem).addClass('active'); 
                        }
                        $('#wishlist_items').html(response);
                        $.ajax({
                            url: '<?php echo site_url('home/reload_my_wishlists'); ?>',
                            type: 'POST',
                            success: function(response) {
                                $('#my_wishlists_area').html(response);
                            }
                        });
                    }
                });
            }
        } catch (e) {
            console.log("Error occured", e.message);
        }
    }
</script>