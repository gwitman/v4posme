<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div id="app" style="visibility: hidden;">

      <!-- Filtros -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body">
              <h6 class="card-title mb-3"><i class="bx bx-filter-alt me-1"></i>Filtros</h6>
              <div class="row g-3">
                <div class="col-6 col-md-3">
                  <label class="form-label small"><i class="bx bx-calendar me-1"></i>Fecha Inicio</label>
                  <input type="date" class="form-control form-control-sm" v-model="startOn">
                </div>
                <div class="col-6 col-md-3">
                  <label class="form-label small"><i class="bx bx-calendar me-1"></i>Fecha Fin</label>
                  <input type="date" class="form-control form-control-sm" v-model="endOn">
                </div>
                <div class="col-6 col-md-3">
                  <label class="form-label small"><i class="bx bx-transfer me-1"></i>Transacción</label>
                  <select class="form-select form-select-sm" v-model="filterTransaction">
                    <option value="19">FACTURAS</option>
                    <option value="23">ABONOS</option>
                    <option value="productSalesAmount">PRODUCTOS VENDIDOS MONTOS</option>
                    <option value="productSalesQuantity">PRODUCTOS VENDIDOS CANTIDAD</option>
                    <option value="productInventoryQuantity">PRODUCTOS CANTIDADES</option>
                  </select>
                </div>
                <div class="col-6 col-md-3">
                  <label class="form-label small"><i class="bx bx-user me-1"></i>Cliente</label>
                  <input type="text" class="form-control form-control-sm" v-model="filterCustomer" placeholder="Buscar cliente...">
                </div>
                <div class="col-12 col-md-4" v-if="filterTransaction == '19'">
                  <label class="form-label small"><i class="bx bx-package me-1"></i>Producto</label>
                  <input type="text" class="form-control form-control-sm" v-model="filterItem" placeholder="Buscar producto...">
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-12 text-end">
                  <button class="btn btn-primary btn-sm" @click="cargarListado()">
                    <i class="bx bx-search me-1"></i>Consultar
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Alerta -->
      <div class="row mb-3" v-if="mostrarAlerta">
        <div class="col-12">
          <div class="alert alert-info d-flex align-items-center" role="alert">
            <i class="bx bx-info-circle me-2"></i>
            <span>{{ mensaje }}</span>
          </div>
        </div>
      </div>

      <!-- Loading -->
      <div class="row mb-3" v-if="loading">
        <div class="col-12 text-center">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
          </div>
          <p class="mt-2 text-muted">Cargando datos...</p>
        </div>
      </div>

      <!-- Cards Resumen -->
      <div class="row mb-4" v-if="!loading && objListData.length > 0">
        <div class="col-6 col-md-3 mb-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3">
              <div class="avatar avatar-sm bg-label-primary rounded-circle mb-2 mx-auto d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                <i class="bx bx-receipt"></i>
              </div>
              <h4 class="mb-0">{{ totalDocumentos }}</h4>
              <small class="text-muted">Documentos</small>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-3 mb-3 order-first order-md-0">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3">
              <div class="avatar avatar-sm bg-label-success rounded-circle mb-2 mx-auto d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                <i class="bx bx-dollar-circle"></i>
              </div>
              <h2 class="mb-0 d-md-none">{{ formatMoney(totalMonto) }}</h2>
              <h4 class="mb-0 d-none d-md-block">{{ formatMoney(totalMonto) }}</h4>
              <small class="text-muted">Total Monto</small>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3">
              <div class="avatar avatar-sm bg-label-warning rounded-circle mb-2 mx-auto d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                <i class="bx bx-user"></i>
              </div>
              <h4 class="mb-0">{{ totalClientes }}</h4>
              <small class="text-muted">Clientes</small>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3 mb-3" v-if="filterTransaction == '19'">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center p-3">
              <div class="avatar avatar-sm bg-label-info rounded-circle mb-2 mx-auto d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                <i class="bx bx-package"></i>
              </div>
              <h4 class="mb-0">{{ totalProductos }}</h4>
              <small class="text-muted">Productos</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla FACTURAS (maestro-detalle) -->
      <div class="row" v-if="!loading && groupedData.length > 0 && filterTransaction == '19'">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
              <h6 class="mb-0"><i class="bx bx-receipt me-1"></i>Facturas</h6>
              <span class="badge bg-primary">{{ groupedData.length }} documentos</span>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="small" style="width:30px;"></th>
                      <th class="small">Documento</th>
                      <th class="small d-none d-md-table-cell">Cliente</th>
                      <th class="small text-end">Monto</th>
                      <th class="small d-none d-md-table-cell">Fecha</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="(grupo, idx) in groupedData" :key="idx">
                      <tr @click="toggleDetalle(idx)" style="cursor:pointer;" class="table-row-master">
                        <td class="small text-center">
                          <i class="bx" :class="detalleAbierto === idx ? 'bx-chevron-down text-primary' : 'bx-chevron-right'"></i>
                        </td>
                        <td class="small fw-semibold text-primary">{{ grupo.Documento }}</td>
                        <td class="small d-none d-md-table-cell">{{ grupo.Cliente }}</td>
                        <td class="small text-end fw-bold">{{ formatMoney(grupo.Monto) }}</td>
                        <td class="small d-none d-md-table-cell">{{ formatFecha(grupo.Fecha) }}</td>
                      </tr>
                      <tr v-if="detalleAbierto === idx">
                        <td :colspan="isMobile ? 3 : 5" class="p-0">
                          <div class="p-2" style="background-color: #e8f5e9; border-left: 4px solid #4caf50;">
                            <p class="small fw-semibold mb-1" style="color: #2e7d32;">
                              <i class="bx bx-user me-1"></i>Cliente: <span class="fw-bold">{{ grupo.Cliente }}</span>
                            </p>
                            <p class="small fw-semibold mb-2" style="color: #2e7d32;">
                              <i class="bx bx-calendar me-1"></i>Fecha: <span class="fw-bold">{{ formatFecha(grupo.Fecha) }}</span>
                            </p>
                            <p class="small fw-semibold mb-2" style="color: #2e7d32;"><i class="bx bx-package me-1"></i>Productos vendidos:</p>
                            <table class="table table-sm table-bordered mb-0" style="background-color: #ffffff;">
                              <thead>
                                <tr style="background-color: #c8e6c9;">
                                  <th class="small">Código</th>
                                  <th class="small">Producto</th>
                                  <th class="small text-center">Cant.</th>
                                  <th class="small text-end">Monto</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="(det, dIdx) in grupo.detalle" :key="dIdx" style="background-color: #f1f8e9;">
                                  <td class="small"><span class="badge bg-success">{{ det.Codigo }}</span></td>
                                  <td class="small">{{ det.Producto }}</td>
                                  <td class="small text-center">{{ det.Cantidad }}</td>
                                  <td class="small text-end">{{ formatMoney(det.SubMonto) }}</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla ABONOS -->
      <div class="row" v-if="!loading && objListData.length > 0 && filterTransaction == '23'">
        <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
              <h6 class="mb-0"><i class="bx bx-money me-1"></i>Abonos</h6>
              <span class="badge bg-success">{{ objListData.length }} registros</span>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="small d-none d-md-table-cell">#</th>
                      <th class="small">Documento</th>
                      <th class="small d-none d-md-table-cell">Cliente</th>
                      <th class="small text-end">Monto</th>
                      <th class="small d-none d-md-table-cell">Fecha</th>
                      <th class="small d-none d-md-table-cell">Referencia</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="(item, idx) in objListData" :key="idx">
                      <tr @click="toggleDetalleAbono(idx)" style="cursor:pointer;">
                        <td class="small d-none d-md-table-cell">{{ idx + 1 }}</td>
                        <td class="small fw-semibold text-success">{{ item.Documento }}</td>
                        <td class="small d-none d-md-table-cell">{{ item.Cliente }}</td>
                        <td class="small text-end fw-bold">{{ formatMoney(item.Monto) }}</td>
                        <td class="small d-none d-md-table-cell">{{ formatFecha(item.Fecha) }}</td>
                        <td class="small d-none d-md-table-cell"><span class="badge bg-label-info">{{ item.Referencia || '-' }}</span></td>
                      </tr>
                      <tr v-if="detalleAbonoAbierto === idx" class="d-md-none">
                        <td colspan="2" class="p-0">
                          <div class="p-2" style="background-color: #e3f2fd; border-left: 4px solid #1976d2;">
                            <p class="small mb-1"><i class="bx bx-user me-1"></i><strong>Cliente:</strong> {{ item.Cliente }}</p>
                            <p class="small mb-1"><i class="bx bx-calendar me-1"></i><strong>Fecha:</strong> {{ formatFecha(item.Fecha) }}</p>
                            <p class="small mb-0"><i class="bx bx-link me-1"></i><strong>Referencia:</strong> <span class="badge bg-label-info">{{ item.Referencia || '-' }}</span></p>
                          </div>
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
