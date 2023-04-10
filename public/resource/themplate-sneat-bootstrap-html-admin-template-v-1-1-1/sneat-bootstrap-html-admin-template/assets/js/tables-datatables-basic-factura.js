/**
 * DataTables Basic
 */



function createTableCustomer(){
  var dt_basic_table = $('.datatables-basic');
   
   
  if(varObjDataTableCustomer.length != undefined){
	  varObjDataTableCustomer.destroy();
  }
  
  // DataTable with buttons
  // --------------------------------------------------------------------

  if (dt_basic_table.length) {
      varObjDataTableCustomer = dt_basic_table.DataTable({
      data : objListaClientes,
      columns: [        
	    { data: 'entityID' },
        { data: 'companyID' },
        { data: 'entityID' },
        { data: 'Codigo' },
        { data: 'Nombre' },
        { data: 'Apellidos' },
        { data: 'Comercial' }     
      ],
      columnDefs: [       
        {
          // For Checkboxes
          targets: 0,
          orderable: false,
          searchable: false,
          responsivePriority: 3,
          checkboxes: true,
          render: function () {
            return '<input type="checkbox" class="dt-checkboxes form-check-input">';
          },
          checkboxes: {
            selectAllRender: '<input type="checkbox" class="form-check-input">'
          }
        },
        {
          targets: 1,
          searchable: false,
          visible: false
        },
		 {
          targets: 2,
          searchable: false,
          visible: false
        },
        {          
          targets: 3,
          responsivePriority: 4
        },
        {
          responsivePriority: 1,
          targets: 4
        },
		{
          responsivePriority: 1,
          targets: 5
        },
		{
          responsivePriority: 1,
          targets: 6
        }
        
        
      ],
      order: [[2, 'desc']],	  
      dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      displayLength: 7,
      lengthMenu: [7, 10, 25, 50, 75, 100],
      buttons: [
      
      ],
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Detalle de ' + data['Nombre'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
    $('div.head-label').html('<h5 class="card-title mb-0">Lista de clientes:</h5>');
  }


}

function createTableProductos(){
  var dt_basic_table = $('.datatables-basic-productos');
    

  if(varObjDataTableProducto.length != undefined){
	  varObjDataTableProducto.destroy();
  }
  
  //Obtener la lista de productos a render
  var objListProductosRenderGrid = jLinq.from(objListaProductos).where(function(obj){ 
			var result = 
			parseInt(obj.warehouseID) == parseInt($("#txtBodegasOrigen").val()) && 
			parseInt(obj.typePriceID) == parseInt($("#txtTiposDePrecios").val());
			
			return result;
			
  }).select();
		
  
  
  // DataTable with buttons
  // --------------------------------------------------------------------

  if (dt_basic_table.length) {
      varObjDataTableProducto = dt_basic_table.DataTable({
      data : objListProductosRenderGrid,
      columns: [        
	    { data: 'itemID' },
        { data: 'itemID' },
        { data: 'itemID' },
        { data: 'Codigo' },
        { data: 'Barra' },
        { data: 'Nombre' }
      ],
      columnDefs: [       
        {
          // For Checkboxes
          targets: 0,
          orderable: false,
          searchable: false,
          responsivePriority: 3,
          checkboxes: true,
          render: function () {
            return '<input type="checkbox" class="dt-checkboxes form-check-input">';
          },
          checkboxes: {
            selectAllRender: '<input type="checkbox" class="form-check-input">'
          }
        },
        {
          targets: 1,
          searchable: false,
          visible: false
        },
		 {
          targets: 2,
          searchable: false,
          visible: false
        },
        {          
          targets: 3,
          responsivePriority: 4
        },
        {
          responsivePriority: 1,
          targets: 4
        },
		{
          responsivePriority: 1,
          targets: 5
        }
        
      ],
      order: [[2, 'desc']],	  
      dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      displayLength: 7,
      lengthMenu: [7, 10, 25, 50, 75, 100],
      buttons: [
      
      ],
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Detalle de ' + data['Codigo'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
    $('div.head-label').html('<h5 class="card-title mb-0">Lista de productos:</h5>');
  }


}

