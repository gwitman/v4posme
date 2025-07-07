<!-- ./ page heading -->
<script>
document.querySelectorAll('.reporte-item').forEach(item => {
  item.addEventListener('click', () => {
	const url = item.getAttribute('data-url');
	if (url) {
	  window.open(url, '_blank');
	}
  });
});
</script>