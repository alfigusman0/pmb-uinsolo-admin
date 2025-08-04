<!-- DataTables -->
<script src="<?= base_url() ?>assets/libs/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script type="text/javascript">
	var table;
	$(document).ready(function() {
		table = $('#dataTabel').DataTable({
			"processing": true,
			"serverSide": true,
			"order": [],
			"ajax": {
				url: "<?php echo base_url(); ?>ukt/histori/bobot-nilai/jsondatatable",
				type: "POST"
			},
			"columnDefs": [{
				"targets": [0, 4, 5], // sesuaikan order table dengan jumlah column
				"orderable": true,
			}, ],
		});
	});
</script>