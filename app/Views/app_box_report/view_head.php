<style>
.recent-activity h2 {
  text-align: center;
  margin-bottom: 30px;
  color: #333;
}

.recent-activity .reportes-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  max-width: 1200px;
  margin: 0 auto;
  grid-auto-rows: 1fr;
}

.recent-activity .reporte-item {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.recent-activity .reporte-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.recent-activity .reporte-titulo {
  font-size: 12px;
  font-weight: bold;
  color: #333;
  margin: 0 0 10px;
}

.recent-activity .reporte-descripcion {
  font-size: 12px;
  color: #555;
  flex-grow: 1;
}

.recent-activity .reporte-rating {
  margin-top: 15px;
}

.recent-activity .star {
  color: #ffc107;
  font-size: 20px;
  margin-right: 2px;
}

@media (max-width: 900px) {
  .recent-activity .reportes-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 600px) {
  .recent-activity .reportes-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<div id="heading" class="page-header">
			<h1><i class="icon20  i-stack"></i> Caja</h1>
</div> 