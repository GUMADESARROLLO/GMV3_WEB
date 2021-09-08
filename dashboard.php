<?php include 'session.php'; ?>
<?php include 'public/menubar.php'; ?>
<style>
    .custom-height-modal {
        width: 1000px

    }</style>
<?php include 'public/dashboard-menu.php'; ?>
    <!-- Page level plugins -->
    <script src="assets/js/Chart.js"></script>
    <script src="assets/js/chart-area-demo.js"></script>
    <script src="assets/js/chart-bar-demo.js"></script>

<?php include 'public/footer.php'; ?>
<script>
    $(document).ready(function() {
        Graph_bar();
        Graph_linea();
    });

</script>
