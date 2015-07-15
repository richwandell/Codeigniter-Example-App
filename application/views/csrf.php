{
  "csrf_token": ["<?php echo $this->security->get_csrf_token_name(); ?>", "<?php echo $this->security->get_csrf_hash(); ?>"],
  "flash": {
    "message": "<?php echo $message; ?>",
    "error": "<?php echo $error; ?>"
  }
}