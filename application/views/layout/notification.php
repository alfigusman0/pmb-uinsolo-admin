
<?php $type_message = $this->session->flashdata('type_message');
if (!empty($type_message)) : ?>
	<div class="alert alert-<?= $this->session->flashdata('type_message') ?> alert-dismissible d-flex align-items-center" role="alert">
		<?= $this->session->flashdata('message'); ?>
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php endif; ?>