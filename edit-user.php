<?php include 'session.php'; ?>
<?php include 'public/menubar.php'; ?>
<!-- <script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script> -->
<script src="assets/js/ckeditor/ckeditor.js"></script>

<?php include 'public/edit-user-form.php'; ?>
<?php include 'public/footer.php'; ?>
<script type="text/javascript">

	$("#btn_save_ruta").click(function(){      
        var form_data = 
                
                    {
                        "mRuta"         : $( "#form_ruta option:selected" ).text(),
                        "mUser"           : $( "#txt_id_user" ).val()
                    }
                
            ;
            $.ajax({
                url: "add-ruta.php",
                type: 'post',
                async: true,
                data: form_data,
                success: function(data) {
                    location.reload();
                }
            });

       
    });
</script>