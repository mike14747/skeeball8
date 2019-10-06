<?php
require_once("connections/conn1.php");
require_once("admin_prehead.php");
include("admin_header.php");
?>

<div class="row">
    <div class="col-sm-12 pt-4 pb-4">
        <h2 class="text-danger">Error 404</h2>
        <hr />
        <p class="text-danger">An error has occurred. The page you are looking for does not exist!</p>
    </div>
</div>

<?php
include('components/footer/footer.php');
