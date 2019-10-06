<?php
require_once('connections/conn.php');
include('components/header/header.php');
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <h2 class="text-danger">Error 502</h2>
        <hr />
        <p class="text-danger">Bad Gateway. The web server is temporary overloaded and can't process your request. Please try to access the site later.</p>
    </div>
</div>

<?php
include('components/footer/footer.php');
