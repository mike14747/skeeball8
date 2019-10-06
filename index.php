<?php
require_once('connections/conn.php');
include('components/header/header.php');
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <div class="col-sm-4 p-0 float-right justify-content-end">
            <div class="card border-secondary float-right" style="max-width: 300px;">
                <div class="card-header bg-table-header text-center">
                    <b>
                        <?php
                        $text_box_heading
                        ?>
                    </b>
                </div>
                <div class="card-body pl-0 small">
                    <?php
                    echo $text_box_text;
                    ?>
                </div>
            </div>
            <img class="img-fluid mt-4 mb-4 pic1" src="images/pic1.jpg" alt="That's How I Roll" />
        </div>

        <?php
        if (isset($show_reg_button) && $show_reg_button == 1) {
            // display REGISTER NOW! button if button is set to be displayed
            echo '<p class="text-center">';
            echo '<a href="" . $reg_button_url . ""><img src="images/register_now.jpg" alt="REGISTER NOW!" /></a><br />';
            // the next line includes a result from the settings query in header.php
            echo $reg_button_text;
            echo '</p>';
        }
        // find page content for this page and display it
        if (isset($_GET['show']) && $_GET['show'] == "more") {
            $query_page_content = $conn->query("SELECT content_heading, page_content, DATE_FORMAT(text_date, '%M %d, %Y') AS text_date1 FROM store_text WHERE store_id=10 && display_content=1  ORDER BY text_date DESC, page_id DESC");
        } else {
            $query_page_content = $conn->query("SELECT content_heading, page_content, DATE_FORMAT(text_date, '%M %d, %Y') AS text_date1 FROM store_text WHERE store_id=10 && display_content=1 && (text_date>=NOW()-INTERVAL 90 DAY) ORDER BY text_date DESC, page_id DESC");
            $more_link = 1;
        }
        if ($query_page_content->num_rows > 0) {
            while ($result_page_content = $query_page_content->fetch_assoc()) {
                echo '<h5 class="text-danger mt-1 mb-0">' . strtoupper($result_page_content['content_heading']) . '</h5>';
                echo '<p><span class="small">' . $result_page_content['text_date1'] . '</span></p>';
                echo $result_page_content['page_content'];
                echo '<hr class="mt-4 mb-4" />';
            }
        }
        $query_page_content->free_result();
        if (isset($more_link) && $more_link = 1) {
            echo '<p class="text-center"><a href="index.php?show=more">...more Skeeball World Tour news...</a></p>';
        } else {
            echo '<p class="text-center"><a href="index.php">...less Skeeball World Tour news...</a></p>';
        }
        ?>

    </div>
</div>

<?php
include('components/footer/footer.php');
