<!--
<?php echo $icon; ?>
<?php echo $address; ?>
<?php echo $display; ?>
<?php echo $description; ?>
-->			

<?php 
	$description 	= $description;
	$short			= helper_obtenerPrimeras5Palabras($description,5);
?>

<div class="reporte-item" data-url="<?php echo $address; ?>">
  <p class="reporte-titulo"><?php echo $display; ?></p>
  
  <p class="reporte-descripcion">
    <span class="texto-corto">
      <?php echo $description; ?>
    </span>
  </p>
  
  <a href="javascript:void(0);" class="reporte-ver-mas"  onclick="
     event.preventDefault();
     event.stopPropagation();

     const item        = this.closest('.reporte-item');
     const descripcion = item.querySelector('.reporte-descripcion');

     descripcion.classList.toggle('expandido');

     this.textContent =
       descripcion.classList.contains('expandido') ? 'Menos' : 'Más';

     return false;
  " >Más</a>

  
  <div class="reporte-rating">
	<span class="star">★</span>
	<span class="star">★</span>
	<span class="star">★</span>
	<span class="star">★</span>
	<span class="star">☆</span>
  </div>
</div>
