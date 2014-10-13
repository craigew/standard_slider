<div class="wrap">

<h2>Standard Slider</h2>

<h3>Administration</h3>

<?php
$postId = "";
if (isset($_POST['Add_New_Slider'])) {
    $optional = array();
    show_slider_form($optional);
} elseif (isset($_POST['update_image'])) {
    update_slider_image_details();
    show_slider_form(array("postId" => $_POST['edit_post_id']));
} elseif (isset($_POST['save-slider-settings'])) {
    add_slider();
    show_slider_list();
} elseif (isset($_POST['delete_image'])) {
    delete_slider_image($_POST['edit_id']);
    show_slider_form(array("postId" => $_POST['edit_post_id']));
} elseif (isset($_POST['delete_slider'])) {
    delete_slider($_POST['manage_slider']);
    show_slider_list();
} elseif (isset($_POST['edit_slider'])) {
    $optional = array("postId" => $_POST['manage_slider']);
    show_slider_form($optional);
} elseif (isset($_POST['attachment_id']) && isset($_POST['post_id'])) {
    associate_image_as_attachment($_POST['attachment_id'], $_POST['post_id']);
    $optional = array("postId" => $_POST['post_id']);
    show_slider_form($optional);
} else {
    show_slider_list();
}

?>



<?php
function show_slider_list()
{
    ?>
    <form id="slider_list" method="post">
        <table class="wp-list-table widefat fixed posts">
            <thead>
            <tr>
                <th>
                    Title
                </th>
                <th>
                    Description
                </th>
                <th>
                    Short Code
                </th>
                <th width="60px">
                    Manage
                </th>
            </tr>
            </thead>
            <tbody>
            <?php
            $type = 'standard_slider';
            $args = array(
                'post_type' => $type,
                'post_status' => 'publish');

            $my_query = null;
            $my_query = new WP_Query($args);
            if ($my_query->have_posts()) {
                $alt = 0;
                while ($my_query->have_posts()) : $my_query->the_post();
                    if ($alt % 2 == 0) {
                        $style = "alternate";

                    } else {
                        $style = "";
                    }
                    $alt += 1;
                    ?>
                    <tr valign="top" class="hentry <?php echo $style ?>">
                        <td><?php the_title(); ?></td>
                        <td><?php the_content(); ?></td>
                        <td>[crafted-software-standard-slider id=<?php the_ID(); ?>]</td>
                        <td><input id="manage_slider" type="radio" name="manage_slider"
                                   value=<?php the_ID(); ?>/></td>
                    </tr>
                <?php
                endwhile;
            }
            wp_reset_query();
            ?>
            </tbody>
        </table>
        <div class="tablenav bottom">
            <input type="submit" id="Add_New_Slider" name="Add_New_Slider" value="Add New Slider"
                   class="button"/>
            <input type="submit" id="editSlider" name="edit_slider" value="Edit Slider"
                   class="button"/>
            <input type="submit" id="deleteSlider" name="delete_slider" value="Delete Slider"
                   class="button"/>
        </div>
    </form>
<?php
}

function show_slider_form($optional)
{
    $post = null;
    $hide_add_image_button = "hide";
    if ((isset($optional["postId"])) && ($optional["postId"] != "")) {
        $post = get_post($optional["postId"]);
        $title = $post->post_title;
        $content = $post->post_content;
        $hide_add_image_button = "";
    } else {
        $title = "";
        $content = "";
    }

    ?>
    <form id="slider_detail" method="post">
        <table class="form-table">
            <tr>
                <th scope="row">Slider Name</th>
                <td><input type="text" id="slider_name" name="slider_name" value=" <?php echo $title ?>"/></td>
            </tr>
            <tr>
                <th scope="row">Slider Description</th>
                <td><input type="text" id="slider_desc" name="slider_desc" value=" <?php echo $content ?>"/></td>

            <tr>
                <td colspan="2">
                    <input type="submit" name="save-slider-settings" id="save-slider-settings" class="button" value="Save Settings">
                    <input type="button" id="addSliderImage" name="addSliderImage" value="Add New Image"
                           class="button <? echo $hide_add_image_button ?>"/></td>
            </tr>
            <?php
            if ($post != null) {
                $images =& get_children(array(
                    'post_parent' => $post->ID,
                    'post_type' => 'attachment',
                    'post_mime_type' => 'image'
                ));

                if (!empty($images)) {
                    foreach ($images as $attachment_id => $attachment) {
                        ?>
                        <tr>
                            <td colspan="2">

                                <form method="post">
                                    <input type="hidden" name="edit_post_id" value="<?php echo $optional["postId"] ?>"
                                           id="edit_post_id"/>
                                    <input type="hidden" name="edit_id" id="edit_id"
                                           value='<?php echo($attachment_id) ?>'/>
                                    <table class="edit_slider_image_list_table">
                                        <tr>
                                            <td><?php echo wp_get_attachment_image($attachment_id, 'full'); ?></td>
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td>Title</td>
                                                        <td><input type="text"
                                                                   name="title_<?php echo($attachment_id) ?>"
                                                                   id="title_<?php echo($attachment_id) ?>"
                                                                   value="<?php echo $attachment->post_title ?>"/></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Caption</td>
                                                        <td><input type="text"
                                                                   name="caption_<?php echo($attachment_id) ?>"
                                                                   id="caption_<?php echo($attachment_id) ?>"
                                                                   value="<?php echo $attachment->post_excerpt ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Description</td>
                                                        <td><input type="text" name="desc_<?php echo($attachment_id) ?>"
                                                                   id="desc_<?php echo($attachment_id) ?>"
                                                                   value="<?php echo $attachment->post_content ?>"/>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <input type="submit" value="Update Image Details" class="button"
                                                       name="update_image"/>
                                                <input type="submit" value="Delete Image" class="button"
                                                       name="delete_image"/>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </td>
                        </tr>
                    <?php
                    }
                }
            }
            ?>

            <tr>
                <td colspan="2">
                    <input type="hidden" name="post_id" value="<?php echo $optional["postId"] ?>" id="post_id"/>
                    <input type="hidden" name="attachment_id" id="attachment_id"/>

                </td>
            </tr>
        </table>
    </form>
<?php
}

function add_slider()
{
    if ((isset($_POST['post_id'])) && ($_POST['post_id'] != "")) {
        wp_update_post(array('ID' => str_replace("/", "", $_POST['post_id']),
            'post_title' => $_POST['slider_name'],
            'post_content' => $_POST['slider_desc']));
    } else {
        $post_id = wp_insert_post(array(
            'post_type' => 'standard_slider',
            'post_title' => $_POST['slider_name'],
            'post_content' => $_POST['slider_desc'],
            'post_status' => 'publish',
            'comment_status' => 'closed', // if you prefer
            'ping_status' => 'closed', // if you prefer
        ));
    }

}

function update_slider_image_details()
{
    wp_update_post(array('ID' => str_replace("/", "", $_POST['edit_id']),
        'post_title' => $_POST['title_' . $_POST['edit_id']],
        'post_excerpt' => $_POST['caption_' . $_POST['edit_id']],
        'post_content' => $_POST['desc_' . $_POST['edit_id']]));
}

function associate_image_as_attachment($image_id, $slider_id)
{
    $attachment = array(
        'ID' => $image_id,
        'post_parent' => $slider_id
    );

    wp_update_post($attachment);
}

function delete_slider($postId)
{
    wp_delete_post($postId);
}

function delete_slider_image($postId)
{
    wp_delete_post($postId);
}

?>

</div>