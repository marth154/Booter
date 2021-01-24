<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.css" rel="stylesheet"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<div class="list-group">
    <a  class="list-group-item active">
        <h4 class="list-group-item-heading">WordPress Theme Pro</h4>
        <p class="list-group-item-text">$ 15.00</p>
    </a>
</div>
<div class="list-group">
    <a  class="list-group-item">
        <h4 class="list-group-item-heading">WordPress Theme Premium</h4>
        <p class="list-group-item-text">$ 25.00</p>
    </a>
</div>
<div class="list-group">
    <a  class="list-group-item">
        <h4 class="list-group-item-heading">WordPress Theme Agency</h4>
        <p class="list-group-item-text">$ 35.00</p>
    </a>
</div>
<script>

$(document).ready(function() {
    $('.list-group-item').click(function() {
        $('.list-group-item').removeClass('active');
        $(this).closest('.list-group-item').addClass('active')
    });
});

</script>