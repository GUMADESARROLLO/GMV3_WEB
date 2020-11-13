<!-- Footer Starts -->
<!--footer start-->
<footer class="admin-footer">
 <div class="container-fluid">
 	<ul class="list-unstyled list-inline">
	 	<li>
			<span class="pmd-card-subtitle-text">IT Developer &copy; <span class="auto-update-year"></span>. Todos los Derechos Reservados.</span>
			<h3 class="pmd-card-subtitle-text"><a href="#!" target="_blank">PLATAFORMA DE PEDIDOS - UNIMARK v3.0.1</a></h3>
        </li>
        <li class="pull-right for-support">
			<a href="mailto:elcorreo@gmail.com">

            	<div>
				  <span class="pmd-card-subtitle-text">para Soporte</span>
				  <h3 class="pmd-card-subtitle-text">analista.guma@gmail.com</h3>
				</div>
            </a>
        </li>
    </ul>
 </div>
</footer>
<!--footer end-->
<!-- Footer Ends -->
<script src="assets/js/jquery-1.12.2.min.js"></script>
<script src="assets/js/jquery.dataTables.js"></script>
<script src="assets/js/dataTables.bootstrap4.js"></script>

<!-- Scripts Starts -->

<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/propeller.min.js"></script>
<script src="assets/js/dropify.js"></script>




<script>
	$(document).ready(function() {

        $("#Id_Buscar_Orden").on('keyup',function(){
            var table = $('#dtPedidos').DataTable();
            table.search(this.value).draw();
        });
        $("#Id_Buscar_articulo").on('keyup',function(){
            var table = $('#dtArticulos').DataTable();
            table.search(this.value).draw();
        });

        $( "#frm_lab_row").change(function() {
            var table = $('#dtPedidos').DataTable();
            table.page.len(this.value).draw();
        });
        $( "#selct_estados").change(function() {
            var table = $('#dtPedidos').DataTable();
            //var selectedValue = this.selectedOptions[0].value;
            var selectedText  = this.selectedOptions[0].text;
            if(selectedText == "Todo"){
                table.search("").draw();
            }else{
                table.search(selectedText).draw();
            }

        });

        function descargarArchivo() {

        }
        $( "#exp-to-excel" ).click(function() {

            var F1 = $("#xls_f1").text();
            var F2 = $("#xls_f2").text();



            window.open("public/exp-to-excel.php?D1="+ F1 +"&D2="+ F2 ,"_blanck");

        })

        $( "#id_redo-alt" ).click(function() {
            Load_Pedido();
        });

        Load_Pedido();










		var sPath=window.location.pathname;
		var sPage = sPath.substring(sPath.lastIndexOf('/') + 1);
		$(".pmd-sidebar-nav").each(function(){
			$(this).find("a[href='"+sPage+"']").parents(".dropdown").addClass("open");
			$(this).find("a[href='"+sPage+"']").parents(".dropdown").find('.dropdown-menu').css("display", "block");
			$(this).find("a[href='"+sPage+"']").parents(".dropdown").find('a.dropdown-toggle').addClass("active");
			$(this).find("a[href='"+sPage+"']").addClass("active");
		});
		$(".auto-update-year").html(new Date().getFullYear());
	});



	function Load_Pedido() {
        $('#dtPedidos').DataTable({
            ajax: 'public/dt_Pedidos.php',
            "destroy": true,
            "ordering": true,
            "info": false,
            "bPaginate": true,
            "bfilter": false,
            "searching": true,
            "pagingType": "full_numbers",
            "aaSorting": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [10, 10, -1],
                [10, 30, "Todo"]
            ],
            "columnDefs": [
                { "visible": false, "targets": 6 }
            ],
            "language": {
                "zeroRecords": "NO HAY RESULTADOS",
                "paginate": {
                    "first":      "Primera",
                    "last":       "Última ",
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },
                "lengthMenu": "_MENU_",
                "emptyTable": "NO HAY DATOS DISPONIBLES",
                "search":     "BUSCAR"
            },

            columns: [
                { "data": "N" },
                { "data": "name" },
                { "data": "FullName" },
                { "data": "order_total" },
                { "data": "date_time" },
                { "data": "status" },
                { "data": "Estado" },
                { "data": "permisos" }
            ],
            "fnInitComplete": function (oSettings, json){


                Pendiente = 0;
                Procesado = 0;
                Cancelado = 0;

                $.each(json['data'], function(i, x) {
                    if (x['Estado'] == 0){
                        Pendiente++;
                    }else if(x['Estado'] == 1){
                        Procesado++;
                    }else{
                        Cancelado++;
                    }

                });


                $("#txt_procesado").html(Procesado);
                $("#txt_pendiente").html(Pendiente);
                $("#txt_cancelado").html(Cancelado);

                $("#dtPedidos_filter").hide();
                $("#dtPedidos_length").hide();
            }
        });
    }
</script> 
<!-- Select2 js-->
<script type="text/javascript" src="assets/plugins/select2/js/select2.full.js"></script>

<!-- Propeller Select2 -->
<script type="text/javascript">
	$(document).ready(function() {
		<!-- Simple Selectbox -->
		$(".select-simple").select2({
			theme: "bootstrap",
			minimumResultsForSearch: Infinity,
		});
		<!-- Selectbox with search -->
		$(".select-with-search").select2({
			theme: "bootstrap"
		});
		<!-- Select Multiple Tags -->
		$(".select-tags").select2({
			tags: false,
			theme: "bootstrap",
		});
		<!-- Select & Add Multiple Tags -->
		$(".select-add-tags").select2({
			tags: true,
			theme: "bootstrap",
		});
	});
</script>
<script type="text/javascript" src="assets/plugins/select2/js/pmd-select2.js"></script>
<script>
	$(document).ready(function() {
		var sPath=window.location.pathname;
		var sPage = sPath.substring(sPath.lastIndexOf('/') + 1);
		$(".pmd-sidebar-nav").each(function(){
			$(this).find("a[href='"+sPage+"']").parents(".dropdown").addClass("open");
			$(this).find("a[href='"+sPage+"']").parents(".dropdown").find('.dropdown-menu').css("display", "block");
			$(this).find("a[href='"+sPage+"']").parents(".dropdown").find('a.dropdown-toggle').addClass("active");
			$(this).find("a[href='"+sPage+"']").addClass("active");
		});
	});
</script>
<!-- login page sections show hide -->
<script type="text/javascript">
	$(document).ready(function(){
	 $('.app-list-icon li a').addClass("active");
		$(".login-for").click(function(){
			$('.login-card').hide()
			$('.forgot-password-card').show();
		});
		$(".signin").click(function(){
			$('.login-card').show()
			$('.forgot-password-card').hide();
		});
	});
</script>
<!-- Scripts Ends -->

<script>
            $(document).ready(function(){
                // Basic
                $('.dropify').dropify();

                // Translated
                $('.dropify-fr').dropify({
                    messages: {
                        default: 'Glissez-déposez un fichier ici ou cliquez',
                        replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                        remove:  'Supprimer',
                        error:   'Désolé, le fichier trop volumineux'
                    }
                });

                $('.dropify-image').dropify({
                    messages: {
                        default : '<center>Drag and drop a image here or click</center>',
                        error   : 'Ooops, something wrong appended.'
                    },
                    error: {
                        'fileSize': '<center>The file size is too big broo ({{ value }} max).</center>',
                        'minWidth': '<center>The image width is too small ({{ value }}}px min).</center>',
                        'maxWidth': '<center>The image width is too big ({{ value }}}px max).</center>',
                        'minHeight': '<center>The image height is too small ({{ value }}}px min).</center>',
                        'maxHeight': '<center>The image height is too big ({{ value }}px max).</center>',
                        'imageFormat': '<center>The image format is not allowed ({{ value }} only).</center>',
                        'fileExtension': '<center>The file is not allowed ({{ value }} only).</center>'
                    },
                });

                $('.dropify-video').dropify({
                    messages: {
                        default: '<center>Drag and drop a video here or click</center>'
                    }
                });

                $('.dropify-notification').dropify({
                    messages: {
                        default : '<center>Drag and drop a image here or click<br>(Optional)</center>',
                    }
                });

                // Used events
                var drEvent = $('#input-file-events').dropify();

                drEvent.on('dropify.beforeClear', function(event, element){
                    return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
                });

                drEvent.on('dropify.afterClear', function(event, element){
                    alert('File deleted');
                });

                drEvent.on('dropify.errors', function(event, element){
                    console.log('Has Errors');
                });

                var drDestroy = $('#input-file-to-destroy').dropify();
                drDestroy = drDestroy.data('dropify')
                $('#toggleDropify').on('click', function(e){
                    e.preventDefault();
                    if (drDestroy.isDropified()) {
                        drDestroy.destroy();
                    } else {
                        drDestroy.init();
                    }
                })
            });
</script>
<script src="assets/js/daterangepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        <!-- Simple Selectbox -->
        $(".select-simple").select2({
            theme: "bootstrap",
            minimumResultsForSearch: Infinity,
        });
        <!-- Selectbox with search -->
        $(".select-with-search").select2({
            theme: "bootstrap"
        });
        <!-- Select Multiple Tags -->
        $(".select-tags").select2({
            tags: false,
            theme: "bootstrap",
        });
        <!-- Select & Add Multiple Tags -->
        $(".select-add-tags").select2({
            tags: true,
            theme: "bootstrap",
        });
    });
</script>
</body>
</html>