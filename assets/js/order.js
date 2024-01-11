// Assuming data is an array of order item objects
var rowCount = 0;
var orderItemTable = "";
const barcodeInput = document.getElementById("product_upc");
var orderItemData = [];

function fireBarCodeInputEvent(inputValue) {
  const productUPCInput = document.getElementById("product_upc");

  // Create a new input event
  const inputEvent = new Event("input", {
    bubbles: true,
    cancelable: true,
  });

  // Set the input value (simulating user input)
  productUPCInput.value = inputValue;

  // Dispatch the input event on the element
  productUPCInput.dispatchEvent(inputEvent);
}

// Listen for input from the barcode reader
barcodeInput.addEventListener("input", function () {
  const scannedBarcode = barcodeInput.value;
  // Display the scanned barcode in the "barcode" field
  console.log(`Scanned Barcode: ${scannedBarcode}`);
  getProductByUPC(scannedBarcode);
});

function deleteSelectedRowOrLast() {
  var selectedRows = orderItemTable.getSelectedRows();
  // If any rows are selected, delete them
  if (selectedRows.length > 0) {
    selectedRows.forEach(function (row) {
      orderItemTable.deleteRow(row); // Use delete method on the row
    });
  } else {
    // If no rows are selected, remove the last row
    var lastRow = orderItemTable.getRows().pop(); // Get the last row
    if (lastRow) {
      lastRow.delete();
    }
  }
}

$(document).ready(function () {
  var customerAddresses = [];
  var customer_add = "";
  var productDropdownList = JSON.parse(productList);
  var categoryDropdownList = JSON.parse(categoryList);
  var productDropdownAllInfoList = JSON.parse(productAllInfoList);
  var transactionTypeList = {
    "": "Transaction Type",
    Invoice: "Invoice",
    Order: "Order",
  };

  var tableColumns = [
    {
      title: "Code",
      field: "code",
      editor: "select",
      width: 80,
      resizable: false,
      responsive: 0,
      editorParams: {
        values: productDropdownList, // Replace with your dropdown values
      },
      cellEdited: function (cell) {
        let field = cell.getField();
        let row = cell.getRow().getData();
        let value = cell.getRow().getCell(field).getValue();
        let productItem = productDropdownAllInfoList[value];

        console.log(field);
        console.log(row);
        console.log(value);
        console.log(productItem);

        // cell.getRow().getCell("cat_code").setValue(productItem.cat_code);
        cell.getRow().getCell("description").setValue(productItem.description);
        cell.getRow().getCell("qty").setValue(0);
        cell.getRow().getCell("weight").setValue(0.0);
        cell.getRow().getCell("unit").setValue(productItem.unit);
        cell.getRow().getCell("box").setValue(0);
        cell.getRow().getCell("price").setValue(productItem.price);
      },
    },
    // {
    //   title: "Category",
    //   field: "cat_code",
    //   editor: "select",
    //   width: 95,
    //   resizable: false,
    //   responsive: 0,
    //   editorParams: {
    //     values: categoryDropdownList, // Replace with your dropdown values
    //   },
    // },
    {
      title: "Description",
      field: "description",
      editor: "input",
      responsive: 0,
      resizable: false,
      width: 220,
    },
    {
      title: "Turkeys",
      field: "qty",
      responsive: 0,
      resizable: false,
      editor: "input",
    },
    {
      title: "Weight",
      field: "weight",
      responsive: 0,
      resizable: false,
      editor: "input",
    },
    {
      title: "Unit",
      field: "unit",
      resizable: false,
      responsive: 0,
      editor: "input",
    },
    {
      title: "Box",
      field: "box",
      resizable: false,
      responsive: 0,
      editor: "input",
    },
    {
      title: "Sell Price",
      field: "price",
      resizable: false,
      responsive: 0,
      editor: "input",
    },
    {
      title: "Transaction Type",
      field: "tr_type",
      resizable: false,
      responsive: 0,
      editor: "select",
      editorParams: {
        values: transactionTypeList,
      },
    },
  ];

  orderItemTable = new Tabulator("#orderItemTable", {
    reactiveData: true,
    data: orderItemData,
    selectable: true,
    columns: tableColumns,
    placeholder: "No Data Set",
    scrollToRowIfVisible: true,
    resizableColumns: false,
    layout: "fitColumns",
    responsiveLayout: "collapse",
    responsiveLayoutCollapseStartOpen: false,
  });
  var deleteButton = document.getElementById("deleteListItem");
  deleteButton.addEventListener("click", deleteSelectedRowOrLast);

  //add row to bottom of table on button click
  document.getElementById("addListItem").addEventListener("click", function () {
    orderItemTable.addRow({});
  });

  var orderDataTable = $(".item-list-dataTable").DataTable({
    //disable sorting on last column
    paging: true,
    // "bLengthChange": false,
    // "bFilter": true,
    // "bInfo": false,
    // "bAutoWidth": false,
    responsive: true,
  });
  orderDataTable.column(0).visible(false);

  $("#order_form").validate({
    errorClass: "span-error",
    errorElement: "span",
  });

  $(".open-ord-btn").click(function () {
    $(".slide-order-form-block").animate(
      {
        width: "toggle",
      },
      400
    );

    $("nav").css("filter", "blur(2px)");
    $("nav").css("pointer-events", "none");

    $("table.item-list-dataTable").css("filter", "blur(2px)");
    $("table.item-list-dataTable").css("pointer-events", "none");

    $("#footer").css("filter", "blur(2px)");
    $("#footer").css("pointer-events", "none");

    if (window.location.hash == "#new-order") {
      // Remove the hash fragment without triggering a page refresh
      history.replaceState(null, document.title, window.location.pathname);
    }
    // Reset the form
    $("form.order-form-submit")[0].reset();
    $("#ord_id").val("");
    $("#error-msg-show").html("");
    // Clear the table data
    orderItemTable.clearData();
    // Reset sorting
    orderItemTable.clearSort();
    // Reset filters
    orderItemTable.clearFilter();
    $(".invoice-number-block").hide();
  });

  function openEditOrdBtn() {
    $(".slide-order-form-block").animate(
      {
        width: "toggle",
      },
      400
    );

    $("nav").css("filter", "blur(2px)");
    $("nav").css("pointer-events", "none");

    $("table.item-list-dataTable").css("filter", "blur(2px)");
    $("table.item-list-dataTable").css("pointer-events", "none");

    $("#footer").css("filter", "blur(2px)");
    $("#footer").css("pointer-events", "none");

    if (window.location.hash == "#new-order") {
      // Remove the hash fragment without triggering a page refresh
      history.replaceState(null, document.title, window.location.pathname);
    }
  }

  $(".close-ord-btn").click(function () {
    $(".slide-order-form-block").animate(
      {
        width: "toggle",
      },
      400
    );
    $("nav").css("filter", "blur(0px)");
    $("nav").css("pointer-events", "auto");

    $("table.item-list-dataTable").css("filter", "blur(0px)");
    $("table.item-list-dataTable").css("pointer-events", "auto");

    $("#footer").css("filter", "blur(0px)");
    $("#footer").css("pointer-events", "auto");
    // Reset the form
    $("form.order-form-submit")[0].reset();
    $("#ord_id").val("");
    $("#error-msg-show").html("");
    // Clear the table data
    orderItemTable.clearData();
    // Reset sorting
    orderItemTable.clearSort();
    // Reset filters
    orderItemTable.clearFilter();
    $(".invoice-number-block").hide();
  });

  $(".item-list-dataTable").on("click", ".btn-edit-sale", function (e) {
    e.preventDefault();
    let url = $(this).attr("href");
    // console.log(url);
    openEditOrdBtn();
    commonLib.blockUI({
      target: ".order-form-submit",
      animate: true,
      overlayColor: "none",
    });

    // Make the AJAX GET request and read order information
    $.ajax({
      type: "GET",
      url: API_BASE_URL + url,
      contentType: "application/json",
      dataType: "json",
      success: function (response) {
        // Handle the successful response from the server
        // console.log(response);
        $("#orderno").val(response.data.orderno);
        $("#invoiceno").val(response.data.invoiceno);
        $("#customer_id").val(response.data.customer_id);
        customer_add = response.data.customer_add;
        $("#customer_id").trigger("change");
        if (
          response.data.reqdate != "" &&
          response.data.reqdate != "0000-00-00"
        ) {
          $("#reqdate").val(response.data.reqdate);
        }
        if (
          response.data.shipdate != "" &&
          response.data.shipdate != "0000-00-00"
        ) {
          $("#shipdate").val(response.data.shipdate);
        }
        if (
          response.data.invoicedate != "" &&
          response.data.invoicedate != "0000-00-00"
        ) {
          $("#invoicedate").val(response.data.invoicedate);
        }
        $("#shipvia").val(response.data.shipvia);
        $("#orderedby").val(response.data.orderedby);
        $("#orderdate").val(response.data.orderdate);
        $("#ord_terms").val(response.data.ord_terms);
        $("#ord_id").val(response.data.id);
        if (response.data.istatus === "1") {
          $("input[name='istatus'][value='1']").prop("checked", true);
        } else {
          $("input[name='istatus'][value='0']").prop("checked", true);
        }
        $("#status_ord").val(response.data.status_ord);

        $(".invoice-number-block").show();
        $("#orderSubmit").text("Update");
        $(".order-form-header").text("Update Order");

        $("#scan-ord-item-list tbody").html("");
        let productItemTablu = {};
        response.data.product_item.forEach(function (item, index) {
          // Get the last added row (the one we just triggered)
          var lastRow = $("#scan-ord-item-list tbody");
          productItemTablu = {
            code: item.code,
            // cat_code: item.cat_code,
            description: item.description,
            qty: item.qty,
            weight: item.weight,
            unit: item.unit,
            box: item.box,
            price: item.price,
            tr_type: item.tr_type,
          };
          orderItemTable.addRow(productItemTablu);
        });
        commonLib.unblockUI(".order-form-submit");
      },
      error: function (xhr, status, error) {
        // Handle errors
        console.error(error);
        commonLib.unblockUI(".order-form-submit");
      },
    });

    return false;
  });

  $("#customer_id").on("change", function (e) {
    let customer = $(this).val();
    commonLib.blockUI({
      target: ".order-form-submit",
      animate: true,
      overlayColor: "none",
    });

    // Make the AJAX GET request and read order information
    let formData = {
      customerId: customer,
    };
    $.ajax({
      type: "POST",
      url: API_BASE_URL + "customer/addresses",
      data: JSON.stringify(formData),
      contentType: "application/json",
      dataType: "json",
      success: function (response) {
        // Handle the successful response from the server
        // console.log(response.data);
        commonLib.unblockUI(".order-form-submit");
        customerAddresses = response.data;

        // Clear existing options
        $("#customer_add").empty();
        // add empty options
        $("#customer_add").append(
          $("<option>", {
            value: "",
            text: "Select Customer Address",
          })
        );
        // Loop through the response and create options
        $.each(response.data, function (index, address) {
          $("#customer_add").append(
            $("<option>", {
              value: address.id, // Assuming your address object has an 'id' property
              text: address.name, // Assuming your address object has an 'name' property
            })
          );
        });
        $("#customer_add").val(customer_add);
        $("#customer_add").trigger("change");
      },
      error: function (xhr, status, error) {
        // Handle errors
        console.error(error);
        commonLib.unblockUI(".order-form-submit");
      },
    });
  });

  $("#customer_add").on("change", function () {
    let customerAdd = $(this).val();
    // console.log(customerAdd);
    // console.log(customerAddresses);

    $.each(customerAddresses, function (index, address) {
      // console.log(customerAdd);
      // console.log(address);

      if (address.id == customerAdd) {
        $("#ord_address1").val(address.address1);
        $("#ord_address2").val(address.address2);
        $("#ord_city").val(address.city);
        $("#ord_province").val(address.province);
        $("#ord_postal_code").val(address.postalCode);
      }
    });
  });

  $(".order-form-submit").on("submit", function (e) {
    e.preventDefault();
    commonLib.blockUI({
      target: ".order-form-submit",
      animate: true,
      overlayColor: "none",
    });
    if ($("#order_form").valid()) {
      var formData = new FormData(this);
      console.log(formData);

      url = API_BASE_URL + "order/create";
      let ord_id = $("#ord_id").val();
      if (ord_id) {
        url = API_BASE_URL + "order/update";
      }

      let listTableData = orderItemTable.getData();
      console.log(listTableData);

      // Loop through the listTableData and append each item as a form field
      listTableData.forEach(function (row, index) {
        for (let rowKey in row) {
          formData.append(
            "product_item[" + index + "][" + rowKey + "]",
            row[rowKey]
          );
        }
      });
      console.log(formData);

      $.ajax({
        type: "POST",
        url: url,
        data: formData,
        processData: false, // Important: Don't process the data
        contentType: false, // Important: Don't set the content type
        success: function (response) {
          // Handle the successful response from the server
          // console.log(response.data);
          commonLib.unblockUI(".order-form-submit");
          if (response.status == true) {
            let title = "Success";
            let type = "success";
            commonLib.iniToastrNotification(type, title, response.mmsg);
            // Trigger the click event programmatically
            $(".close-ord-btn").trigger("click");
            // Add new data to the DataTable
            // orderDataTable.draw();
            location.reload();
          } else if (
            response.status == "orderno" ||
            response.status == "invoiceno"
          ) {
            let alertError = "";
            alertError +=
              '<div class="alert alert-danger alert-dismissible" role="alert">';
            alertError +=
              '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
            alertError += '<span aria-hidden="true">&times;</span>';
            alertError += "</button>";
            alertError += response.mmsg;
            alertError += "</div>";
            $("#error-msg-show").html(alertError);
          } else {
            let title = "Error";
            let type = "error";
            commonLib.iniToastrNotification(type, title, response.mmsg);
            // Trigger the click event programmatically
            $(".close-frm-btn").trigger("click");
          }
        },
        error: function (xhr, status, error) {
          // Handle errors
          console.error(error);
          commonLib.unblockUI(".order-form-submit");
        },
      });
    } else {
      commonLib.unblockUI(".order-form-submit");
    }

    return false;
  });

  $(".item-list-dataTable").on("click", "a.btn-delete-sale", function (e) {
    e.preventDefault();
    let url = $(this).attr("href");

    let deleteCnf = $.confirm({
      title: "Delete Item!",
      content: "Are you sure you want to delete this item?",
      type: "red",
      typeAnimated: true,
      columnClass:
        "col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1",
      buttons: {
        close: {
          text: "NO",
          function() {},
        },
        ok: {
          text: "YES",
          btnClass: "btn-red",
          action: function () {
            deleteCnf.close();
            deleteOrder(url);
          },
        },
      },
    });

    return false;
  });

  // Check if the current URL has a hash fragment
  if (window.location.hash == "#new-order") {
    $(".open-ord-btn").trigger("click");
  }

  //order scanning
  $(".btn-ord-scan").on("click", function (e) {
    $("#product_upc").focus();
    $("#product_upc_off").text("Start Scanning...");
    // getProductByUPC();
  });

  $(".item-list-dataTable").on("click", ".invoice-generate", function (e) {
    setTimeout(function () {
      location.reload();
    }, 1500);
  });

  $("#importCSVListItem").on("click", function (e) {
    let timeStamp = new Date().getTime();
    let filename = `${timeStamp}.csv`;
    //download a CSV file that uses a fullstop (,) delimiter
    orderItemTable.download("csv", filename, { delimiter: "," });
  });
});

function deleteOrder(url) {
  commonLib.blockUI({
    target: ".order-list-block",
    animate: true,
    overlayColor: "none",
  });

  $.ajax({
    type: "GET",
    url: API_BASE_URL + url,
    contentType: "application/json",
    dataType: "json",
    success: function (response) {
      // Handle the successful response from the server
      console.log(response.data);
      commonLib.unblockUI(".order-list-block");
      location.reload();
    },
    error: function (xhr, status, error) {
      // Handle errors
      console.error(error);
      commonLib.unblockUI(".order-list-block");
    },
  });
}
function returnProductWeight(scannedBarcode) {
  const inputString = scannedBarcode;
  const extractedSubstring = inputString.substring(22, 26);
  // Calculate the middle index
  const middleIndex = Math.floor(extractedSubstring.length / 2);
  const firstPart = extractedSubstring.substring(0, middleIndex);
  const secondPart = extractedSubstring.substring(middleIndex);
  // Remove leading "0" by parsing as an integer
  const firstPartWithoutLeadingZero = parseInt(firstPart, 10);
  const resultString =
    firstPartWithoutLeadingZero.toString() + "." + secondPart;
  console.log(resultString);

  return resultString;
}

function getProductByUPC(scannedBarcode) {
  console.log("scannedBarcode");
  console.log(scannedBarcode);
  if (scannedBarcode.length == 48) {
    $(".scan-ord-item-loading").show();
    $("#product_upc").blur();
    $.ajax({
      type: "GET",
      url: API_BASE_URL + "product/upc/" + scannedBarcode,
      contentType: "application/json",
      dataType: "json",
      success: function (response) {
        // Handle the successful response from the server
        console.log(response.data);
        let productItemTablu = {};

        response.data.forEach(function (item) {
          // Get the last added row (the one we just triggered)
          var lastRow = $("#scan-ord-item-list tbody");
          let pro_weight = 0.0;
          pro_weight = returnProductWeight(scannedBarcode);

          productItemTablu = {
            code: item.code,
            // cat_code: item.cat_code,
            description: item.description,
            qty: item.item_in_box,
            weight: pro_weight,
            unit: item.unit,
            box: 1,
            price: item.price,
            tr_type: "",
          };
          orderItemTable.addRow(productItemTablu);
        });
        $(".scan-ord-item-loading").hide();

        $("#product_upc").val("");
        $("#product_upc").focus();
      },
      error: function (xhr, status, error) {
        // Handle errors
        console.error(error);
        $(".scan-ord-item-loading").hide();
        $("#product_upc").val("");
        $("#product_upc").focus();
      },
    });
  }
}
function productItemDec() {
  $(".dec")
    .off()
    .on("click", function () {
      var inputField = $(this).siblings(".inc-dec-value");
      var quantity = parseInt(inputField.val());
      if (quantity > 0) {
        inputField.val(quantity - 1);
      }
    });
}
function productItemInc() {
  $(".inc")
    .off()
    .on("click", function (e) {
      e.preventDefault();
      console.log($(this));
      var inputField = $(this)
        .parents(".inc-dec-wrapper")
        .find(".inc-dec-value");
      var quantity = parseInt(inputField.val());
      console.log(quantity);
      inputField.val(+quantity + 1);

      return false;
    });
}

function productItemDelete() {
  $(".delete-item")
    .off()
    .on("click", function () {
      $(this).closest("tr").remove();
      rowCount--;
    });
}
